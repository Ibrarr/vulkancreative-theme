import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(ScrollTrigger, SplitText);

const reduceMotion = prefersReducedMotion();

document.addEventListener('DOMContentLoaded', () => {
    if (reduceMotion) return;

    gsap.from('.services .tag', {
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.services .tag',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    gsap.from('.services .sub-heading', {
        opacity: 0,
        y: 20,
        duration: 0.5,
        delay: 0.5,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.services .sub-heading',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    gsap.from('.service-bento', {
        opacity: 0,
        y: 30,
        duration: 0.8,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.service-bento',
            start: 'top 95%',
            toggleActions: 'play none none none',
            once: true
        }
    });
});

document.fonts.ready.then(() => {
    gsap.set('.split-text-services', { opacity: 1 });

    if (reduceMotion) return;

    const split = SplitText.create('.split-text-services', {
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
            trigger: '.split-text-services',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    ScrollTrigger.refresh();
});

// Make each service card clickable (scrolls to its target / enquiry form).
jQuery(document).ready(function() {
    const services = document.querySelectorAll('.service');

    services.forEach(service => {
        service.addEventListener('click', (e) => {
            const link = service.querySelector('a.button');
            if (!link) return;

            const href = link.getAttribute('href');
            if (!href) return;

            if (href.startsWith('#')) {
                const target = document.getElementById(href.slice(1));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth' });
                    history.replaceState(null, '', window.location.pathname);
                }
            } else {
                window.location.href = href;
            }
        });
    });
});
