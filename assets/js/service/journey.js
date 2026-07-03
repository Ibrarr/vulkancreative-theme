import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// The engagement journey. Desktop with motion allowed: the section is
// promoted (is-journey) to a sticky sideways travel — the track's x is
// scrubbed so 1px of scroll moves 1px of track (the homepage services
// mechanic), the ember fill sweeps the line on the same trigger, each plate
// forges (is-forged) as the tip reaches its node, and the head counter ticks.
// Below lg with motion, the same markup is a vertical ledger whose rail draws
// with the scroll (the deliverables mechanic). Reduced motion or no JS: the
// base CSS already renders the finished state — fills drawn, numerals solid,
// counter server-rendered complete — so this module only ever winds the state
// back inside its own motion contexts.
document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('.service-journey');
    if (!section) return;

    const rail = section.querySelector('.journey-rail');
    const track = section.querySelector('.journey-track');
    const fill = section.querySelector('.journey-progress');
    const steps = gsap.utils.toArray('.journey-step', section);
    const counter = section.querySelector('.journey-counter .counter-current');
    if (!rail || !track || !fill || !steps.length) return;

    const setCounter = (n) => {
        if (counter) counter.textContent = String(Math.max(1, n)).padStart(2, '0');
    };
    const recount = () => setCounter(section.querySelectorAll('.journey-step.is-forged').length);

    // In-place scrub shared by the vertical ledger and the no-travel desktop
    // case: the fill draws across the rail and plates forge at a fixed line.
    const inPlaceScrub = (axis) => {
        gsap.set(fill, { [axis]: 0 });
        steps.forEach((step) => step.classList.remove('is-forged'));
        setCounter(1);

        const fillTween = gsap.fromTo(fill, { [axis]: 0 }, {
            [axis]: 1,
            ease: 'none',
            scrollTrigger: { trigger: track, start: 'top 70%', end: 'bottom 60%', scrub: 0.5 },
        });
        const triggers = steps.map((step) => ScrollTrigger.create({
            trigger: step,
            start: 'top 66%',
            onEnter: () => { step.classList.add('is-forged'); recount(); },
            onLeaveBack: () => { step.classList.remove('is-forged'); recount(); },
        }));

        return () => {
            triggers.forEach((t) => t.kill());
            if (fillTween.scrollTrigger) fillTween.scrollTrigger.kill();
            fillTween.kill();
            gsap.set(fill, { clearProps: 'transform' });
            steps.forEach((step) => step.classList.add('is-forged'));
            setCounter(steps.length);
        };
    };

    const mm = gsap.matchMedia();

    mm.add('(min-width: 992px) and (prefers-reduced-motion: no-preference)', () => {
        section.classList.add('is-journey');

        const distance = () => Math.max(0, track.scrollWidth - rail.clientWidth);

        // A short journey on a wide viewport has nothing to travel: keep the
        // natural section and scrub in place instead.
        if (distance() === 0) {
            section.classList.add('no-travel');
            const cleanup = inPlaceScrub('scaleX');
            return () => {
                cleanup();
                section.classList.remove('is-journey', 'no-travel');
            };
        }

        // The travel takes 1.75x its own distance in scroll, so the journey
        // reads as a passage rather than a flick: the scrollbar stays the only
        // driver, the track just moves a little slower than the thumb.
        const SCROLL_RATIO = 1.75;
        const setHeight = () => {
            section.style.height = window.innerHeight + Math.round(distance() * SCROLL_RATIO) + 'px';
        };
        setHeight();

        gsap.set(fill, { scaleX: 0 });
        steps.forEach((step) => step.classList.remove('is-forged'));
        section.classList.remove('is-complete');
        setCounter(1);

        const travel = gsap.to(track, {
            x: () => -distance(),
            ease: 'none',
            scrollTrigger: {
                trigger: section,
                start: 'top top',
                end: 'bottom bottom',
                scrub: 1,
                invalidateOnRefresh: true,
                onRefreshInit: setHeight,
                onUpdate(self) {
                    // The tip has swept this far along the track; a plate is
                    // forged the instant the tip strikes its node (the marker
                    // row's first child, at the plate's left edge).
                    const swept = self.progress * track.scrollWidth;
                    let forged = 0;
                    steps.forEach((step) => {
                        const on = swept >= step.offsetLeft + 5;
                        step.classList.toggle('is-forged', on);
                        if (on) forged++;
                    });
                    setCounter(forged);
                    // The terminus ignites once the whole line is swept.
                    section.classList.toggle('is-complete', self.progress >= 0.995);
                },
            },
        });
        const fillTween = gsap.to(fill, {
            scaleX: 1,
            ease: 'none',
            scrollTrigger: { trigger: section, start: 'top top', end: 'bottom bottom', scrub: 1 },
        });

        return () => {
            if (travel.scrollTrigger) travel.scrollTrigger.kill();
            travel.kill();
            if (fillTween.scrollTrigger) fillTween.scrollTrigger.kill();
            fillTween.kill();
            gsap.set([track, fill], { clearProps: 'transform' });
            section.style.height = '';
            section.classList.remove('is-journey', 'is-complete');
            steps.forEach((step) => step.classList.add('is-forged'));
            setCounter(steps.length);
        };
    });

    mm.add('(max-width: 991.98px) and (prefers-reduced-motion: no-preference)', () => inPlaceScrub('scaleY'));
});
