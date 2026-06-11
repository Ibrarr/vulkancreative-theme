import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

// Case-study gallery panels: hidden at load (the section is below the fold)
// and staggered in by IntersectionObserver as the gallery enters, so there is
// never a visible snap-to-hidden. The expand-on-hover/focus interaction is
// pure CSS. Nothing is hidden under reduced motion or without JS.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const gallery = document.querySelector('.work .work-gallery');
    if (!gallery) return;

    const panels = gallery.querySelectorAll('.case-panel');
    if (!panels.length) return;

    gsap.set(panels, { opacity: 0, y: 36 });

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            gsap.to(panels, { opacity: 1, y: 0, duration: 0.7, stagger: 0.14, ease: 'power2.out' });
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -4% 0px' });

    observer.observe(gallery);
});
