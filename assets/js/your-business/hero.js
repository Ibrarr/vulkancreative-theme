import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(ScrollTrigger);

const reduceMotion = prefersReducedMotion();

// Pre-hide the hero targets to prevent an initial flash before the entrance
// runs. Never under reduced motion: content must stay visible, and the CSS
// safety net (misc/_motion.scss) keeps everything shown.
if (!reduceMotion) {
    const hideStyle = document.createElement('style');
    hideStyle.id = 'yb-animation-hide-style';
    hideStyle.textContent = `
        .yb-hero h1,
        .split-text-yb-hero,
        .yb-hero .hero-form-container {
            opacity: 0 !important;
        }
    `;
    document.head.appendChild(hideStyle);
}

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

    // Reduced motion: skip the entrance entirely; content stays visible.
    if (!reduceMotion) {
        // Hero is always above the fold so use a timeline instead of ScrollTrigger
        const heroTl = gsap.timeline();

        // H1 fade up
        heroTl.fromTo('.yb-hero h1',
            { opacity: 0, y: 50 },
            { opacity: 1, y: 0, duration: 1, ease: 'power2.out' },
            0.2
        );

        // Hero form fade up
        heroTl.fromTo('.yb-hero .hero-form-container',
            { opacity: 0, y: 30 },
            { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' },
            0.4
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
    }

    // Hero form label handlers
    if (window.jQuery) {
        const $ = window.jQuery;

        $('.yb-hero .hero-form-container input[type="text"], .yb-hero .hero-form-container input[type="email"], .yb-hero .hero-form-container textarea').focus(function() {
            $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '#FF3B30');
        });

        $('.yb-hero .hero-form-container input[type="text"], .yb-hero .hero-form-container input[type="email"], .yb-hero .hero-form-container textarea').blur(function() {
            $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '');
        });
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
