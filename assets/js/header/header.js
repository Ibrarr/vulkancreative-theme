document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById("header");

    // On the Your Business landing page, keep header static with no scroll behaviour
    if (document.body.classList.contains('page-template-page-your-business')) {
        return;
    }

    const SURFACE_AT = 24;  // px scrolled before the bar gains a surface
    const HIDE_AFTER = 160; // px scrolled before scroll-down hides the bar

    let prevScrollPos = window.pageYOffset;

    // Hide-on-scroll-down only arms after a genuine interaction, so deep
    // links and other programmatic scrolls (which also fire scroll events)
    // never hide the bar on arrival.
    let userHasInteracted = false;
    ['wheel', 'touchstart', 'keydown', 'pointerdown'].forEach((type) => {
        window.addEventListener(type, () => {
            userHasInteracted = true;
        }, { once: true, passive: true });
    });

    const updateHeader = (isScrollEvent) => {
        const currentScrollPos = window.pageYOffset;

        header.classList.toggle('header--scrolled', currentScrollPos > SURFACE_AT);

        if (isScrollEvent && userHasInteracted) {
            if (currentScrollPos < prevScrollPos || currentScrollPos <= HIDE_AFTER) {
                header.classList.remove('header--hidden');
            } else if (currentScrollPos > prevScrollPos) {
                header.classList.add('header--hidden');
            }
        } else if (!isScrollEvent) {
            header.classList.remove('header--hidden');
        }

        prevScrollPos = currentScrollPos;
    };

    // Throttle to animation frames: scroll events can fire several times per
    // frame, and each run touches classes and a CSS variable.
    let ticking = false;
    const onScroll = () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(() => {
                ticking = false;
                updateHeader(true);
            });
        }
    };

    updateHeader(false);
    window.addEventListener("scroll", onScroll, { passive: true });
});

import { DrawSVGPlugin } from 'gsap/DrawSVGPlugin';
import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';
gsap.registerPlugin(DrawSVGPlugin);

document.addEventListener('DOMContentLoaded', () => {
    const logoPaths = "header .logo svg > path:nth-of-type(n+7):nth-of-type(-n+16)";

    // Reduced motion: show the finished (filled) logo with no draw-in animation.
    if (prefersReducedMotion()) {
        gsap.set(logoPaths, { visibility: "visible", fillOpacity: 1, strokeOpacity: 0 });
        return;
    }

    gsap.set(
        logoPaths,
        {
            visibility: "visible",
            drawSVG: "0%",
            fill: "#EF3E36",
            fillOpacity: 0,
            strokeOpacity: 1
        }
    );

//──────────────────────────────────────────────────────────────────────────
// 3. Create a one‐time timeline that:
//    • draws in order:   10, 13, 7, 9, 12, 15, 14, 11, 8, 16
//    • overlaps each draw slightly
//    • fades out the stroke and fades in the fill smoothly
//──────────────────────────────────────────────────────────────────────────
    const order = [10, 13, 7, 9, 12, 15, 14, 11, 8, 16];
    const drawDur    = 0.5;  // duration (s) to draw each outline
    const fillDur    = 0.2;  // duration (s) to fade fill in & stroke out
    const drawOverlap = 0.2; // start next draw 0.2s before previous finishes

    const tl = gsap.timeline({
        defaults: { ease: "power1.inOut" },
        repeat: 0   // play only once
    });

    order.forEach((idx, i) => {
        const sel = `header .logo svg > path:nth-of-type(${idx})`;

        // ─── Draw the stroke from 0% → 100% ────────────────────────────────
        // First path starts normally; subsequent ones overlap by drawOverlap.
        if (i === 0) {
            tl.fromTo(
                sel,
                { drawSVG: "0%" },
                { drawSVG: "100%", duration: drawDur }
            );
        } else {
            tl.fromTo(
                sel,
                { drawSVG: "0%" },
                { drawSVG: "100%", duration: drawDur },
                `-=${drawOverlap}`
            );
        }

        // ─── Fade in fill & fade out stroke ───────────────────────────────
        // This tween begins drawOverlap seconds before the draw ends,
        // so the stroke starts fading out just before it’s fully drawn.
        tl.to(
            sel,
            {
                fillOpacity: 1,     // fade fill from 0 → 1
                strokeOpacity: 0,   // fade stroke from 1 → 0
                duration: fillDur
            },
            `-=${drawOverlap}`
        );
    });
});