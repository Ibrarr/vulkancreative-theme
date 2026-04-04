import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { fireworkEffect } from '../components/firework-button-effect';

gsap.registerPlugin(ScrollTrigger);

// Add CSS to prevent initial flash
const hideStyle = document.createElement('style');
hideStyle.id = 'yb-animation-hide-style';
hideStyle.textContent = `
    .yb-hero h1,
    .yb-hero .bottom,
    .yb-hero .tag,
    .split-text-yb-hero {
        opacity: 0 !important;
    }
`;
document.head.appendChild(hideStyle);

let domReady = false;
let fontsReady = false;

function initializeWhenReady() {
    if (domReady && fontsReady) {
        setTimeout(() => {
            initializeAllAnimations();
        }, 100);
    }
}

function initializeAllAnimations() {
    const hideStyleElement = document.getElementById('yb-animation-hide-style');
    if (hideStyleElement) hideStyleElement.remove();

    // Tag fade in
    gsap.fromTo('.yb-hero .tag',
        { opacity: 0, y: 20 },
        {
            opacity: 1,
            y: 0,
            duration: 0.6,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.yb-hero',
                start: 'top 100%',
                toggleActions: 'play none none none',
            }
        }
    );

    // H1 fade up
    gsap.fromTo('.yb-hero h1',
        { opacity: 0, y: 50 },
        {
            opacity: 1,
            y: 0,
            duration: 1,
            delay: 0.2,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.yb-hero',
                start: 'top 100%',
                toggleActions: 'play none none none',
            }
        }
    );

    // Bottom CTA fade up
    gsap.fromTo('.yb-hero .bottom',
        { opacity: 0, y: 30 },
        {
            opacity: 1,
            y: 0,
            duration: 0.8,
            delay: 0.75,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.yb-hero .bottom',
                start: 'top 100%',
                toggleActions: 'play none none none',
            }
        }
    );

    // Split text
    gsap.set('.split-text-yb-hero', { opacity: 1 });

    SplitText.create('.split-text-yb-hero', {
        type: 'words,lines',
        linesClass: 'line',
        autoSplit: true,
        mask: 'lines',
        onSplit: (self) => {
            return gsap.from(self.lines, {
                duration: 0.8,
                yPercent: 100,
                opacity: 0,
                stagger: 0.2,
                delay: 0.2,
                ease: 'expo.out',
            });
        }
    });

    // Button effects
    if (window.jQuery) {
        const $ = window.jQuery;
        const cursor = document.querySelector('.custom-cursor');

        if (cursor) {
            $('.disable-custom-cursor').on('mouseenter', function() {
                cursor.classList.add('hidden');
            });

            $('.disable-custom-cursor').on('mouseleave', function() {
                cursor.classList.remove('hidden');
            });
        }

        fireworkEffect('.yb-hero .button');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    domReady = true;
    initializeWhenReady();
});

if (document.fonts) {
    document.fonts.ready.then(() => {
        fontsReady = true;
        initializeWhenReady();
    });
} else {
    window.addEventListener('load', () => {
        fontsReady = true;
        initializeWhenReady();
    });
}
