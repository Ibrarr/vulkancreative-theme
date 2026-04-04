import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

document.addEventListener('DOMContentLoaded', () => {
    const isDesktop = window.innerWidth >= 991;

    // Tag - exact homepage pattern
    gsap.from('.yb-solution .tag', {
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-solution .tag',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    // Sub-heading - exact homepage pattern
    gsap.from('.yb-solution .sub-heading', {
        opacity: 0,
        y: 20,
        duration: 0.5,
        delay: 0.5,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-solution .sub-heading',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    // Pillar cards - standard container entrance
    gsap.from('.yb-solution .pillar', {
        opacity: 0,
        y: 30,
        duration: 0.8,
        stagger: 0.2,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-solution .pillar-container',
            start: 'top 95%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    if (isDesktop) {
        // Parallax: pillars move at different scroll speeds
        gsap.to('.yb-solution .pillar-1', {
            y: -30,
            ease: 'none',
            scrollTrigger: {
                trigger: '.yb-solution .pillar-container',
                start: 'top bottom',
                end: 'bottom top',
                scrub: true,
            }
        });

        gsap.to('.yb-solution .pillar-2', {
            y: -60,
            ease: 'none',
            scrollTrigger: {
                trigger: '.yb-solution .pillar-container',
                start: 'top bottom',
                end: 'bottom top',
                scrub: true,
            }
        });

        // Background numbers: slowest layer
        gsap.to('.yb-solution .pillar-bg-number', {
            y: 40,
            ease: 'none',
            scrollTrigger: {
                trigger: '.yb-solution .pillar-container',
                start: 'top bottom',
                end: 'bottom top',
                scrub: true,
            }
        });
    }
});

// SplitText - exact homepage pattern
document.fonts.ready.then(() => {
    gsap.set('.split-text-yb-solution', { opacity: 1 });

    const split = SplitText.create('.split-text-yb-solution', {
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
            trigger: '.split-text-yb-solution',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    ScrollTrigger.refresh();
});
