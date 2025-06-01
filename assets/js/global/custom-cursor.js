jQuery(document).ready(function($) {
    const cursor = document.querySelector('.custom-cursor');

    const isTouchDevice = () => {
        return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
    };

    if (isTouchDevice()) {
        cursor.style.display = 'none';
        return;
    }

    // Track mouse movement
    document.addEventListener('mousemove', (e) => {
        cursor.style.left = `${e.clientX}px`;
        cursor.style.top = `${e.clientY}px`;
    });
});

jQuery(function ($) {
    $(document).on('gform_post_render gform_confirmation_loaded gform_page_loaded', function () {

        let $cursor = $('.custom-cursor');
        if (!$cursor.length) {
            $cursor = $('<div class="custom-cursor"></div>');
        }

        $('body').append($cursor);

        $cursor.removeClass('hidden').show();
    });
});
