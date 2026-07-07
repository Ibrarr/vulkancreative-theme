import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

// Checklist entrance: rows rise in sequence, then the total settles from a
// slight scale, then the note fades. One instance per checklist block. Under
// reduced motion / no-IO the server-rendered finished state stands.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    gsap.utils.toArray('.lp-checklist').forEach((section) => {
        const plate = section.querySelector('.lp-checklist-plate');
        if (!plate) return;

        const rows = gsap.utils.toArray(section.querySelectorAll('.checklist-row'));
        const total = section.querySelector('.checklist-total');
        const note = section.querySelector('.checklist-note');
        if (!rows.length) return;

        gsap.set(rows, { opacity: 0, y: 18 });
        if (total) gsap.set(total, { opacity: 0, scale: 1.04, transformOrigin: 'left bottom' });
        if (note) gsap.set(note, { opacity: 0 });

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const tl = gsap.timeline();
                tl.to(rows, { opacity: 1, y: 0, duration: 0.5, stagger: 0.11, ease: 'power2.out' });
                if (total) tl.to(total, { opacity: 1, scale: 1, duration: 0.55, ease: 'expo.out' }, rows.length * 0.11 + 0.15);
                if (note) tl.to(note, { opacity: 1, duration: 0.5 }, rows.length * 0.11 + 0.35);
                obs.unobserve(entry.target);
            });
        }, { threshold: 0.2, rootMargin: '0px 0px -6% 0px' });

        observer.observe(plate);
    });
});
