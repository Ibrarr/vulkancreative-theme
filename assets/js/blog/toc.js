import { prefersReducedMotion } from '../components/reduced-motion';

// Table-of-contents scrollspy + click handling.
//
// Scrollspy: marks the in-view section's link .is-active so the reader can see
// their progress. State, not motion, so it works under reduced motion.
//
// Click: a naive spy breaks here for two reasons. (1) The page sets
// `html { scroll-behavior: smooth }` (Bootstrap), so a jump is a long animated
// scroll and the spy keeps whichever section you were *on* highlighted the
// whole way. (2) The browser's smooth scroll aims at where the heading is *at
// click time*; if a lazy image or late font above it loads mid-trip, the
// heading gets pushed down and the scroll lands short — the previous section is
// left at the top and stays active.
//
// So we drive the scroll ourselves: own the click in the capture phase and stop
// it (the global remove-anchor handler would otherwise scroll again and reset
// the URL to "/"), mark the clicked link active immediately, lock the spy, and
// run a short rAF tween that recomputes the target every frame (so it follows
// any layout shift) and holds it pinned for a brief settle window. We force
// `scroll-behavior: auto` for the duration so every scrollTo is truly instant
// and controllable. A real scroll gesture mid-trip hands control back.
//
// No-ops where there is no TOC.
document.addEventListener('DOMContentLoaded', () => {
    const toc = document.querySelector('.insight-toc');
    if (!toc) return;

    const links = Array.from(toc.querySelectorAll('a[href^="#"]'));
    if (!links.length) return;

    const entries = links
        .map((link) => {
            const id = decodeURIComponent(link.getAttribute('href').slice(1));
            const target = document.getElementById(id);
            return target ? { link, target } : null;
        })
        .filter(Boolean);
    if (!entries.length) return;

    const OFFSET = 140;   // active flips when a heading sits this far below the top
    const LANDING = 120;  // where a clicked heading should come to rest
    const TWEEN_MS = 520; // scroll animation length
    const SETTLE_MS = 420; // hold-pinned window after, to absorb late shifts

    const root = document.documentElement;

    let ticking = false;
    let lock = false;        // true while a click-driven scroll is running
    let lockLink = null;
    let raf = null;
    let savedBehavior = null;

    const setActive = (activeLink) => {
        entries.forEach((entry) => {
            entry.link.classList.toggle('is-active', entry.link === activeLink);
        });
    };

    const update = () => {
        ticking = false;
        if (lock) return; // don't let the trip override the clicked target
        const scrollY = window.scrollY || window.pageYOffset;
        let activeIndex = 0;
        for (let i = 0; i < entries.length; i += 1) {
            const top = entries[i].target.getBoundingClientRect().top + scrollY;
            if (top - OFFSET <= scrollY) activeIndex = i;
            else break;
        }
        setActive(entries[activeIndex].link);
    };

    const onScroll = () => {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(update);
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });

    // --- click lock plumbing ---
    const SCROLL_KEYS = new Set(['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End', ' ', 'Spacebar']);
    const stopRaf = () => {
        if (raf) {
            cancelAnimationFrame(raf);
            raf = null;
        }
    };
    const restoreBehavior = () => {
        if (savedBehavior !== null) {
            root.style.scrollBehavior = savedBehavior;
            savedBehavior = null;
        }
    };
    const removeTakeover = () => {
        window.removeEventListener('wheel', takeover);
        window.removeEventListener('touchstart', takeover);
        window.removeEventListener('keydown', keyTakeover);
    };
    const endLock = () => {
        if (!lock) return;
        lock = false;
        stopRaf();
        restoreBehavior();
        removeTakeover();
        if (lockLink) setActive(lockLink); // honour the click; spy resumes on next scroll
    };
    function takeover() { // a real scroll gesture mid-trip: hand back to the spy
        if (!lock) return;
        lock = false;
        stopRaf();
        restoreBehavior();
        removeTakeover();
        update();
    }
    function keyTakeover(e) {
        if (SCROLL_KEYS.has(e.key)) takeover();
    }

    const goTo = (entry) => {
        stopRaf();
        restoreBehavior();
        removeTakeover();

        lock = true;
        lockLink = entry.link;
        setActive(entry.link); // immediate feedback — no crawl through stale sections

        // Absolute document position the heading should rest at, re-read each
        // frame so the tween follows any layout shift above it.
        const targetY = () => Math.max(
            0,
            Math.round(entry.target.getBoundingClientRect().top + (window.scrollY || 0) - LANDING),
        );

        // We animate ourselves; disable the CSS smooth scroll so every scrollTo
        // is instant and synchronous.
        savedBehavior = root.style.scrollBehavior;
        root.style.scrollBehavior = 'auto';

        // Land keyboard focus on the heading without a second scroll.
        entry.target.setAttribute('tabindex', '-1');
        entry.target.focus({ preventScroll: true });

        window.addEventListener('wheel', takeover, { passive: true });
        window.addEventListener('touchstart', takeover, { passive: true });
        window.addEventListener('keydown', keyTakeover);

        const startY = window.scrollY || 0;
        const tweenMs = prefersReducedMotion() ? 0 : TWEEN_MS;
        const ease = (t) => 1 - Math.pow(1 - t, 3); // easeOutCubic
        const start = performance.now();

        const tick = (now) => {
            if (!lock) return;
            const elapsed = now - start;
            const tY = targetY();
            if (elapsed < tweenMs) {
                const p = ease(elapsed / tweenMs);
                window.scrollTo(0, Math.round(startY + (tY - startY) * p));
            } else {
                window.scrollTo(0, tY); // hold exact, absorbing any late shift
                if (elapsed > tweenMs + SETTLE_MS) { endLock(); return; }
            }
            raf = window.requestAnimationFrame(tick);
        };
        raf = window.requestAnimationFrame(tick);
    };

    // Capture phase + stopPropagation: own the click before it reaches the
    // link, so the global remove-anchor handler does not also run.
    toc.addEventListener('click', (e) => {
        const link = e.target.closest('a[href^="#"]');
        if (!link || !toc.contains(link)) return;
        const entry = entries.find((en) => en.link === link);
        if (!entry) return; // unknown target — let default handling deal with it

        e.preventDefault();
        e.stopPropagation();
        goTo(entry);
    }, true);

    update();
});
