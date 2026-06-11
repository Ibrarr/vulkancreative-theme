import Splide from '@splidejs/splide';
import { prefersReducedMotion } from '../components/reduced-motion';

// Testimonial spotlight: one large quote at a time, crossfading on autoplay,
// with custom arrows, a slide counter and an autoplay progress bar.
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('testimonial-splide');
    if (!el) return;

    const reduceMotion = prefersReducedMotion();

    const splide = new Splide(el, {
        type: 'fade',
        rewind: true,
        speed: reduceMotion ? 0 : 700,
        autoplay: !reduceMotion,
        interval: 6000,
        pauseOnHover: true,
        pauseOnFocus: true,
        arrows: true,
        pagination: false,
    });

    const counter = el.querySelector('.spotlight-counter .current');
    const bar = el.querySelector('.spotlight-progress-bar');

    splide.on('mounted move', () => {
        if (counter) {
            counter.textContent = String(splide.index + 1).padStart(2, '0');
        }
    });

    splide.on('autoplay:playing', (rate) => {
        if (bar) {
            bar.style.width = `${rate * 100}%`;
        }
    });

    splide.mount();
});
