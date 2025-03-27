import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

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