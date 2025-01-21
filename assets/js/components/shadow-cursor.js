export const shadowCursor = (selector) => {
    jQuery(document).ready(function($) {
        const cursor = document.querySelector('.custom-cursor');

        // Create the shadow element
        const $shadow = $('<div></div>').css({
            position: 'absolute',
            width: '700px', // Larger shadow size
            height: '700px',
            borderRadius: '50%',
            background: `radial-gradient(circle, rgba(255,59,48,1) 0%, rgba(9,9,121,0) 70%)`, // Fades out more
            opacity: 0.50,
            pointerEvents: 'none',
            transform: 'translate(-50%, -50%)',
            display: 'none', // Start hidden
            zIndex: -1,
        });

        // Append shadow only inside selector container
        $(selector).css('position', 'relative').append($shadow);

        let mouseX = 0, mouseY = 0, shadowX = 0, shadowY = 0;
        let isMouseInside = false; // Track if mouse is inside

        // Mouse enter event with fadeIn and position reset
        $(selector).on('mouseenter', function(event) {
            const offset = $(this).offset();
            mouseX = event.pageX - offset.left; // Update mouse position
            mouseY = event.pageY - offset.top;

            // Immediately set shadow to mouse position
            $shadow.css({
                top: mouseY,
                left: mouseX
            });

            shadowX = mouseX; // Reset shadowX
            shadowY = mouseY; // Reset shadowY

            isMouseInside = true;
            $shadow.stop(true, true).fadeIn(300);
            cursor.classList.add('hidden');
        });

        // Mouse move event
        $(selector).on('mousemove', function(event) {
            if (!isMouseInside) return; // Do nothing if mouse is outside
            const offset = $(this).offset();
            mouseX = event.pageX - offset.left;
            mouseY = event.pageY - offset.top;
        });

        // Smooth lag effect
        function animateShadow() {
            if (isMouseInside) {
                shadowX += (mouseX - shadowX) * 0.1; // Adjust lag intensity by changing 0.1
                shadowY += (mouseY - shadowY) * 0.1;

                $shadow.css({
                    top: shadowY,
                    left: shadowX
                });
            }
            requestAnimationFrame(animateShadow);
        }

        animateShadow();

        // Mouse leave event with fadeOut
        $(selector).on('mouseleave', function() {
            isMouseInside = false; // Stop updating shadow position
            $shadow.stop(true, true).fadeOut(300);
            cursor.classList.remove('hidden');
        });

        $(`${selector} .button.disable-custom-cursor`).on('mouseleave', function() {
            cursor.classList.add('hidden');
        });
    });
};