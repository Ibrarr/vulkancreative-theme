import gsap from 'gsap';

jQuery(document).ready(function($) {
    const cursor = document.querySelector('.custom-cursor');

    // Create the shadow element
    const $shadow = $('<div></div>').css({
        position: 'absolute',
        width: '700px', // Larger shadow size
        height: '700px',
        borderRadius: '50%',
        background: `radial-gradient(circle, rgba(255,59,48,1) 0%, rgba(9,9,121,0) 70%)`, // Fades out more
        opacity: 0.60,
        pointerEvents: 'none',
        transform: 'translate(-50%, -50%)',
        display: 'none', // Start hidden
        zIndex: -1,
    });

    // Append shadow only inside .why container
    $('.why').css('position', 'relative').append($shadow);

    let mouseX = 0, mouseY = 0, shadowX = 0, shadowY = 0;

    // Mouse enter event with fadeIn
    $('.why').on('mouseenter', function() {
        $shadow.stop(true, true).fadeIn(300);
        cursor.classList.add('hidden');
    });

    // Mouse move event
    $('.why').on('mousemove', function(event) {
        const offset = $(this).offset();
        mouseX = event.pageX - offset.left;
        mouseY = event.pageY - offset.top;
    });

    // Smooth lag effect
    function animateShadow() {
        shadowX += (mouseX - shadowX) * 0.1; // Adjust lag intensity by changing 0.1
        shadowY += (mouseY - shadowY) * 0.1;

        $shadow.css({
            top: shadowY,
            left: shadowX
        });

        requestAnimationFrame(animateShadow);
    }

    animateShadow();

    // Mouse leave event with fadeOut
    $('.why').on('mouseleave', function() {
        $shadow.stop(true, true).fadeOut(300);
        cursor.classList.remove('hidden');
    });

    $('.why .button').on('mouseleave', function() {
        cursor.classList.add('hidden');
    });
});
