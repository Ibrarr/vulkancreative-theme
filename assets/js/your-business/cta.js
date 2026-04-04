import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.yb-cta .tag', {
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-cta .tag',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    gsap.from('.yb-cta .sub-heading', {
        opacity: 0,
        y: 20,
        duration: 0.5,
        delay: 0.5,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-cta .sub-heading',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    gsap.from('.yb-cta .form-container', {
        opacity: 0,
        y: 30,
        duration: 0.8,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-cta .form-container',
            start: 'top 95%',
            toggleActions: 'play none none none',
            once: true
        }
    });
});

document.fonts.ready.then(() => {
    gsap.set('.split-text-yb-cta', { opacity: 1 });

    const split = SplitText.create('.split-text-yb-cta', {
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
            trigger: '.split-text-yb-cta',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    ScrollTrigger.refresh();
});

jQuery(document).ready(function($) {
    const cursor = document.querySelector('.custom-cursor');

    $('.yb-cta .gform_button.button, .yb-cta input[type="text"], .yb-cta input[type="email"], .yb-cta textarea').on('mouseenter', function() {
        cursor.classList.add('hidden');
    });

    $('.yb-cta .gform_button.button, .yb-cta input[type="text"], .yb-cta input[type="email"], .yb-cta textarea').on('mouseleave', function() {
        cursor.classList.remove('hidden');
    });

    $('.yb-cta input[type="text"], .yb-cta input[type="email"], .yb-cta textarea').focus(function() {
        $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '#FF3B30');
    });

    $('.yb-cta input[type="text"], .yb-cta input[type="email"], .yb-cta textarea').blur(function() {
        $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '');
    });
});
