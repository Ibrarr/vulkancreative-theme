import Splide from '@splidejs/splide';
import { AutoScroll } from '@splidejs/splide-extension-auto-scroll';

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('logo-splide');
    if (!el) return;

    new Splide(el, {
        type: 'loop',
        drag: 'free',
        focus: 'center',
        perPage: 6,
        gap: '60px',
        arrows: false,
        pagination: false,
        autoScroll: {
            speed: 0.8,
            pauseOnHover: false,
            pauseOnFocus: false,
        },
        breakpoints: {
            992: {
                perPage: 4,
                gap: '40px',
            },
            576: {
                perPage: 3,
                gap: '30px',
            },
        },
    }).mount({ AutoScroll });
});
