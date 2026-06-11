import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

// The Why rows are visible by default (set in CSS). On scroll-in each row
// rises in and restarts its animated icon. IntersectionObserver is used
// instead of ScrollTrigger so the rows can never be left stuck hidden.
document.addEventListener('DOMContentLoaded', () => {
    const boxes = document.querySelectorAll('.why .why-boxes');
    if (!boxes.length) return;

    const reduceMotion = prefersReducedMotion();

    const showLoopingIcon = (box) => {
        const reveal = box.querySelector('.reveal');
        const infinite = box.querySelector('.infinite');
        if (reveal) reveal.style.display = 'none';
        if (infinite) infinite.style.display = 'block';
    };

    const playIcon = (box) => {
        const reveal = box.querySelector('.reveal');
        const infinite = box.querySelector('.infinite');
        if (!reveal) return;

        // Restart the one-shot reveal, then swap to the looping version.
        const revealSrc = reveal.src;
        reveal.src = '';
        reveal.src = revealSrc;

        setTimeout(() => {
            if (infinite) {
                const infiniteSrc = infinite.src;
                infinite.src = '';
                infinite.src = infiniteSrc;
            }
        }, 1200);

        setTimeout(() => showLoopingIcon(box), 1800);
    };

    if (reduceMotion || !('IntersectionObserver' in window)) {
        boxes.forEach((box) => {
            box.classList.add('is-in');
            showLoopingIcon(box);
        });
        return;
    }

    // Hidden at load (the section is below the fold) so the reveal never pops.
    gsap.set(boxes, { opacity: 0, y: 28 });

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const box = entry.target;
            box.classList.add('is-in');
            gsap.to(box, { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' });
            playIcon(box);
            obs.unobserve(box);
        });
    }, { threshold: 0.2, rootMargin: '0px 0px -4% 0px' });

    boxes.forEach((box) => observer.observe(box));
});
