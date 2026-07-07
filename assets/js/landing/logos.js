import Splide from '@splidejs/splide';
import { AutoScroll } from '@splidejs/splide-extension-auto-scroll';
import { prefersReducedMotion } from '../components/reduced-motion';

// Client logo marquee — one per logos block. Under reduced motion the carousel
// is left static (draggable, no auto-scroll).
document.addEventListener('DOMContentLoaded', () => {
    const els = Array.from(document.querySelectorAll('.lp-logo-splide'));
    if (!els.length) return;

    const reduce = prefersReducedMotion();

    els.forEach((el) => {
        const options = {
            type: 'loop',
            drag: 'free',
            focus: 'center',
            perPage: 6,
            gap: '60px',
            arrows: false,
            pagination: false,
            breakpoints: {
                992: { perPage: 4, gap: '40px' },
                576: { perPage: 3, gap: '30px' },
            },
        };

        if (reduce) {
            new Splide(el, options).mount();
            return;
        }

        options.autoScroll = { speed: 0.8, pauseOnHover: false, pauseOnFocus: false };
        new Splide(el, options).mount({ AutoScroll });
    });
});
