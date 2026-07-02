import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

// The "what happens next" ember rail: on first view the rail draws down the
// list (scaleY 0 -> 1) while the steps stagger in, and each step's index heats
// grey -> red as the draw passes it (the process section's philosophy, sized
// for a short list — one IntersectionObserver-triggered timeline, no scrub).
// Under reduced motion or without JS the finished state shows instead: rail
// drawn, indices red (misc/_motion.scss and the html.js gate in _main.scss).
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const block = document.querySelector('.contact-main .contact-next');
    const rail = block && block.querySelector('.next-progress');
    const wrap = block && block.querySelector('.next-rail-wrap');
    const steps = gsap.utils.toArray('.contact-main .next-step');
    if (!block || !rail || !wrap || !steps.length) return;

    // Take over from the CSS pre-hide; animation:none cancels the 2.5s failsafe
    // whose `forwards` fill would otherwise outrank the timeline's inline styles.
    gsap.set(block, { opacity: 0, animation: 'none' });
    gsap.set(steps, { opacity: 0, y: 16 });
    gsap.set(rail, { scaleY: 0 });

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            obs.unobserve(entry.target);

            const tl = gsap.timeline();
            tl.to(block, { opacity: 1, duration: 0.3, ease: 'power1.out' }, 0)
                .to(rail, { scaleY: 1, duration: 0.9, ease: 'power2.inOut' }, 0.1)
                .to(steps, { opacity: 1, y: 0, duration: 0.5, stagger: 0.16, ease: 'power2.out' }, 0.15);

            // Heat each index as the draw passes it: the rail runs 0.1s-1.0s of
            // the timeline, so a step at fraction f of the wrap height heats at
            // 0.1 + 0.9f (the CSS colour transition supplies the glow-up).
            const wrapHeight = wrap.offsetHeight || 1;
            steps.forEach((step) => {
                const fraction = Math.min(1, (step.offsetTop + 24) / wrapHeight);
                tl.call(() => step.classList.add('is-passed'), null, 0.1 + 0.9 * fraction);
            });
        });
    }, { threshold: 0.35 });

    observer.observe(block);
});
