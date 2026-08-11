import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';
import { revealFailsafe } from '../components/reveal-failsafe';

// Staggered entrance for service card grids. Binds by the .services-grid
// class, so the same module serves the hub grid and the service pages'
// related strip (it ships in both bundles, the testimonials.js precedent).
// Nothing is pre-hidden without JS, so the cards can never be left invisible;
// reduced motion sees the finished grid immediately.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const grids = gsap.utils.toArray('.services-grid');
    if (!grids.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            gsap.to(entry.target.querySelectorAll('.service-card'), {
                opacity: 1,
                y: 0,
                duration: 0.6,
                stagger: 0.09,
                ease: 'power2.out',
                // Clear the transform once settled so nothing composites over
                // the cards' own hover layers.
                clearProps: 'transform',
            });
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -4% 0px' });

    grids.forEach((grid) => {
        const cards = grid.querySelectorAll('.service-card');
        if (!cards.length) return;
        gsap.set(cards, { opacity: 0, y: 24 });
        revealFailsafe(cards, 4000);
        observer.observe(grid);
    });
});
