import { prefersReducedMotion } from '../components/reduced-motion';

// Count the "By the numbers" stats up from zero when they scroll into view.
// Keeps any prefix/suffix (e.g. +, x, %, £) and decimal places intact. Under
// reduced motion the final value is shown immediately.
document.addEventListener('DOMContentLoaded', () => {
    const nums = document.querySelectorAll('.results .stat-number');
    if (!nums.length) return;

    const reduceMotion = prefersReducedMotion();

    const run = (el) => {
        const raw = el.textContent.trim();
        const match = raw.match(/^(\D*)(\d+(?:\.\d+)?)(.*)$/);
        if (!match) return;

        const [, prefix, numStr, suffix] = match;
        const target = parseFloat(numStr);
        const decimals = (numStr.split('.')[1] || '').length;

        if (reduceMotion || !target) {
            el.textContent = raw;
            return;
        }

        const duration = 1400;
        const start = performance.now();
        const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

        const tick = (now) => {
            const t = Math.min((now - start) / duration, 1);
            el.textContent = prefix + (target * easeOutCubic(t)).toFixed(decimals) + suffix;
            if (t < 1) {
                requestAnimationFrame(tick);
            } else {
                el.textContent = raw; // exact final value
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
    }, { threshold: 0.6 });

    nums.forEach((el) => observer.observe(el));
});
