import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(SplitText);

// Insights reveal (blog index, category/author archives, search, single post).
// Same approach as the homepage: with motion allowed, targets are hidden at
// load and revealed by IntersectionObserver as they enter the viewport —
// titles with a SplitText line-mask rise, everything else with a gentle fade,
// card grids in a stagger. Under reduced motion, without JS or without
// IntersectionObserver nothing is ever hidden. Selectors that aren't present
// on a given page are simply no-ops.
//
// The observer fires at threshold 0 (any part entering): article bodies and
// card grids are far taller than the viewport, so a percentage threshold could
// sit permanently unmet while the element already fills the screen.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const headings = gsap.utils.toArray('.insights-title, .insight-hero-title, .insight-related-heading, .insight-faqs h2');
    const fades = gsap.utils.toArray(
        '.insights-header .breadcrumbs, .insights-standfirst, ' +
        '.insights-filter, .insights-header--author .author-bio, ' +
        '.insight-hero .breadcrumbs, .insight-hero-excerpt, .insight-hero-meta, ' +
        '.insight-content, .insight-sidebar, .pagination'
    );
    const grids = gsap.utils.toArray('.insights-grid .row, .insight-related .row');

    if (!headings.length && !fades.length && !grids.length) return;

    // Hide up front, after the guards — so no-JS / reduced-motion keep content.
    gsap.set([...headings, ...fades], { opacity: 0, y: 24 });
    grids.forEach((grid) => gsap.set(grid.querySelectorAll('.insight-card'), { opacity: 0, y: 24 }));

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

    const showGrid = (grid) => {
        gsap.to(grid.querySelectorAll('.insight-card'), {
            opacity: 1,
            y: 0,
            duration: 0.6,
            stagger: 0.08,
            ease: 'power2.out',
        });
    };

    const handlers = new Map();
    headings.forEach((el) => handlers.set(el, showHeading));
    fades.forEach((el) => handlers.set(el, showFade));
    grids.forEach((el) => handlers.set(el, showGrid));

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const fn = handlers.get(entry.target);
            if (fn) fn(entry.target);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0, rootMargin: '0px 0px 0px 0px' });

    handlers.forEach((fn, el) => observer.observe(el));
});
