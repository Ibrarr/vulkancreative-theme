import { prefersReducedMotion } from '../components/reduced-motion';

// Point webpack's lazy-chunk loader at the theme's dist directory so the
// dynamically imported three.js chunk resolves under the theme URL, not the
// WordPress site root.
if (typeof window !== 'undefined' && window.__vc_public_path) {
    // eslint-disable-next-line no-undef, camelcase
    __webpack_public_path__ = window.__vc_public_path;
}

// Resolve the theme's assets URL from the same theme URI PHP hands the webpack chunks
// (window.__vc_public_path = "<theme-url>/dist/"), so the model + posters load on ANY host
// (staging, a subfolder, a CDN, http/https) — not only when the site sits at the domain root.
const BASE = (window.__vc_public_path || `${window.location.origin}/wp-content/themes/vulkancreative-theme/dist/`).replace(/dist\/?$/, 'assets');
// Versioned filename: the model URL is hand-built (no mix manifest), so a new
// revision must ship under a new name or long-lived caches serve the old one.
const MODEL_URL = `${BASE}/models/statue-marble-2.glb`;
const POSTER_URL = `${BASE}/images/hero/statue-desktop.webp`;
const DESKTOP_MIN = 992;

// The poster <img> is server-rendered (front-page.php). Below lg its <picture>
// source already shows the mobile cut-out, preloaded from <head>; at lg+ it
// carries a transparent pixel, because the scene fades in over the glow. This
// only hands it the desktop still for the two cases with no scene: reduced
// motion, and a scene that failed to load.
function showDesktopPoster(container) {
    if (window.innerWidth < DESKTOP_MIN) return;
    const img = container.querySelector('.hero-poster');
    if (img) img.src = POSTER_URL;
}

// Code-split: three.js and its WebGL runtime only download on capable desktop
// viewports, after the hero text has painted. Keeps the main bundle small and the
// 7MB model off the critical path.
function loadHero(container) {
    const canvas = document.createElement('canvas');
    canvas.className = 'hero-spline';
    canvas.setAttribute('aria-hidden', 'true');

    import('./statue-scene')
        .then(({ buildScene }) => {
            container.appendChild(canvas);
            buildScene(container, canvas, MODEL_URL, () => {
                canvas.remove();
                showDesktopPoster(container);
            });
        })
        .catch(() => {
            // Runtime failed to load (missing chunk, blocked WebGL, etc.): fall
            // back to the static poster. Deliberately quiet: a console warning
            // here counted as a page error in audits for any visitor without
            // WebGL, and the poster is a complete experience.
            showDesktopPoster(container);
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.hero .graphic');
    if (!container) return;

    // Reduced motion or smaller screens never load the 3D scene, so they get the
    // static poster. Desktop gets no placeholder at all: the scene fades in over
    // the molten glow once it has rendered.
    if (prefersReducedMotion() || window.innerWidth < DESKTOP_MIN) {
        showDesktopPoster(container);
        return;
    }

    // Hold the heavy scene boot until the hero intro has settled. On a warm reload
    // everything is cached, so an immediate idle callback would land the engine
    // compile in the middle of the headline animation and visibly freeze it.
    const INTRO_SETTLE_MS = 2400;

    const start = () => {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => loadHero(container), { timeout: 2000 });
        } else {
            setTimeout(() => loadHero(container), 600);
        }
    };

    const afterIntro = () => setTimeout(start, INTRO_SETTLE_MS);

    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(afterIntro);
    } else {
        afterIntro();
    }
});
