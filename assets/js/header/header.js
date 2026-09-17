document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById("header");

    // Landing template: the header is static (position: absolute) and scrolls
    // away with the hero, so skip the surface/hide-on-scroll behaviour.
    if (document.body.classList.contains('page-template-page-landing-page')) {
        return;
    }

    const SURFACE_AT = 24;  // px scrolled before the bar gains a surface
    const HIDE_AFTER = 160; // px scrolled before scroll-down hides the bar

    let prevScrollPos = window.scrollY;

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
        const currentScrollPos = window.scrollY;

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

// The logo's draw-in is pure CSS (header/components/_desktop.scss), so this
// bundle carries no animation library.
