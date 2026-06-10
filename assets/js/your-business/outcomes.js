import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

document.addEventListener('DOMContentLoaded', () => {
    // Tag fade
    gsap.from('.yb-outcomes .tag', {
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-outcomes .tag',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    // Outcome items: staggered reveal
    const items = gsap.utils.toArray('.yb-outcomes .outcome-item');

    items.forEach((item) => {
        const number = item.querySelector('.outcome-number');
        const content = item.querySelector('.outcome-content');

        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: item,
                start: 'top 95%',
                toggleActions: 'play none none none',
                once: true
            }
        });

        // Number slides in from left
        if (number) {
            tl.from(number, {
                x: -20,
                opacity: 0,
                duration: 0.5,
                ease: 'power2.out',
            }, 0);
        }

        // Content fades up
        if (content) {
            tl.from(content, {
                y: 15,
                opacity: 0,
                duration: 0.5,
                ease: 'power2.out',
            }, 0.1);
        }
    });
});

document.fonts.ready.then(() => {
    gsap.set('.split-text-yb-outcomes', { opacity: 1 });

    const split = SplitText.create('.split-text-yb-outcomes', {
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
            trigger: '.split-text-yb-outcomes',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    ScrollTrigger.refresh();
});
