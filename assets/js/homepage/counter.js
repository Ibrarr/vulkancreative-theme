import { prefersReducedMotion } from '../components/reduced-motion';

// Count the "By the numbers" stats up from zero when they scroll into view.
// The real values are server-rendered (SEO, no-JS, reduced motion); with
// motion allowed they are zeroed at load, before the section can be seen,
// so the final value never flashes ahead of the count-up. Prefix/suffix
// (e.g. +, x, %, £) and decimal places are kept intact.
document.addEventListener('DOMContentLoaded', () => {
    const nums = document.querySelectorAll('.results .stat-number');
    if (!nums.length) return;

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const parse = (raw) => {
        const match = raw.match(/^(\D*)(\d+(?:\.\d+)?)(.*)$/);
        if (!match) return null;
        const [, prefix, numStr, suffix] = match;
        return {
            raw,
            prefix,
            suffix,
            target: parseFloat(numStr),
            decimals: (numStr.split('.')[1] || '').length,
        };
    };

    const stats = new Map();

    // Zero everything up front (the section is below the fold at load)
    nums.forEach((el) => {
        const stat = parse(el.textContent.trim());
        if (!stat || !stat.target) return;
        stats.set(el, stat);
        el.textContent = stat.prefix + (0).toFixed(stat.decimals) + stat.suffix;
    });

    const run = (el) => {
        const stat = stats.get(el);
        if (!stat) return;

        const duration = 1400;
        const start = performance.now();
        const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

        const tick = (now) => {
            const t = Math.min((now - start) / duration, 1);
            el.textContent = stat.prefix + (stat.target * easeOutCubic(t)).toFixed(stat.decimals) + stat.suffix;
            if (t < 1) {
                requestAnimationFrame(tick);
            } else {
                el.textContent = stat.raw; // exact final value
            }
        };

        requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            run(entry.target);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.35 });

    stats.forEach((stat, el) => observer.observe(el));
});
