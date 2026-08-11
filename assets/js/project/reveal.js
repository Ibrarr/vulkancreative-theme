import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';
import { revealFailsafe } from '../components/reveal-failsafe';

gsap.registerPlugin(SplitText);

// Section entrances for the work pages (the /work/ archive and the single
// project showcases), mirroring the services reveal: with motion allowed,
// targets are hidden at load and revealed by IntersectionObserver as they
// enter the viewport: headings rise with a SplitText line mask, the rest
// fade up, grids and galleries cascade. Under reduced motion, without JS, or
// without IntersectionObserver nothing is ever hidden (misc/_motion.scss is
// the CSS safety net). The related-work wheel owns its own entrance
// (homepage/our-work.js).
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const headings = gsap.utils.toArray([
        '.work-hero h1',
        '.project-hero h1',
        '.project-overview .content h2',
        '.project-cta .content h2',
    ].join(', '));

    const fades = gsap.utils.toArray([
        '.work-hero .sub-heading',
        '.work-index .work-filter',
        '.project-hero .hero-meta',
        '.project-hero .hero-actions',
        '.project-hero .hero-media',
        '.project-overview .overview-lead',
        '.project-overview .fact-ledger',
        '.project-overview .deliverables',
        '.project-case-study-band .band-inner',
        '.project-cta .content .sub-heading',
        '.project-cta .cta-actions',
    ].join(', '));

    // Staggered groups: the observer watches the container, the items cascade in.
    const groups = [
        { container: '.work-index [data-work-grid]', items: '.work-card' },
        { container: '.project-gallery .gallery-grid', items: '.gallery-item' },
    ]
        .map((group) => ({ el: document.querySelector(group.container), items: group.items }))
        .filter((group) => group.el);

    if (!headings.length && !fades.length && !groups.length) return;

    // animation:none cancels the CSS 2.5s failsafe once JS owns the reveal:
    // its `forwards` fill outranks inline styles, so left alive it would force
    // pre-hidden targets visible early and swallow their entrance.
    gsap.set([...headings, ...fades], { opacity: 0, y: 24, animation: 'none' });
    revealFailsafe([...headings, ...fades], 4000);
    groups.forEach((group) => {
        gsap.set(group.el.querySelectorAll(group.items), { opacity: 0, y: 16, animation: 'none' });
        revealFailsafe(group.el.querySelectorAll(group.items), 4000);
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
                    // Display headings run line-height 1, so the overflow mask
                    // clips descenders; restore the intact heading once done.
                    onComplete: () => self.revert(),
                });
            },
        });
    };

    const showFade = (el) => {
        gsap.to(el, { opacity: 1, y: 0, duration: 0.55, ease: 'power2.out' });
    };

    const showGroup = (group) => {
        gsap.to(group.el.querySelectorAll(group.items), {
            opacity: 1,
            y: 0,
            duration: 0.6,
            stagger: 0.1,
            ease: 'power2.out',
            clearProps: 'transform',
        });
    };

    const handlers = new Map();
    headings.forEach((el) => handlers.set(el, showHeading));
    fades.forEach((el) => handlers.set(el, showFade));

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const fn = handlers.get(entry.target);
            if (fn) fn(entry.target);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -4% 0px' });

    // The staggered grids get their own observer at threshold 0. A percentage
    // threshold never fires for a container taller than the viewport: the work
    // grid runs ~2400px, so 12% is ~290px, but on desktop its top can sit just
    // below the fold on load and the grid stays hidden until a ~1000px scroll.
    // Threshold 0 with a small bottom margin reveals the cascade as soon as the
    // grid's top edge enters, and fires immediately for a grid already in view.
    const groupByEl = new Map();
    groups.forEach((group) => groupByEl.set(group.el, group));
    const groupObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const group = groupByEl.get(entry.target);
            if (group) showGroup(group);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0, rootMargin: '0px 0px -10% 0px' });

    // Observation starts once fonts are active: SplitText must measure the
    // settled metrics (Archivo's width stretch changes line breaks), or a
    // heading can animate with fallback-font wrapping and rewrap when the
    // reveal reverts. fonts.ready resolves immediately on a warm cache.
    document.fonts.ready.then(() => {
        handlers.forEach((fn, el) => observer.observe(el));
        groupByEl.forEach((group, el) => groupObserver.observe(el));
    });
});
