import Splide from '@splidejs/splide';
import { prefersReducedMotion } from '../components/reduced-motion';

// Testimonial spotlight: one review at a time, crossfading on autoplay, with
// custom arrows, a slide counter and an autoplay progress bar. The portrait
// panel (present only when reviews carry photos) mirrors the active slide.
// Under reduced motion the fades are instant and autoplay stays off.
//
// Slide heights need no script: Splide's fade track is as tall as its tallest
// slide, and each quote sets its own type size from its length, so the slides
// come out close to equal. The one thing handled here is a review too long
// for its line clamp, which gets a control that opens it in place.
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
    const quotes = Array.from(el.querySelectorAll('.spotlight-quote'));

    const closeAll = () => {
        quotes.forEach((quote) => {
            if (!quote.classList.contains('is-open')) return;
            quote.classList.remove('is-open');
            const button = quote.parentNode.querySelector('.spotlight-more');
            if (button) {
                button.setAttribute('aria-expanded', 'false');
                button.textContent = 'Read Full Review';
            }
        });
    };

    // Give every clamped quote its control. Runs once the fonts have settled
    // (line counts depend on the final metrics) and again after a resize.
    const syncControls = () => {
        quotes.forEach((quote, i) => {
            const holder = quote.parentNode;
            let button = holder.querySelector('.spotlight-more');
            if (quote.classList.contains('is-open')) return;

            const clipped = quote.scrollHeight - quote.clientHeight > 2;
            if (clipped && !button) {
                if (!quote.id) quote.id = `spotlight-quote-${i + 1}`;
                button = document.createElement('button');
                button.type = 'button';
                button.className = 'spotlight-more';
                button.textContent = 'Read Full Review';
                button.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-controls', quote.id);
                button.addEventListener('click', () => {
                    const open = quote.classList.toggle('is-open');
                    button.setAttribute('aria-expanded', open ? 'true' : 'false');
                    button.textContent = open ? 'Show Less' : 'Read Full Review';
                    // Someone reading a full review should not have it
                    // swapped out from under them.
                    const { Autoplay } = splide.Components;
                    if (open) Autoplay.pause(); else if (!reduceMotion) Autoplay.play();
                });
                holder.appendChild(button);
            } else if (!clipped && button) {
                button.remove();
            }
        });
    };

    let resizeTimer = null;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(syncControls, 150);
    });

    splide.on('mounted', () => {
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(syncControls);
        } else {
            syncControls();
        }
    });

    const slides = Array.from(el.querySelectorAll('.splide__slide'));

    splide.on('mounted move', () => {
        if (counter) {
            counter.textContent = String(splide.index + 1).padStart(2, '0');
        }
        portraits.forEach((portrait, i) => {
            portrait.classList.toggle('is-active', i === splide.index);
        });
        // Splide marks the slides it is not showing aria-hidden, but leaves
        // what is inside them focusable. inert takes the Read Full Review
        // control (and anything else) out of the tab order with them.
        slides.forEach((slide, i) => {
            slide.inert = i !== splide.index;
        });
    });

    // Leaving a slide closes an opened review, so the track returns to its
    // resting height once the fade has finished.
    splide.on('moved', closeAll);

    splide.on('autoplay:playing', (rate) => {
        if (bar) {
            bar.style.width = `${rate * 100}%`;
        }
    });

    splide.mount();
});
