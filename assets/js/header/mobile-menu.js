jQuery(document).ready(function($) {
    // Scoped handlers for mobile menu
    $('.mobile-menu-icons .open').on('click.mobileMenu', function() {
        $('.mobile-menu').slideDown(); // Slide down the mobile menus
        $(this).hide(); // Hide the open button
        $('.mobile-menu-icons  .close').show(); // Show the close button
        $('header').addClass('mobile-menu-active');
        $('body').addClass('no-scroll'); // Disable scrolling on the body
    });

    $('.mobile-menu-icons .close').on('click.mobileMenu', function() {
        $('.mobile-menu').slideUp(function() {
            // Remove the active classes after sliding up the mobile menus
            $('header').removeClass('mobile-menu-active active-mega-menu');
            $('.mega-menu-link').removeClass('active');
            $('body').removeClass('no-scroll'); // Enable scrolling on the body
        });

        $(this).hide(); // Hide the close button
        $('.mobile-menu-icons .open').show(); // Show the open button
    });

    // Handler for anchor links within the menu
    $('.mobile-menu a').on('click.mobileMenu', function(e) {
        const target = $(this).attr('href'); // Get the href attribute of the button
        if (target.startsWith('#')) { // Check if it's an anchor link
            e.preventDefault(); // Prevent default link behavior
            // Close the menu
            $('.mobile-menu').slideUp();
            $('.mobile-menu-icons .close').hide();
            $('.mobile-menu-icons .open').show();
            $('header').removeClass('mobile-menu-active');
            $('body').removeClass('no-scroll');

            // Navigate to the anchor link
            const offset = $(target).offset().top; // Get the offset position of the target element
            $('html, body').animate({ scrollTop: offset }); // Smooth scroll to the target
        }
    });
});
