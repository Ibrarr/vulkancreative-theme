import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(SplitText);

// Section entrances without pops: with motion allowed, the targets are hidden
// at load (they all sit below the fold) and revealed by IntersectionObserver
// as they enter the viewport — headings with a SplitText line-mask rise,
// everything else with a gentle fade. Under reduced motion, without JS, or
// without IntersectionObserver nothing is ever hidden.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const headings = gsap.utils.toArray('.results .content h2, .work .content h2, .our-work .content h2, .why .content h2, .process .content h2, .testimonials .content h2');
    const fades = gsap.utils.toArray('.why .content .sub-heading, .our-work .content .sub-heading, .process .content .sub-heading');
    const steps = gsap.utils.toArray('.process .process-steps .process-step');

    // Hide up front, before the user can ever see these sections.
    gsap.set([...headings, ...fades, ...steps], { opacity: 0, y: 24 });

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
            gsap.to(steps, { opacity: 1, y: 0, duration: 0.6, stagger: 0.12, ease: 'power2.out' });
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

    handlers.forEach((fn, el) => observer.observe(el));
});
