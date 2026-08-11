import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';
import { revealFailsafe } from '../components/reveal-failsafe';

// Work index: hovering or focusing a case row mirrors its is-active class
// onto the matching stage image (decorative crossfade; all text is always
// visible, so this is state, not motion, and stays bound under reduced
// motion). The entrance stagger is hidden-at-load + IntersectionObserver,
// same as the other sections; nothing is hidden under reduced motion or
// without JS.
document.addEventListener('DOMContentLoaded', () => {
    const showcase = document.querySelector('.work .work-showcase');
    if (!showcase) return;

    const rows = gsap.utils.toArray(showcase.querySelectorAll('.case-row'));
    const stageImgs = gsap.utils.toArray(showcase.querySelectorAll('.case-stage .stage-img'));

    const setActive = (caseId) => {
        rows.forEach((row) => row.classList.toggle('is-active', row.dataset.case === caseId));
        stageImgs.forEach((img) => img.classList.toggle('is-active', img.dataset.case === caseId));
    };

    if (rows.length) {
        rows.forEach((row) => {
            row.addEventListener('mouseenter', () => setActive(row.dataset.case));
            row.addEventListener('focusin', () => setActive(row.dataset.case));
        });
    }

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const stage = showcase.querySelector('.case-stage');
    const targets = stage ? [...rows, stage] : rows;
    if (!targets.length) return;

    gsap.set(targets, { opacity: 0, y: 36 });
    revealFailsafe(targets, 4000);

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            gsap.to(targets, {
                opacity: 1,
                y: 0,
                duration: 0.7,
                stagger: 0.14,
                ease: 'power2.out',
                clearProps: 'transform',
            });
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -4% 0px' });

    observer.observe(showcase);
});
