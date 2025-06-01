import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import {SplitText} from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.contact .tag', {
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.contact .tag',
            start: 'top 100%',
            toggleActions: 'play none none none'
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
            toggleActions: 'play none none none'
        }
    });
});

document.fonts.ready.then(() => {
    gsap.set('.split-text-contact', { opacity: 1 });

    SplitText.create('.split-text-contact', {
        type: 'words,lines',
        linesClass: 'line',
        mask: 'lines',
        autoSplit: true,
        onSplit: ({ lines }) => {
            gsap.timeline({
                scrollTrigger: {
                    trigger: '.split-text-contact',
                    start: 'top 100%',
                    toggleActions: 'play none none none'
                }
            })
                .from(lines, {
                    yPercent: 100,
                    opacity: 0,
                    duration: 0.8,
                    stagger: 0.1,
                    delay: 0.15,
                    ease: 'expo.out',
                })
                .from('.form-container', {
                    opacity: 0,
                    y: 30,
                    duration: 0.8,
                    delay: 0.5,
                    ease: 'power2.out'
                }, 0);
        }
    });

    ScrollTrigger.refresh();
});

jQuery(document).ready(function($) {
    const cursor = document.querySelector('.custom-cursor');

    $('.gform_button.button, input[type="text"], input[type="email"], textarea').on('mouseenter', function() {
        cursor.classList.add('hidden');
    });

    // Handle mouse leaving a button
    $('.gform_button.button, input[type="text"], input[type="email"], textarea').on('mouseleave', function() {
        cursor.classList.remove('hidden');
    });

    $('input[type="text"], input[type="email"], textarea').focus(function() {
        // Find the closest parent div, then find the label associated with this input
        $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '#FF3B30');
    });

    // When input loses focus
    $('input[type="text"], input[type="email"], textarea').blur(function() {
        // Reset label colour
        $(this).closest('div.ginput_container').parent().find('label.gfield_label').css('color', '');
    });
});