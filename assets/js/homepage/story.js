import videojs from 'video.js';
import 'videojs-youtube';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener("DOMContentLoaded", () => {
    const content = document.querySelector(".story .content");
    const videoWrapper = document.querySelector(".story .video-wrapper");

    videojs('our-story');

    gsap.timeline({
        scrollTrigger: {
            trigger: ".story",
            start: "top top",
            end: "bottom bottom",
            scrub: true,
        },
    })
        .to(content, { opacity: 0, scale: 0.8, duration: 0.3, ease: "power1.out" }, 0)
        .fromTo(
            videoWrapper,
            { scale: 0.8 },
            { scale: 1, duration: 0.8, ease: "power1.out" },
            0.1 // Start slightly after the text begins to fade
        );
});

jQuery(document).ready(function($) {
    const cursor = document.querySelector('.custom-cursor');

    $('.video-js').on('mouseenter', function() {
        cursor.classList.add('hidden');
    });

    // Handle mouse leaving a button
    $('.video-js').on('mouseleave', function() {
        cursor.classList.remove('hidden');
    });
});
