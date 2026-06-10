import { prefersReducedMotion } from '../components/reduced-motion';

// The Why cards are visible by default (set in CSS). On scroll-in we add `.is-in`
// for a subtle slide-up and restart the animated icon. IntersectionObserver is used
// instead of ScrollTrigger so the cards can never be left stuck hidden.
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

    if (reduceMotion) {
        boxes.forEach((box) => {
            box.classList.add('is-in');
            showLoopingIcon(box);
        });
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const box = entry.target;
            box.classList.add('is-in');
            playIcon(box);
            obs.unobserve(box);
        });
    }, { threshold: 0.25, rootMargin: '0px 0px -8% 0px' });

    boxes.forEach((box) => observer.observe(box));
});
