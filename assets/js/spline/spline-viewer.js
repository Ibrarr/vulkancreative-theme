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
            splineEl.setAttribute('url', SCENE_URL);
            splineEl.className = 'hero-spline';

            // Crossfade: the scene fades in over the poster once it has
            // genuinely rendered, so there is never a hard pop or a
            // misaligned double statue.
            let revealed = false;
            const reveal = () => {
                if (revealed) return;
                revealed = true;
                container.classList.add('spline-ready');
            };

            splineEl.addEventListener('load-complete', reveal);
            splineEl.addEventListener('load', reveal);

            // Fallback: watch for the rendered canvas, then allow a few
            // frames before the swap.
            const poll = setInterval(() => {
                if (splineEl.shadowRoot && splineEl.shadowRoot.querySelector('canvas')) {
                    clearInterval(poll);
                    setTimeout(reveal, 500);
                }
            }, 250);
            setTimeout(() => clearInterval(poll), 30000);

            container.appendChild(splineEl);
        })
        .catch(() => {
            // Scene failed to load: fall back to the static poster.
            addPoster(container);
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.hero .graphic');
    if (!container) return;

    // Reduced motion or smaller screens never load the 3D scene, so they get
    // the static poster. Desktop gets no placeholder at all: the scene fades
    // in over the molten glow once it has rendered.
    if (prefersReducedMotion() || window.innerWidth < DESKTOP_MIN) {
        addPoster(container);
        return;
    }

    // Hold the heavy scene boot until the hero intro has settled. On a warm
    // reload everything is cached, so an immediate idle callback would land
    // the ~400ms engine compile in the middle of the headline animation and
    // visibly freeze it.
    const INTRO_SETTLE_MS = 2400;

    const start = () => {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => loadSpline(container), { timeout: 2000 });
        } else {
            setTimeout(() => loadSpline(container), 600);
        }
    };

    const afterIntro = () => setTimeout(start, INTRO_SETTLE_MS);

    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(afterIntro);
    } else {
        afterIntro();
    }
});
