// Mobile overlay menu: open/close, Escape, a Tab trap while open, and focus
// handed back to the toggle on close. Vanilla JS: this was the theme's only
// real jQuery module, and rewriting it lets jQuery leave every page that has
// no Gravity Form.
document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('header');
    const menu = document.querySelector('.mobile-menu');
    const toggle = document.querySelector('.mobile-menu-toggle');
    if (!header || !menu || !toggle) return;

    // Everything behind the overlay. Inert keeps screen-reader browse mode and
    // stray taps out of the page while the menu covers it.
    const page = document.getElementById('smooth-wrapper');

    const isOpen = () => header.classList.contains('mobile-menu-active');

    const openMenu = () => {
        header.classList.add('mobile-menu-active');
        document.body.classList.add('no-scroll');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', 'Close menu');
        if (page) page.setAttribute('inert', '');

        // Move focus into the menu once the overlay's visibility transition has
        // finished (focus fails while the overlay is still hidden).
        setTimeout(() => {
            if (!isOpen()) return;
            const first = menu.querySelector('a, button');
            if (first) first.focus();
        }, 400);
    };

    const closeMenu = (returnFocus = true) => {
        header.classList.remove('mobile-menu-active');
        document.body.classList.remove('no-scroll');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Open menu');
        if (page) page.removeAttribute('inert');
        if (returnFocus) toggle.focus();
    };

    toggle.addEventListener('click', () => (isOpen() ? closeMenu() : openMenu()));

    document.addEventListener('keydown', (e) => {
        if (!isOpen()) return;

        if (e.key === 'Escape') {
            closeMenu();
            return;
        }

        if (e.key !== 'Tab') return;

        // Visible controls only (collapsed accordion panels are skipped), plus
        // the toggle, which sits outside the overlay.
        const focusable = [...menu.querySelectorAll('a, button')]
            .filter((el) => el.offsetParent !== null)
            .concat(toggle);
        if (!focusable.length) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault();
            last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault();
            first.focus();
        }
    });

    // In-page anchors: close the overlay and leave the scroll to
    // global/remove-anchor-from-url.js, which already honours reduced motion.
    menu.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener('click', () => closeMenu(false));
    });
});
