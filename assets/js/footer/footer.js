import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { shadowCursor } from '../components/shadow-cursor'

gsap.registerPlugin(ScrollTrigger);

jQuery(document).ready(function($) {
    const cursor = document.querySelector('.custom-cursor');

    $('a, .gform-button, input[type="text"]').on('mouseenter', function() {
        cursor.classList.add('hidden');
    });

    // Handle mouse leaving a button
    $('a, .gform-button, input[type="text"]').on('mouseleave', function() {
        cursor.classList.remove('hidden');
    });
});

shadowCursor('#footer');