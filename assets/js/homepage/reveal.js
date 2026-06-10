import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(ScrollTrigger);

// Subtle entrance fade for the headings of sections that don't use SplitText
// (results, process, testimonials). The data grids (stats, steps, testimonials)
// stay static so they can never be left hidden if a trigger misfires. Mirrors the
// proven pattern used by the other homepage modules. Skipped under reduced motion.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion()) return;

    gsap.utils.toArray('.results .content, .process .content, .testimonials .content').forEach((el) => {
        gsap.from(el, {
            opacity: 0,
            y: 24,
            duration: 0.6,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: el,
                start: 'top 95%',
                toggleActions: 'play none none none',
            },
        });
    });

    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(() => ScrollTrigger.refresh());
    }
});
