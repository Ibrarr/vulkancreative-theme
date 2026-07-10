import { prefersReducedMotion } from '../components/reduced-motion';

// Point webpack's lazy-chunk loader at the theme's dist directory so the
// dynamically imported three.js chunk resolves under the theme URL, not the
// WordPress site root.
if (typeof window !== 'undefined' && window.__vc_public_path) {
    // eslint-disable-next-line no-undef, camelcase
    __webpack_public_path__ = window.__vc_public_path;
}

const BASE = `${window.location.origin}/wp-content/themes/vulkancreative-theme/assets`;
const MODEL_URL = `${BASE}/models/statue-marble.glb`;
const POSTER_URL = `${BASE}/images/hero/statue-desktop.webp`;
// Below lg the poster is a forward-facing transparent cut-out (face and hammer
// to camera) rather than the bowed-head scene render.
const POSTER_MOBILE_URL = `${BASE}/images/hero/statue-mobile.webp`;
const DESKTOP_MIN = 992;

// Show a lightweight poster if one exists, so there is an instant visual while
// the heavier interactive scene loads (and a fallback if it never does).
function addPoster(container) {
    const url = window.innerWidth < DESKTOP_MIN ? POSTER_MOBILE_URL : POSTER_URL;
    const probe = new Image();
    probe.onload = () => {
        if (container.querySelector('.hero-poster')) return;
        const img = document.createElement('img');
        img.src = url;
        img.alt = '';
        img.className = 'hero-poster';
        img.setAttribute('aria-hidden', 'true');
        container.prepend(img);
    };
    probe.src = url;
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
                addPoster(container);
            });
        })
        .catch((err) => {
            // Runtime failed to load (missing chunk, blocked WebGL, etc.): fall
            // back to the static poster, but surface why so a stale deploy does
            // not fail silently.
            console.warn('[vc] statue hero failed to load; showing poster fallback.', err);
            addPoster(container);
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.hero .graphic');
    if (!container) return;

    // Reduced motion or smaller screens never load the 3D scene, so they get the
    // static poster. Desktop gets no placeholder at all: the scene fades in over
    // the molten glow once it has rendered.
    if (prefersReducedMotion() || window.innerWidth < DESKTOP_MIN) {
        addPoster(container);
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
