import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(SplitText);

// Generic entrance reveals for the landing blocks, driven by data-reveal hooks
// so they work for any block in any order and repeat. Hero targets are
// pre-hidden in CSS (html.js, 2.8s failsafe) and run on load; every other
// [data-reveal] is hidden here and revealed by IntersectionObserver. Under
// reduced motion / no-JS / no-IO nothing is ever hidden (misc/_motion.scss
// forces the finished state).
document.addEventListener('DOMContentLoaded', () => {
    const hero = document.querySelector('.lp-hero');

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
        gsap.set('.lp-hero [data-reveal]', { opacity: 1 });
        return;
    }

    // ---- Hero (above the fold: runs on load once fonts settle) ----
    if (hero) {
        const heroHeading = hero.querySelector('[data-reveal="heading"]');
        const heroFades = gsap.utils.toArray(hero.querySelectorAll('[data-reveal="fade"]'));
        const heroRule = hero.querySelector('[data-reveal="rule"]');
        // Visible but zero-width so it grows left-to-right (the CSS pre-hide
        // sets opacity 0; restore it here, the scaleX carries the reveal).
        if (heroRule) gsap.set(heroRule, { opacity: 1, scaleX: 0 });

        document.fonts.ready.then(() => {
            const tl = gsap.timeline({ defaults: { ease: 'expo.out' } });
            if (heroHeading) {
                SplitText.create(heroHeading, {
                    type: 'lines',
                    linesClass: 'line',
                    mask: 'lines',
                    autoSplit: false,
                    onSplit(self) {
                        gsap.set(heroHeading, { opacity: 1 });
                        // No revert: the split lines stay, so the heading keeps
                        // the exact line breaks it animated in with (no reflow).
                        tl.from(self.lines, {
                            yPercent: 110,
                            opacity: 0,
                            duration: 0.85,
                            stagger: 0.1,
                        }, 0.1);
                    },
                });
            }
            heroFades.forEach((el, i) => tl.to(el, { opacity: 1, duration: 0.6 }, 0.45 + i * 0.15));
            if (heroRule) tl.to(heroRule, { scaleX: 1, duration: 0.9, ease: 'power3.out' }, 0.8);
        });
    }

    // ---- Below the fold: every [data-reveal] outside the hero ----
    const inHero = (el) => hero && hero.contains(el);
    const targets = gsap.utils.toArray('[data-reveal]').filter((el) => !inHero(el));
    if (!targets.length) return;

    // Pre-hide by type.
    targets.forEach((el) => {
        const type = el.getAttribute('data-reveal');
        if ('stagger' === type) {
            gsap.set(Array.from(el.children), { opacity: 0, y: 22 });
        } else if ('rule' === type) {
            gsap.set(el, { scaleX: 0 });
        } else {
            gsap.set(el, { opacity: 0, y: 24 });
        }
    });

    const reveal = (el) => {
        const type = el.getAttribute('data-reveal');
        if ('heading' === type) {
            SplitText.create(el, {
                type: 'lines',
                linesClass: 'line',
                mask: 'lines',
                autoSplit: false,
                onSplit(self) {
                    gsap.set(el, { opacity: 1, y: 0 });
                    // No revert: keep the split so the line breaks don't reflow.
                    return gsap.from(self.lines, {
                        yPercent: 110,
                        opacity: 0,
                        duration: 0.7,
                        stagger: 0.09,
                        ease: 'expo.out',
                    });
                },
            });
        } else if ('stagger' === type) {
            gsap.to(Array.from(el.children), { opacity: 1, y: 0, duration: 0.55, stagger: 0.08, ease: 'power2.out' });
        } else if ('rule' === type) {
            gsap.to(el, { scaleX: 1, duration: 0.8, ease: 'power3.out' });
        } else {
            gsap.to(el, { opacity: 1, y: 0, duration: 0.55, ease: 'power2.out' });
        }
    };

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            reveal(entry.target);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -4% 0px' });

    document.fonts.ready.then(() => targets.forEach((el) => observer.observe(el)));
});
