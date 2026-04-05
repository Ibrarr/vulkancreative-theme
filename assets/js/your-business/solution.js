import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

document.addEventListener('DOMContentLoaded', () => {
    // Tag fade
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

    // Sub-heading fade
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

    // Decorative numbers: scale in and settle at low opacity
    gsap.utils.toArray('.yb-solution .pillar-number').forEach((num) => {
        gsap.fromTo(num,
            { scale: 0.5, opacity: 0 },
            {
                scale: 1,
                opacity: 0.08,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: num,
                    start: 'top 95%',
                    toggleActions: 'play none none none',
                    once: true
                }
            }
        );

        // Subtle parallax: number drifts up slightly slower than scroll
        gsap.to(num, {
            y: -30,
            ease: 'none',
            scrollTrigger: {
                trigger: num,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1,
            }
        });
    });

    // Pillar content blocks: slide in from offset direction
    const pillarLeft = document.querySelector('.pillar-left .pillar-content');
    const pillarRight = document.querySelector('.pillar-right .pillar-content');

    if (pillarLeft) {
        gsap.from(pillarLeft, {
            x: -60,
            opacity: 0,
            duration: 0.8,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: pillarLeft,
                start: 'top 90%',
                toggleActions: 'play none none none',
                once: true
            }
        });
    }

    if (pillarRight) {
        gsap.from(pillarRight, {
            x: 60,
            opacity: 0,
            duration: 0.8,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: pillarRight,
                start: 'top 90%',
                toggleActions: 'play none none none',
                once: true
            }
        });
    }
});

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
