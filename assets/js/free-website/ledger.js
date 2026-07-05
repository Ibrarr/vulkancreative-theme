import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

// The ledger prints itself: rows rise in sequence with their dotted leaders
// drawing, then the £0 total settles into place. The finished state is
// server-rendered; this only animates towards it.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const plate = document.querySelector('.fw-ledger .fw-ledger-plate');
    if (!plate) return;

    const rows = gsap.utils.toArray('.fw-ledger .ledger-row');
    const leaders = gsap.utils.toArray('.fw-ledger .ledger-leader');
    const total = plate.querySelector('.ledger-total');
    const note = plate.querySelector('.ledger-note');
    if (!rows.length) return;

    gsap.set(rows, { opacity: 0, y: 18 });
    gsap.set(leaders, { scaleX: 0, transformOrigin: 'left center' });
    if (total) gsap.set(total, { opacity: 0, scale: 1.04, transformOrigin: 'left bottom' });
    if (note) gsap.set(note, { opacity: 0 });

    const print = () => {
        const tl = gsap.timeline({ defaults: { ease: 'power2.out' } });
        tl.to(rows, { opacity: 1, y: 0, duration: 0.5, stagger: 0.11 }, 0);
        tl.to(leaders, { scaleX: 1, duration: 0.45, stagger: 0.11, ease: 'power3.out' }, 0.15);
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
