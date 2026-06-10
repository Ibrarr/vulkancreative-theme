import { prefersReducedMotion } from '../components/reduced-motion';

// Point webpack's lazy-chunk loader at the theme's dist directory so dynamically
// imported chunks resolve under the theme URL, not the WordPress site root.
if (typeof window !== 'undefined' && window.__vc_public_path) {
    // eslint-disable-next-line no-undef, camelcase
    __webpack_public_path__ = window.__vc_public_path;
}

const BASE = `${window.location.origin}/wp-content/themes/vulkancreative-theme/assets`;
const SCENE_URL = `${BASE}/spline/scene.splinecode`;
const POSTER_URL = `${BASE}/images/hero/statue-poster.webp`;
const DESKTOP_MIN = 992;

// Show a lightweight poster if one exists, so there is an instant visual while
// the heavier interactive scene loads (and a fallback if it never does).
function addPoster(container) {
    const probe = new Image();
    probe.onload = () => {
        if (container.querySelector('.hero-poster')) return;
        const img = document.createElement('img');
        img.src = POSTER_URL;
        img.alt = '';
        img.className = 'hero-poster';
        img.setAttribute('aria-hidden', 'true');
        container.prepend(img);
    };
    probe.src = POSTER_URL;
}

// Code-split: the viewer and its WebGL runtime only download on capable desktop
// viewports, after the hero text has painted. Keeps the main bundle small and the
// 15MB scene off the critical path.
function loadSpline(container) {
    import('@splinetool/viewer')
        .then(() => {
            const splineEl = document.createElement('spline-viewer');
            splineEl.setAttribute('loading-anim-type', 'spinner-big-dark');
            splineEl.setAttribute('url', SCENE_URL);
            splineEl.className = 'hero-spline';
            container.appendChild(splineEl);
        })
        .catch(() => {
            /* On failure, the poster (if any) remains. */
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.hero .graphic');
    if (!container) return;

    addPoster(container);

    // Reduced motion or smaller screens: keep the static poster only.
    if (prefersReducedMotion() || window.innerWidth < DESKTOP_MIN) return;

    const start = () => {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => loadSpline(container), { timeout: 2000 });
        } else {
            setTimeout(() => loadSpline(container), 600);
        }
    };

    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(start);
    } else {
        start();
    }
});
