import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// The founders duo panels. Base markup renders stacked and fully open (the
// no-JS and reduced-motion state, aria-expanded="true" server-side); this
// module takes over only when motion is allowed, via gsap.matchMedia:
//  - lg+: the two panels tween flex-basis (50/50 <-> 44/18) under
//    justify-content: space-between, so opening a founder parts the row and
//    that founder's bio plate fades into the middle gap beside their own
//    portrait; the other panel crossfades to a veiled vertical-name spine.
//    No text ever re-wraps mid-tween: captions have a fixed-width inner, the
//    plate width is set in px here, and the spine swap is opacity only.
//  - below lg: stacked cards with independent height-tweened accordions.
// Every expand/collapse ends with ScrollTrigger.refresh() so the scrubbed
// sections below never work from stale positions.
document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('.about-founders');
    if (!section) return;

    const duo = section.querySelector('.founders-duo');
    const panels = gsap.utils.toArray('.founder-panel', section);
    const bios = panels.map((panel) => panel.querySelector('.founder-bio'));
    const captions = panels.map((panel) => panel.querySelector('.founder-caption'));
    const spines = panels.map((panel) => panel.querySelector('.founder-spine'));
    const allToggles = gsap.utils.toArray('.founder-toggle', section);
    if (panels.length < 2 || bios.some((el) => !el) || captions.some((el) => !el)) return;

    const panelIndexOf = (toggle) => panels.indexOf(toggle.closest('.founder-panel'));

    const syncAria = (openIndex) => {
        allToggles.forEach((toggle) => {
            const i = panelIndexOf(toggle);
            const expanded = openIndex === true ? true : openIndex === i;
            toggle.setAttribute('aria-expanded', String(expanded));
        });
    };

    // Panels rise in once on first view; entrance touches opacity/y only, so
    // an expand started mid-entrance never fights it.
    const addEntrance = () => {
        if (!('IntersectionObserver' in window)) return () => {};
        gsap.set(panels, { opacity: 0, y: 36 });
        const io = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                gsap.to(panels, {
                    opacity: 1,
                    y: 0,
                    duration: 0.7,
                    stagger: 0.14,
                    ease: 'power2.out',
                    clearProps: 'opacity,transform',
                });
                obs.unobserve(entry.target);
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -4% 0px' });
        io.observe(duo);
        return () => io.disconnect();
    };

    const mm = gsap.matchMedia();

    // Desktop duo.
    mm.add('(min-width: 992px) and (prefers-reduced-motion: no-preference)', () => {
        section.classList.add('is-enhanced', 'is-duo');

        // Rest leaves a slim gap between the two panels; open leaves the same
        // order of gap between the plate and the spine (44 + 38 + 17 = 99),
        // so the founders always read as clearly separated.
        const OPEN = 44;
        const SPINE = 17;
        const REST = 49.4;
        const PLATE_RATIO = 0.38;
        let open = null;
        let tl = null;

        // The plate is laid out once at a fixed px width so its text never
        // re-wraps while the panels tween around it.
        const sizePlates = () => {
            const width = Math.round(duo.getBoundingClientRect().width * PLATE_RATIO);
            bios.forEach((bio) => { bio.style.width = width + 'px'; });
        };
        sizePlates();

        // Equal short-bio heights so the two caption blocks line up at rest
        // (the testimonials equaliser pattern); re-measured on resize and
        // once webfonts settle.
        const shorts = panels.map((panel) => panel.querySelector('.founder-short'));
        const equaliseShorts = () => {
            shorts.forEach((el) => { el.style.minHeight = ''; });
            const tallest = Math.max(...shorts.map((el) => el.offsetHeight));
            shorts.forEach((el) => { el.style.minHeight = tallest + 'px'; });
        };
        equaliseShorts();
        let fontsLive = true;
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(() => { if (fontsLive) equaliseShorts(); });
        }

        gsap.set(panels, { flexBasis: REST + '%' });
        gsap.set(bios, { autoAlpha: 0 });
        gsap.set(spines, { autoAlpha: 0 });
        syncAria(null);

        const setState = (next) => {
            if (tl) tl.kill();
            open = next;
            panels.forEach((panel, i) => {
                panel.classList.toggle('is-open', i === next);
                panel.classList.toggle('is-spine', next !== null && i !== next);
            });
            syncAria(next);

            tl = gsap.timeline({ onComplete: () => ScrollTrigger.refresh() });
            bios.forEach((bio, i) => {
                if (i !== next) tl.to(bio, { autoAlpha: 0, duration: 0.2, ease: 'power2.out' }, 0);
            });
            panels.forEach((panel, i) => {
                const spineNow = next !== null && i !== next;
                tl.to(captions[i], { autoAlpha: spineNow ? 0 : 1, duration: spineNow ? 0.2 : 0.35, ease: 'power2.out' }, spineNow ? 0 : 0.3);
                tl.to(spines[i], { autoAlpha: spineNow ? 1 : 0, duration: spineNow ? 0.35 : 0.15, ease: 'power2.out' }, spineNow ? 0.35 : 0);
                const basis = next === null ? REST : (i === next ? OPEN : SPINE);
                tl.to(panel, { flexBasis: basis + '%', duration: 0.7, ease: 'expo.out' }, 0.05);
            });
            if (next !== null) {
                tl.fromTo(bios[next], { x: 16 }, { autoAlpha: 1, x: 0, duration: 0.45, ease: 'power2.out' }, 0.28);
            }
        };

        const controller = new AbortController();
        allToggles.forEach((toggle) => {
            toggle.addEventListener('click', () => {
                const i = panelIndexOf(toggle);
                setState(open === i ? null : i);
            }, { signal: controller.signal });
        });

        let resizeTimer = null;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                sizePlates();
                equaliseShorts();
                ScrollTrigger.refresh();
            }, 150);
        }, { signal: controller.signal });

        const removeEntrance = addEntrance();

        return () => {
            controller.abort();
            removeEntrance();
            if (tl) tl.kill();
            clearTimeout(resizeTimer);
            fontsLive = false;
            bios.forEach((bio) => { bio.style.width = ''; });
            shorts.forEach((el) => { el.style.minHeight = ''; });
            section.classList.remove('is-enhanced', 'is-duo');
            panels.forEach((panel) => panel.classList.remove('is-open', 'is-spine'));
            syncAria(true);
        };
    });

    // Stacked accordions below lg.
    mm.add('(max-width: 991.98px) and (prefers-reduced-motion: no-preference)', () => {
        section.classList.add('is-enhanced', 'is-accordion');

        const openStates = panels.map(() => false);
        gsap.set(bios, { height: 0, autoAlpha: 0 });
        syncAria(null);

        const controller = new AbortController();
        allToggles.forEach((toggle) => {
            toggle.addEventListener('click', () => {
                const i = panelIndexOf(toggle);
                openStates[i] = !openStates[i];
                panels[i].classList.toggle('is-open', openStates[i]);
                allToggles.forEach((t) => {
                    if (panelIndexOf(t) === i) t.setAttribute('aria-expanded', String(openStates[i]));
                });
                gsap.to(bios[i], {
                    height: openStates[i] ? 'auto' : 0,
                    autoAlpha: openStates[i] ? 1 : 0,
                    duration: 0.5,
                    ease: 'power2.out',
                    onComplete: () => ScrollTrigger.refresh(),
                });
            }, { signal: controller.signal });
        });

        const removeEntrance = addEntrance();

        return () => {
            controller.abort();
            removeEntrance();
            section.classList.remove('is-enhanced', 'is-accordion');
            panels.forEach((panel) => panel.classList.remove('is-open'));
            syncAria(true);
        };
    });
});
