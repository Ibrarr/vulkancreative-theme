import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { shadowCursor } from '../components/shadow-cursor';

gsap.registerPlugin(ScrollTrigger, SplitText);

shadowCursor('.yb-problem');

document.addEventListener('DOMContentLoaded', () => {
    // Tag fade
    gsap.from('.yb-problem .tag', {
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-problem .tag',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    // Sub-heading fade
    gsap.from('.yb-problem .sub-heading', {
        opacity: 0,
        y: 20,
        duration: 0.5,
        delay: 0.5,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-problem .sub-heading',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    // Cards: staggered fade-up
    gsap.from('.problem-card', {
        opacity: 0,
        y: 30,
        duration: 0.8,
        stagger: 0.2,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.problem-cards',
            start: 'top 95%',
            toggleActions: 'play none none none',
            once: true
        }
    });
});

document.fonts.ready.then(() => {
    gsap.set('.split-text-yb-problem', { opacity: 1 });

    const split = SplitText.create('.split-text-yb-problem', {
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
            trigger: '.split-text-yb-problem',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    ScrollTrigger.refresh();
});
