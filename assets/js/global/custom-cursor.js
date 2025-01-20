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
