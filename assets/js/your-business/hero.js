import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { fireworkEffect } from '../components/firework-button-effect';

gsap.registerPlugin(ScrollTrigger);

let domReady = false;
let fontsReady = false;
let initialized = false;

function initializeWhenReady() {
    if (domReady && fontsReady && !initialized) {
        initialized = true;
        setTimeout(() => {
            initializeAllAnimations();
        }, 100);
    }
}

function initializeAllAnimations() {
    // Remove loading class to allow scrolling for subsequent sections
    document.body.classList.remove('loading');

    // Tag fade in
    gsap.fromTo('.yb-hero .tag',
        { opacity: 0, y: 20 },
        { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }
    );

    // H1 fade up
    gsap.fromTo('.yb-hero h1',
        { opacity: 0, y: 50 },
        { opacity: 1, y: 0, duration: 1, delay: 0.2, ease: 'power2.out' }
    );

    // Hero form slide up
    gsap.fromTo('.yb-hero .hero-form',
        { opacity: 0, y: 40 },
        { opacity: 1, y: 0, duration: 0.8, delay: 0.4, ease: 'power2.out' }
    );

    // Bottom CTA fade up (visible on mobile only via CSS)
    gsap.fromTo('.yb-hero .bottom',
        { opacity: 0, y: 30 },
        { opacity: 1, y: 0, duration: 0.8, delay: 0.75, ease: 'power2.out' }
    );

    // Hero trust logos fade in
    gsap.fromTo('.yb-hero .hero-trust-logos',
        { opacity: 0 },
        { opacity: 1, duration: 0.6, delay: 0.8, ease: 'power2.out' }
    );

    gsap.fromTo('.yb-hero .trust-logo',
        { opacity: 0, y: 10 },
        { opacity: 0.5, y: 0, duration: 0.5, delay: 0.9, stagger: 0.15, ease: 'power2.out' }
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
            $('.yb-hero .disable-custom-cursor').on('mouseenter', function() {
                cursor.classList.add('hidden');
            });

            $('.yb-hero .disable-custom-cursor').on('mouseleave', function() {
                cursor.classList.remove('hidden');
            });

            $('.yb-hero .gform_button.button, .yb-hero input[type="text"], .yb-hero input[type="email"], .yb-hero textarea').on('mouseenter', function() {
                cursor.classList.add('hidden');
            });

            $('.yb-hero .gform_button.button, .yb-hero input[type="text"], .yb-hero input[type="email"], .yb-hero textarea').on('mouseleave', function() {
                cursor.classList.remove('hidden');
            });
        }

        $('.yb-hero input[type="text"], .yb-hero input[type="email"], .yb-hero textarea').focus(function() {
            $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '#FF3B30');
        });

        $('.yb-hero input[type="text"], .yb-hero input[type="email"], .yb-hero textarea').blur(function() {
            $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '');
        });

        fireworkEffect('.yb-hero .button');
    }

    // Refresh ScrollTrigger for subsequent sections
    ScrollTrigger.refresh();
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
