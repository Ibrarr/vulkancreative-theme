jQuery(document).ready(function($) {
    const $header = $('header');
    const $menu = $('.mobile-menu');
    const $toggle = $('.mobile-menu-toggle');

    if (!$menu.length || !$toggle.length) {
        return;
    }

    const focusableSelector = '.mobile-menu a, .mobile-menu button';

    function openMenu() {
        $header.addClass('mobile-menu-active');
        $('body').addClass('no-scroll');
        $toggle.attr('aria-expanded', 'true').attr('aria-label', 'Close menu');

        // Move focus into the menu for keyboard users, once the overlay's
        // visibility transition has finished (focus fails while hidden).
        setTimeout(() => {
            if (!$header.hasClass('mobile-menu-active')) {
                return;
            }
            const firstLink = $menu.find('a, button').first();
            if (firstLink.length) {
                firstLink.trigger('focus');
            }
        }, 400);
    }

    function closeMenu(returnFocus = true) {
        $header.removeClass('mobile-menu-active');
        $('body').removeClass('no-scroll');
        $toggle.attr('aria-expanded', 'false').attr('aria-label', 'Open menu');

        if (returnFocus) {
            $toggle.trigger('focus');
        }
    }

    $toggle.on('click.mobileMenu', function() {
        if ($header.hasClass('mobile-menu-active')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    // Escape closes the menu
    $(document).on('keydown.mobileMenu', function(e) {
        if (e.key === 'Escape' && $header.hasClass('mobile-menu-active')) {
            closeMenu();
        }
    });

    // Simple focus trap while the overlay is open
    $(document).on('keydown.mobileMenuTrap', function(e) {
        if (e.key !== 'Tab' || !$header.hasClass('mobile-menu-active')) {
            return;
        }

        const focusable = $(focusableSelector).filter(':visible').add($toggle);
        if (!focusable.length) {
            return;
        }

        const first = focusable.first()[0];
        const last = focusable.last()[0];

        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault();
            last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault();
            first.focus();
        }
    });

    // Handler for anchor links within the menu
    $('.mobile-menu a').on('click.mobileMenu', function(e) {
        const target = $(this).attr('href');
        if (target && target.startsWith('#')) {
            e.preventDefault();
            closeMenu(false);

            const $target = $(target);
            if ($target.length) {
                const offset = $target.offset().top;
                // jQuery animate ignores prefers-reduced-motion, so branch:
                // reduced motion gets an instant jump.
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    window.scrollTo(0, offset);
                } else {
                    $('html, body').animate({ scrollTop: offset });
                }
            }
        }
    });
});
