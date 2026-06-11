import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(ScrollTrigger);

// The cropped VULKAN wordmark rises out of the footer's base as the end of
// the page scrolls into view. Static under reduced motion.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion()) return;

    const mark = document.querySelector('.footer-wordmark');
    if (!mark) return;

    gsap.fromTo(mark,
        { yPercent: 40 },
        {
            yPercent: 0,
            ease: 'none',
            scrollTrigger: {
                trigger: '#footer',
                start: 'top 90%',
                end: 'bottom bottom',
                scrub: true,
            },
        }
    );
});
