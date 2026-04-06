import Splide from '@splidejs/splide';

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('yb-testimonial-splide');
    if (!el) return;

    new Splide(el, {
        type: 'loop',
        perPage: 2,
        gap: '30px',
        arrows: false,
        pagination: true,
        speed: 600,
        easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
        autoplay: true,
        interval: 5000,
        pauseOnHover: true,
        pauseOnFocus: true,
        breakpoints: {
            992: {
                perPage: 1,
            },
        },
    }).mount();
});
