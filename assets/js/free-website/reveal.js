import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';
import { revealFailsafe } from '../components/reveal-failsafe';

gsap.registerPlugin(SplitText);

// Free-website page entrances. The hero targets are pre-hidden in CSS (html.js
// scope, 2.5s failsafe); everything below the fold is hidden here at
// DOMContentLoaded and revealed by IntersectionObserver. Under reduced motion,
// without JS, or without IntersectionObserver nothing is ever hidden
// (misc/_motion.scss forces the finished state).
document.addEventListener('DOMContentLoaded', () => {
    const hero = document.querySelector('.fw-hero');
    if (!hero) return;

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
        // CSS pre-hides the hero under html.js; hand it straight back.
        gsap.set(['.fw-hero h1', '.fw-hero .sub-heading', '.fw-hero .hero-actions', '.fw-hero .hero-note'], { opacity: 1 });
        return;
    }

    // ---- Hero (above the fold: runs on load, once fonts have settled) ----
    // lg+ only. Below lg the hero text paints with the first frame through the
    // CSS entrance in misc/_motion.scss (it is the page's LCP element), and the
    // stylesheet no longer pre-hides it there.
    const heroOnJs = window.matchMedia('(min-width: 992px)').matches;
    const heroH1 = hero.querySelector('h1');
    const heroSub = hero.querySelector('.sub-heading');
    const heroActions = hero.querySelector('.hero-actions');
    const heroNote = hero.querySelector('.hero-note');
    const heroRule = hero.querySelector('.hero-rule');

    if (heroRule && heroOnJs) gsap.set(heroRule, { scaleX: 0 });

    if (heroOnJs) document.fonts.ready.then(() => {
        const tl = gsap.timeline({ defaults: { ease: 'expo.out' } });

        if (heroH1) {
            SplitText.create(heroH1, {
                type: 'lines',
                linesClass: 'line',
                mask: 'lines',
                aria: 'none',
                autoSplit: false,
                onSplit(self) {
                    gsap.set(heroH1, { opacity: 1 });
                    tl.from(self.lines, {
                        yPercent: 110,
                        opacity: 0,
                        duration: 0.85,
                        stagger: 0.1,
                        onComplete: () => self.revert(),
                    }, 0.1);
                },
            });
        }

        if (heroSub) tl.to(heroSub, { opacity: 1, duration: 0.6 }, 0.45);
        if (heroActions) tl.to(heroActions, { opacity: 1, duration: 0.6 }, 0.6);
        if (heroNote) tl.to(heroNote, { opacity: 1, duration: 0.6 }, 0.75);
        if (heroRule) tl.to(heroRule, { scaleX: 1, duration: 0.9, ease: 'power3.out' }, 0.8);
    });

    // ---- Below the fold ----
    const headings = gsap.utils.toArray('.fw-how .content h2, .fw-ledger .content h2, .fw-contrast .content h2, .fw-proof .content h2, .fw-faq .content h2, .fw-enquire .content h2');
    // No blanket fade-ups: sub-headings, the FAQ and the form simply sit there
    // (the form is what the page is for). Motion goes to the things that are
    // sequences: the steps rise along their rail, the comparison rows land one
    // by one, and the ledger prints itself (ledger.js).
    const fades = [];
    const steps = gsap.utils.toArray('.fw-how .how-step');
    const contrastRows = gsap.utils.toArray('.fw-contrast .compare-row');

    gsap.set([...headings, ...fades], { opacity: 0, y: 24 });
    revealFailsafe([...headings, ...fades], 4000);
    // Steps rise on entrance only; their opacity and the rail belong to the
    // scroll fill in how-scroll.js.
    gsap.set(steps, { y: 24 });
    if (contrastRows.length) gsap.set(contrastRows, { opacity: 0, y: 22 });
    if (contrastRows.length) revealFailsafe(contrastRows, 4000);

    const showHeading = (el) => {
        SplitText.create(el, {
            type: 'lines',
            linesClass: 'line',
            mask: 'lines',
            autoSplit: false,
            onSplit(self) {
                gsap.set(el, { opacity: 1, y: 0 });
                return gsap.from(self.lines, {
                    yPercent: 110,
                    opacity: 0,
                    duration: 0.7,
                    stagger: 0.09,
                    ease: 'expo.out',
                    onComplete: () => self.revert(),
                });
            },
        });
    };

    const showFade = (el) => {
        gsap.to(el, { opacity: 1, y: 0, duration: 0.55, ease: 'power2.out' });
    };

    const handlers = new Map();
    headings.forEach((el) => handlers.set(el, showHeading));
    fades.forEach((el) => handlers.set(el, showFade));

    // Steps rise in together on entrance (the scroll fill brightens them).
    const stepsGrid = document.querySelector('.fw-how .fw-how-steps');
    if (stepsGrid && steps.length) {
        handlers.set(stepsGrid, () => {
            gsap.to(steps, { y: 0, duration: 0.6, stagger: 0.14, ease: 'power2.out' });
        });
    }

    // Each comparison row rises in sequence.
    const contrastCompare = document.querySelector('.fw-contrast .contrast-compare');
    if (contrastCompare && contrastRows.length) {
        handlers.set(contrastCompare, () => {
            gsap.to(contrastRows, { opacity: 1, y: 0, duration: 0.6, stagger: 0.09, ease: 'power2.out' });
        });
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const fn = handlers.get(entry.target);
            if (fn) fn(entry.target);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -4% 0px' });

    document.fonts.ready.then(() => {
        handlers.forEach((fn, el) => observer.observe(el));
    });
});
