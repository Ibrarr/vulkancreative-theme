import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';
import { revealFailsafe } from '../components/reveal-failsafe';

// The ledger prints itself: rows rise in sequence with their dotted leaders
// drawing, then the £0 total settles into place. The finished state is
// server-rendered; this only animates towards it.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const plate = document.querySelector('.fw-ledger .fw-ledger-plate');
    if (!plate) return;

    const rows = gsap.utils.toArray('.fw-ledger .ledger-row');
    const total = plate.querySelector('.ledger-total');
    const note = plate.querySelector('.ledger-note');
    if (!rows.length) return;

    gsap.set(rows, { opacity: 0, y: 18 });
    revealFailsafe(rows, 4000);
    if (total) gsap.set(total, { opacity: 0, scale: 1.04, transformOrigin: 'left bottom' });
    if (total) revealFailsafe(total, 4000);
    if (note) gsap.set(note, { opacity: 0 });
    if (note) revealFailsafe(note, 4000);

    const print = () => {
        const tl = gsap.timeline({ defaults: { ease: 'power2.out' } });
        tl.to(rows, { opacity: 1, y: 0, duration: 0.5, stagger: 0.11 }, 0);
        if (total) {
            tl.to(total, { opacity: 1, scale: 1, duration: 0.55, ease: 'expo.out' }, rows.length * 0.11 + 0.25);
        }
        if (note) {
            tl.to(note, { opacity: 1, duration: 0.5 }, rows.length * 0.11 + 0.5);
        }
    };

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            print();
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.2, rootMargin: '0px 0px -6% 0px' });

    observer.observe(plate);
});
