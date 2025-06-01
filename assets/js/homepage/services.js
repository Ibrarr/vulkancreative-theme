import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import {SplitText} from "gsap/SplitText";
import { shadowCursor } from "../components/shadow-cursor";

gsap.registerPlugin(ScrollTrigger);

shadowCursor('.services');

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.services .tag', {
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.services .tag',
            start: 'top 100%',
            toggleActions: 'play none none none'
        }
    });

    gsap.from('.services .sub-heading', {
        opacity: 0,
        y: 20,
        duration: 0.5,
        delay: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.services .sub-heading',
            start: 'top 100%',
            toggleActions: 'play none none none'
        }
    });
});

document.fonts.ready.then(() => {
    gsap.set('.split-text-services', { opacity: 1 });

    SplitText.create('.split-text-services', {
        type: 'words,lines',
        linesClass: 'line',
        mask: 'lines',
        autoSplit: true,
        onSplit: ({ lines }) => {
            gsap.timeline({
                scrollTrigger: {
                    trigger: '.split-text-services',
                    start: 'top 100%',
                    toggleActions: 'play none none none'
                }
            })
                .from(lines, {
                    yPercent: 100,
                    opacity: 0,
                    duration: 0.8,
                    stagger: 0.18,
                    delay: 0.15,
                    ease: 'expo.out',
                })
                .from('.service-container', {
                    opacity: 0,
                    y: 30,
                    duration: 0.8,
                    delay: 0.2,
                    ease: 'power2.out'
                }, 0);
        }
    });

    ScrollTrigger.refresh();
});

jQuery(document).ready(function($) {
    const cursor = document.querySelector('.custom-cursor');

    // Detect touch devices
    const isTouchDevice = () => {
        return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
    };

    if (isTouchDevice()) {
        return; // Exit the script for touch devices
    }

    // Track mouse movement
    document.addEventListener('mousemove', (e) => {
        cursor.style.left = `${e.clientX}px`;
        cursor.style.top = `${e.clientY}px`;
    });

    // Handle mouse entering `.service` elements
    const services = document.querySelectorAll('.service');

    services.forEach(service => {
        service.addEventListener('mouseenter', () => {
            cursor.classList.remove('dot');
            cursor.classList.add('expanded');
            cursor.innerHTML = '<span class="learn-more-cursor">Enquire<br>Now</span>';
        });

        service.addEventListener('mouseleave', () => {
            cursor.classList.remove('expanded');
            cursor.innerHTML = '';
            cursor.classList.add('dot');
        });

        service.addEventListener('click', (e) => {
            const link = service.querySelector('a.button');
            if (link) {
                const href = link.getAttribute('href');
                if (href) {
                    window.location.href = href;
                }
            }
        });
    });
});
