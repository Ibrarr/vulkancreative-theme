import Splide from '@splidejs/splide';
import { prefersReducedMotion } from '../components/reduced-motion';

// Testimonial spotlight: one large quote at a time, crossfading on autoplay,
// with custom arrows, a slide counter and an autoplay progress bar. The
// duotone portrait panel mirrors the active slide. Under reduced motion the
// fades are instant and autoplay stays off.
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
    const portraits = Array.from(el.querySelectorAll('.spotlight-portrait'));
    const blockquotes = Array.from(el.querySelectorAll('.splide__slide blockquote'));

    // Equalise slide heights to the tallest quote so the crossfade never
    // shifts the layout below, without reserving more space than needed.
    const equaliseQuotes = () => {
        blockquotes.forEach((quote) => { quote.style.minHeight = ''; });
        const tallest = Math.max(...blockquotes.map((quote) => quote.offsetHeight));
        blockquotes.forEach((quote) => { quote.style.minHeight = `${tallest}px`; });
    };

    let resizeTimer = null;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(equaliseQuotes, 150);
    });

    splide.on('mounted', equaliseQuotes);

    // Re-measure once webfonts settle (metrics can change line counts);
    // nothing is hidden behind this, it only refines the reserved height.
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(equaliseQuotes);
    }

    splide.on('mounted move', () => {
        if (counter) {
            counter.textContent = String(splide.index + 1).padStart(2, '0');
        }
        portraits.forEach((portrait, i) => {
            portrait.classList.toggle('is-active', i === splide.index);
        });
    });

    splide.on('autoplay:playing', (rate) => {
        if (bar) {
            bar.style.width = `${rate * 100}%`;
        }
    });

    splide.mount();
});
