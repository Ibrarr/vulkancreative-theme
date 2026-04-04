import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { shadowCursor } from '../components/shadow-cursor';

gsap.registerPlugin(ScrollTrigger, SplitText);

shadowCursor('.yb-outcomes');

document.addEventListener('DOMContentLoaded', () => {
    const isDesktop = window.innerWidth >= 991;

    // Tag - exact homepage pattern
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

    if (isDesktop) {
        // Horizontal scroll: pin section, translate track
        const track = document.querySelector('.outcomes-track');
        const panels = document.querySelectorAll('.outcome-panel');

        if (track && panels.length > 0) {
            const trackWidth = track.scrollWidth;
            const viewportWidth = window.innerWidth;
            const scrollDistance = trackWidth - viewportWidth + 100;

            gsap.to(track, {
                x: -scrollDistance,
                ease: 'none',
                scrollTrigger: {
                    trigger: '.yb-outcomes',
                    start: 'top top',
                    end: `+=${scrollDistance}`,
                    pin: '.yb-outcomes-pin',
                    scrub: 1,
                }
            });
        }
    } else {
        // Mobile: standard stagger
        gsap.from('.yb-outcomes .outcome-panel', {
            opacity: 0,
            y: 30,
            duration: 0.8,
            stagger: 0.2,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.yb-outcomes .outcomes-track',
                start: 'top 95%',
                toggleActions: 'play none none none',
                once: true
            }
        });
    }
});

// SplitText - exact homepage pattern
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
