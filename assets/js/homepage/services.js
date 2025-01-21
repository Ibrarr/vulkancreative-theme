import { shadowCursor } from "../components/shadow-cursor";

shadowCursor('.services');

jQuery(document).ready(function($) {
    const cursor = document.querySelector('.custom-cursor');

    // Detect touch devices
    const isTouchDevice = () => {
        return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
    };

    if (isTouchDevice()) {
        return; // Exit the script for touch devices
    }

    // Track mouse movement
    document.addEventListener('mousemove', (e) => {
        cursor.style.left = `${e.clientX}px`;
        cursor.style.top = `${e.clientY}px`;
    });

    // Handle mouse entering `.service` elements
    const services = document.querySelectorAll('.service');

    services.forEach(service => {
        service.addEventListener('mouseenter', () => {
            cursor.classList.add('display');
            cursor.innerHTML = '<span class="learn-more-cursor">Learn<br>More</span>';
        });

        service.addEventListener('mouseleave', () => {
            // Reset the cursor to its original state
            cursor.classList.remove('expanded');
            cursor.innerHTML = ''; // Remove the text
        });

        service.addEventListener('click', (e) => {
            const link = service.querySelector('a.button');
            if (link) {
                const href = link.getAttribute('href');
                if (href) {
                    window.location.href = href;
                }
            }
        });
    });
});
