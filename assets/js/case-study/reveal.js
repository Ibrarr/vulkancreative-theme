import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(SplitText);

// Section entrances for the case study pages (the /case-studies/ archive and
// the single narrative pages), mirroring the work-pages reveal: with motion
// allowed, targets are hidden at load and revealed by IntersectionObserver as
// they enter the viewport: headings rise with a SplitText line mask, the rest
// fade up, grids and galleries cascade. Under reduced motion, without JS, or
// without IntersectionObserver nothing is ever hidden (misc/_motion.scss is
// the CSS safety net). The results count-up owns its own entrance
// (homepage/counter.js).
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const headings = gsap.utils.toArray([
        '.case-studies-hero h1',
        '.cs-hero h1',
        '.cs-overview .content h2',
        '.cs-challenge .content h2',
        '.cs-approach .content h2',
        '.cs-results .content h2',
        '.cs-testimonial .content h2',
        '.cs-related .content h2',
        '.cs-cta .content h2',
    ].join(', '));

    const fades = gsap.utils.toArray([
        '.case-studies-hero .sub-heading',
        '.cs-index .work-filter',
        '.cs-hero .sub-heading',
        '.cs-hero .hero-meta',
        '.cs-hero .hero-metric',
        '.cs-hero .hero-media',
        '.cs-overview .overview-lead',
        '.cs-overview .fact-ledger',
        '.cs-challenge .statement',
        '.cs-challenge .narrative-body',
        '.cs-approach .narrative-body',
        '.cs-approach .deliverables',
        '.cs-results .results-narrative',
        '.cs-results .results-actions',
        '.cs-testimonial .testimonial-spotlight',
        '.cs-related .related-project',
        '.cs-cta .content .sub-heading',
        '.cs-cta .cta-actions',
    ].join(', '));

    // Staggered groups: the observer watches the container, the items cascade in.
    const groups = [
        { container: '.cs-index [data-work-grid]', items: '.cs-card-item' },
        { container: '.cs-approach .gallery-grid', items: '.gallery-item' },
        { container: '.cs-results .stats-grid', items: '.stat' },
        { container: '.cs-related .related-services-row', items: '.service-card' },
    ]
        .map((group) => ({ el: document.querySelector(group.container), items: group.items }))
        .filter((group) => group.el);

    if (!headings.length && !fades.length && !groups.length) return;

    // animation:none cancels the CSS 2.5s failsafe once JS owns the reveal:
    // its `forwards` fill outranks inline styles, so left alive it would force
    // pre-hidden targets visible early and swallow their entrance.
    gsap.set([...headings, ...fades], { opacity: 0, y: 24, animation: 'none' });
    groups.forEach((group) => {
        gsap.set(group.el.querySelectorAll(group.items), { opacity: 0, y: 16, animation: 'none' });
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
    groups.forEach((group) => handlers.set(group.el, () => showGroup(group)));

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const fn = handlers.get(entry.target);
            if (fn) fn(entry.target);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -4% 0px' });

    // Observation starts once fonts are active: SplitText must measure the
    // settled metrics (Archivo's width stretch changes line breaks), or a
    // heading can animate with fallback-font wrapping and rewrap when the
    // reveal reverts. fonts.ready resolves immediately on a warm cache.
    document.fonts.ready.then(() => {
        handlers.forEach((fn, el) => observer.observe(el));
    });
});
