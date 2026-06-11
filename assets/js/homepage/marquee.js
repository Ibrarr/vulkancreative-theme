import Splide from '@splidejs/splide';
import { AutoScroll } from '@splidejs/splide-extension-auto-scroll';
import { prefersReducedMotion } from '../components/reduced-motion';

// Client logo marquee at the base of the hero. Under reduced motion the
// carousel is left static (draggable, no auto-scroll).
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('logo-splide');
    if (!el) return;

    const reduceMotion = prefersReducedMotion();

    const options = {
        type: 'loop',
        drag: 'free',
        focus: 'center',
        perPage: 6,
        gap: '60px',
        arrows: false,
        pagination: false,
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
    };

    if (reduceMotion) {
        new Splide(el, options).mount();
        return;
    }

    options.autoScroll = {
        speed: 0.8,
        pauseOnHover: false,
        pauseOnFocus: false,
    };

    new Splide(el, options).mount({ AutoScroll });
});
