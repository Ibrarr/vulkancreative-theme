import Splide from '@splidejs/splide';
import { prefersReducedMotion } from '../components/reduced-motion';

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('testimonial-splide');
    if (!el) return;

    const reduceMotion = prefersReducedMotion();

    new Splide(el, {
        type: 'loop',
        perPage: 2,
        gap: '24px',
        arrows: false,
        pagination: true,
        speed: 600,
        easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
        autoplay: !reduceMotion,
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
