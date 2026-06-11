import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

// Bento cells are visible by default (set in CSS). With motion allowed they
// are hidden at load (the section sits below the fold) and staggered in once
// the grid first intersects. IntersectionObserver is used instead of
// ScrollTrigger so the cells can never be left stuck hidden.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const grid = document.querySelector('.why .why-grid');
    if (!grid) return;

    const cells = grid.querySelectorAll('.why-cell');
    if (!cells.length) return;

    gsap.set(cells, { opacity: 0, y: 28 });

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            // clearProps drops GSAP's inline transform once the entrance is
            // done, leaving the cells with clean computed styles.
            gsap.to(cells, {
                opacity: 1,
                y: 0,
                duration: 0.6,
                stagger: 0.07,
                ease: 'power2.out',
                clearProps: 'transform',
            });
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -4% 0px' });

    observer.observe(grid);
});
