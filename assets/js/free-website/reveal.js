import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(SplitText);

// Free-website page entrances. The hero targets are pre-hidden in CSS (html.js
// scope, 2.5s failsafe); everything below the fold is hidden here at
// DOMContentLoaded and revealed by IntersectionObserver. Under reduced motion,
// without JS, or without IntersectionObserver nothing is ever hidden
// (misc/_motion.scss forces the finished state).
document.addEventListener('DOMContentLoaded', () => {
    const hero = document.querySelector('.fw-hero');
    if (!hero) return;

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
        // CSS pre-hides the hero under html.js; hand it straight back.
        gsap.set(['.fw-hero h1', '.fw-hero .sub-heading', '.fw-hero .hero-actions', '.fw-hero .hero-note'], { opacity: 1 });
        return;
    }

    // ---- Hero (above the fold: runs on load, once fonts have settled) ----
    const heroH1 = hero.querySelector('h1');
    const heroSub = hero.querySelector('.sub-heading');
    const heroActions = hero.querySelector('.hero-actions');
    const heroNote = hero.querySelector('.hero-note');
    const heroRule = hero.querySelector('.hero-rule');

    if (heroRule) gsap.set(heroRule, { scaleX: 0 });

    document.fonts.ready.then(() => {
        const tl = gsap.timeline({ defaults: { ease: 'expo.out' } });

        if (heroH1) {
            SplitText.create(heroH1, {
                type: 'lines',
                linesClass: 'line',
                mask: 'lines',
                autoSplit: false,
                onSplit(self) {
                    gsap.set(heroH1, { opacity: 1 });
                    tl.from(self.lines, {
                        yPercent: 110,
                        opacity: 0,
                        duration: 0.85,
                        stagger: 0.1,
                        onComplete: () => self.revert(),
                    }, 0.1);
                },
            });
        }

        if (heroSub) tl.to(heroSub, { opacity: 1, duration: 0.6 }, 0.45);
        if (heroActions) tl.to(heroActions, { opacity: 1, duration: 0.6 }, 0.6);
        if (heroNote) tl.to(heroNote, { opacity: 1, duration: 0.6 }, 0.75);
        if (heroRule) tl.to(heroRule, { scaleX: 1, duration: 0.9, ease: 'power3.out' }, 0.8);
    });

    // ---- Below the fold ----
    const headings = gsap.utils.toArray('.fw-how .content h2, .fw-ledger .content h2, .fw-contrast .content h2, .fw-proof .content h2, .fw-faq .content h2, .fw-enquire .content h2');
    const fades = gsap.utils.toArray('.fw-how .fw-how-intro .sub-heading, .fw-ledger .fw-ledger-head .sub-heading, .fw-contrast .fw-contrast-intro .sub-heading, .fw-enquire .fw-enquire-intro .sub-heading, .fw-enquire .enquire-note');
    const steps = gsap.utils.toArray('.fw-how .how-step');
    const contrastRows = gsap.utils.toArray('.fw-contrast .contrast-grid .contrast-row');
    const faqItems = gsap.utils.toArray('.fw-faq .fw-faq-item');
    const formPanel = document.querySelector('.fw-enquire .form-container');
    const howRail = document.querySelector('.fw-how .fw-how-rail');

    gsap.set([...headings, ...fades, ...steps, ...faqItems], { opacity: 0, y: 24 });
    if (formPanel) gsap.set(formPanel, { opacity: 0, y: 28 });
    if (howRail) gsap.set(howRail, { scaleX: 0 });
    contrastRows.forEach((row) => {
        const usual = row.querySelector('.cr-usual');
        const cell = row.querySelector('.cr-this');
        if (usual) gsap.set(usual, { opacity: 0, x: -16 });
        if (cell) gsap.set(cell, { opacity: 0, x: 16 });
    });

    const showHeading = (el) => {
        SplitText.create(el, {
            type: 'lines',
            linesClass: 'line',
            mask: 'lines',
            autoSplit: false,
            onSplit(self) {
                gsap.set(el, { opacity: 1, y: 0 });
                return gsap.from(self.lines, {
                    yPercent: 110,
                    opacity: 0,
                    duration: 0.7,
                    stagger: 0.09,
                    ease: 'expo.out',
                    onComplete: () => self.revert(),
                });
            },
        });
    };

    const showFade = (el) => {
        gsap.to(el, { opacity: 1, y: 0, duration: 0.55, ease: 'power2.out' });
    };

    const handlers = new Map();
    headings.forEach((el) => handlers.set(el, showHeading));
    fades.forEach((el) => handlers.set(el, showFade));

    // Steps stagger in together and the rail draws underneath them.
    const stepsGrid = document.querySelector('.fw-how .fw-how-steps');
    if (stepsGrid && steps.length) {
        handlers.set(stepsGrid, () => {
            gsap.to(steps, { opacity: 1, y: 0, duration: 0.6, stagger: 0.14, ease: 'power2.out' });
            if (howRail) gsap.to(howRail, { scaleX: 1, duration: 1.1, ease: 'power3.out', delay: 0.1 });
        });
    }

    // Contrast rows slide in from opposite sides, row by row.
    const contrastGrid = document.querySelector('.fw-contrast .contrast-grid');
    if (contrastGrid && contrastRows.length) {
        handlers.set(contrastGrid, () => {
            contrastRows.forEach((row, i) => {
                const usual = row.querySelector('.cr-usual');
                const cell = row.querySelector('.cr-this');
                if (usual) gsap.to(usual, { opacity: 1, x: 0, duration: 0.55, ease: 'power2.out', delay: i * 0.08 });
                if (cell) gsap.to(cell, { opacity: 1, x: 0, duration: 0.55, ease: 'power2.out', delay: i * 0.08 + 0.05 });
            });
        });
    }

    // FAQ items rise in sequence.
    const faqList = document.querySelector('.fw-faq .fw-faq-list');
    if (faqList && faqItems.length) {
        handlers.set(faqList, () => {
            gsap.to(faqItems, { opacity: 1, y: 0, duration: 0.5, stagger: 0.07, ease: 'power2.out' });
        });
    }

    if (formPanel) {
        handlers.set(formPanel, () => {
            gsap.to(formPanel, { opacity: 1, y: 0, duration: 0.7, ease: 'expo.out' });
        });
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const fn = handlers.get(entry.target);
            if (fn) fn(entry.target);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -4% 0px' });

    document.fonts.ready.then(() => {
        handlers.forEach((fn, el) => observer.observe(el));
    });
});
