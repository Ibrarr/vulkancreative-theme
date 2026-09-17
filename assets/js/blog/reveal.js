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

    // Below lg the hero text paints with the first frame through the CSS
    // entrance in misc/_motion.scss (it is the page's LCP element), so this
    // module only owns the hero at lg+.
    const heroOnJs = window.matchMedia('(min-width: 992px)').matches;

    // Titles at lg+ only (below lg the CSS first-paint entrance owns them).
    const headings = gsap.utils.toArray(`${heroOnJs ? '.insights-title, .insight-hero-title, ' : ''}.insight-related-heading, .insight-faqs h2`);
    // Nothing else is hidden. The article body, sidebar, filter, breadcrumbs
    // and pagination are content people came to read or use, and hiding the
    // article until a script ran also held back the page's largest paint.
    const fades = [];
    const grids = gsap.utils.toArray('.insights-grid .row, .insight-related .row');

    if (!headings.length && !fades.length && !grids.length) return;

    // Hide up front, after the guards — so no-JS / reduced-motion keep content.
    gsap.set([...headings, ...fades], { opacity: 0, y: 24, animation: 'none' });
    grids.forEach((grid) => gsap.set(grid.querySelectorAll('.insight-card'), { opacity: 0, y: 24 }));

    // Failsafe: nothing on the insights family stays hidden if a reveal never
    // fires (a stalled script, an observer that misses). Already-revealed
    // targets are untouched.
    setTimeout(() => {
        gsap.to([...headings, ...fades], { opacity: 1, y: 0, duration: 0.4, ease: 'power2.out', overwrite: false });
        grids.forEach((grid) => gsap.to(grid.querySelectorAll('.insight-card'), { opacity: 1, y: 0, duration: 0.4, ease: 'power2.out', overwrite: false }));
    }, 4000);

    const showHeading = (el) => {
        SplitText.create(el, {
            type: 'lines',
            linesClass: 'line',
            mask: 'lines',
            aria: 'none',
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

    // Observation starts once fonts are active: SplitText must measure the
    // settled metrics (Archivo's width stretch changes line breaks), or a
    // heading can animate with fallback-font wrapping and rewrap when the
    // reveal reverts. fonts.ready resolves immediately on a warm cache.
    document.fonts.ready.then(() => {
        handlers.forEach((fn, el) => observer.observe(el));
    });
});
