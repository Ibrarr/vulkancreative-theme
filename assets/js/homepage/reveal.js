import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';
import { revealFailsafe } from '../components/reveal-failsafe';

gsap.registerPlugin(SplitText);

// Section entrances without pops: with motion allowed, the targets are hidden
// at load (they all sit below the fold) and revealed by IntersectionObserver
// as they enter the viewport — headings with a SplitText line-mask rise,
// everything else with a gentle fade. Under reduced motion, without JS, or
// without IntersectionObserver nothing is ever hidden.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const headings = gsap.utils.toArray('.results .content h2, .work .content h2, .our-work .content h2, .why .content h2, .process .content h2, .testimonials .content h2, .latest-insights .content h2');
    // No blanket fade-ups: sub-headings and links simply sit there. Motion goes
    // to the things that are lists of objects (steps, cards, badges cascade in).
    const fades = [];
    const steps = gsap.utils.toArray('.process .process-steps .process-step');
    const latestCards = gsap.utils.toArray('.latest-insights .insight-card');
    const badges = gsap.utils.toArray('.why .partner-logos-row .partner-logos-item');

    // Hide up front, before the user can ever see these sections.
    gsap.set([...headings, ...fades, ...steps, ...latestCards], { opacity: 0, y: 24 });
    gsap.set(badges, { opacity: 0, y: 12 });
    revealFailsafe([...headings, ...fades, ...steps, ...latestCards, ...badges], 4000);

    const showHeading = (el) => {
        SplitText.create(el, {
            type: 'lines',
            linesClass: 'line',
            mask: 'lines',
            aria: 'none',
            autoSplit: false,
            onSplit(self) {
                gsap.set(el, { opacity: 1, y: 0 });
                return gsap.from(self.lines, {
                    yPercent: 110,
                    opacity: 0,
                    duration: 0.7,
                    stagger: 0.09,
                    ease: 'expo.out',
                    // The display headings run line-height 1, so the overflow
                    // mask clips descenders; restore the intact heading once
                    // the reveal has finished.
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

    // The steps stagger in together when the grid enters
    const stepsGrid = document.querySelector('.process .process-steps');
    if (stepsGrid && steps.length) {
        handlers.set(stepsGrid, () => {
            gsap.to(steps, { opacity: 1, y: 0, duration: 0.5, stagger: 0.08, ease: 'power2.out' });
        });
    }

    // The partner badges land one after another, left to right
    const badgeRow = document.querySelector('.why .partner-logos-row');
    if (badgeRow && badges.length) {
        handlers.set(badgeRow, () => {
            gsap.to(badges, { opacity: 1, y: 0, duration: 0.5, stagger: 0.06, ease: 'power2.out' });
        });
    }

    // Latest insights cards stagger in when their grid enters
    const latestGrid = document.querySelector('.latest-insights .row');
    if (latestGrid && latestCards.length) {
        handlers.set(latestGrid, () => {
            gsap.to(latestCards, { opacity: 1, y: 0, duration: 0.5, stagger: 0.07, ease: 'power2.out' });
        });
    }

    // Reveal immediately on intersect. The display fonts are preloaded, so
    // they are long settled by the time anything below the fold is reached;
    // waiting on document.fonts here can stall reveals behind unrelated late
    // font requests and make whole sections pop in together.
    const fire = (el) => {
        const fn = handlers.get(el);
        if (fn) fn(el);
    };

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            fire(entry.target);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -4% 0px' });

    // Observation starts once fonts are active: SplitText must measure the
    // settled metrics (Archivo's width stretch changes line breaks), or a
    // heading can animate with fallback-font wrapping and rewrap when the
    // reveal reverts. fonts.ready resolves immediately on a warm cache.
    document.fonts.ready.then(() => {
        handlers.forEach((fn, el) => observer.observe(el));
    });
});
