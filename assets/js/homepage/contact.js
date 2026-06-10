import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(ScrollTrigger, SplitText);

const reduceMotion = prefersReducedMotion();

document.addEventListener('DOMContentLoaded', () => {
    if (reduceMotion) return;

    gsap.from('.contact .tag', {
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.contact .tag',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    gsap.from('.contact .sub-heading', {
        opacity: 0,
        y: 20,
        duration: 0.5,
        delay: 0.5,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.contact .sub-heading',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    gsap.from('.form-container', {
        opacity: 0,
        y: 30,
        duration: 0.8,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.form-container',
            start: 'top 95%',
            toggleActions: 'play none none none',
            once: true
        }
    });
});

document.fonts.ready.then(() => {
    gsap.set('.split-text-contact', { opacity: 1 });

    if (reduceMotion) return;

    const split = SplitText.create('.split-text-contact', {
        type: 'words,lines',
        linesClass: 'line',
        mask: 'lines',
        autoSplit: true
    });

    gsap.from(split.lines, {
        yPercent: 100,
        opacity: 0,
        duration: 0.8,
        stagger: 0.1,
        delay: 0.15,
        ease: 'expo.out',
        scrollTrigger: {
            trigger: '.split-text-contact',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    ScrollTrigger.refresh();
});

// Highlight the active field label in brand red on focus.
jQuery(document).ready(function($) {
    $('input[type="text"], input[type="email"], textarea').on('focus', function() {
        $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '#FF3B30');
    });

    $('input[type="text"], input[type="email"], textarea').on('blur', function() {
        $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '');
    });
});
