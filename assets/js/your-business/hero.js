import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { fireworkEffect } from '../components/firework-button-effect';

gsap.registerPlugin(ScrollTrigger);

// Anti-flash pattern - exact match to homepage/hero.js
const hideStyle = document.createElement('style');
hideStyle.id = 'yb-animation-hide-style';
hideStyle.textContent = `
    .yb-hero h1,
    .yb-hero .bottom,
    .yb-hero .tag,
    .split-text-yb-hero,
    .yb-hero .form-container,
    .yb-hero .form-trust {
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

    // Use individual tweens like homepage hero, NOT a shared timeline
    // Tag fade up
    gsap.to('.yb-hero .tag', {
        opacity: 1,
        y: 0,
        duration: 0.6,
        ease: 'power2.out',
    });

    // Set from states explicitly
    gsap.set('.yb-hero h1', { opacity: 0, y: 50 });
    gsap.set('.yb-hero .bottom', { opacity: 0, y: 30 });
    gsap.set('.yb-hero .form-container', { opacity: 0, y: 30 });
    gsap.set('.yb-hero .form-trust', { opacity: 0 });

    // H1 fade up - exact homepage hero values
    gsap.to('.yb-hero h1', {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: 'power2.out',
    });

    // SplitText - exact homepage hero pattern
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

    // Bottom CTA - exact homepage hero values
    gsap.to('.yb-hero .bottom', {
        opacity: 1,
        y: 0,
        duration: 0.8,
        delay: 0.75,
        ease: 'power2.out',
    });

    // Form container fade in
    gsap.to('.yb-hero .form-container', {
        opacity: 1,
        y: 0,
        duration: 0.8,
        delay: 0.3,
        ease: 'power2.out',
    });

    // Form trust line fade in
    gsap.to('.yb-hero .form-trust', {
        opacity: 1,
        duration: 0.5,
        delay: 0.8,
        ease: 'power2.out',
    });

    // Button effects - exact homepage hero pattern
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

            // Form interactions - exact homepage contact.js pattern
            $('.yb-hero .gform_button.button, .yb-hero input[type="text"], .yb-hero input[type="email"], .yb-hero textarea').on('mouseenter', function() {
                cursor.classList.add('hidden');
            });

            $('.yb-hero .gform_button.button, .yb-hero input[type="text"], .yb-hero input[type="email"], .yb-hero textarea').on('mouseleave', function() {
                cursor.classList.remove('hidden');
            });
        }

        fireworkEffect('.yb-hero .button');

        $('input[type="text"], input[type="email"], textarea').focus(function() {
            $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '#FF3B30');
        });

        $('input[type="text"], input[type="email"], textarea').blur(function() {
            $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '');
        });
    }
}

// DOM ready
document.addEventListener('DOMContentLoaded', () => {
    domReady = true;
    initializeWhenReady();
});

// Fonts ready
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
