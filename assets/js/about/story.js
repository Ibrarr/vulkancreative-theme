import videojs from 'video.js';
import 'videojs-youtube';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(ScrollTrigger);

const reduceMotion = prefersReducedMotion();

document.addEventListener('DOMContentLoaded', () => {
    if (reduceMotion) return;

    const storyTl = gsap.timeline({
        scrollTrigger: {
            trigger: '.about-story',
            start: 'top 100%',
            toggleActions: 'play none none none'
        }
    });

    storyTl.from('.about-story h2', {
        opacity: 0,
        y: 50,
        duration: 1,
        ease: 'power2.out'
    });

    gsap.from('.about-story .bottom', {
        opacity: 0,
        y: 30,
        duration: 0.8,
        delay: 0.75,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.about-story .bottom',
            start: 'top 100%',
            toggleActions: 'play none none none'
        }
    });

    gsap.from('.video-wrapper', {
        opacity: 0,
        y: 50,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.video-wrapper',
            start: 'top 100%',
            toggleActions: 'play none none none'
        }
    });
});

document.fonts.ready.then(() => {
    gsap.set('.split-text-story', { opacity: 1 });

    if (reduceMotion) return;

    SplitText.create('.split-text-story', {
        type: 'words,lines',
        linesClass: 'line',
        autoSplit: true,
        mask: 'lines',
        onSplit: ({ lines }) => {
            gsap.from(lines, {
                yPercent: 100,
                opacity: 0,
                duration: 0.8,
                stagger: 0.1,
                delay: 0.2,
                ease: 'expo.out',
                scrollTrigger: {
                    trigger: '.split-text-story',
                    start: 'top 100%',
                    toggleActions: 'play none none none'
                }
            });
        }
    });

    ScrollTrigger.refresh();
});

// Lazily initialise the video player just before the story section comes
// into view: booting it at page load competes with the entrance reveals and
// adds a visible stutter on warm reloads. The native poster shows until then.
document.addEventListener('DOMContentLoaded', () => {
    const story = document.querySelector('.about-story');
    const videoEl = document.getElementById('our-story');
    if (!videoEl) return;

    let initialised = false;
    const init = () => {
        if (initialised) return;
        initialised = true;
        videojs('our-story');
    };

    if (!story || !('IntersectionObserver' in window)) {
        init();
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            init();
            obs.disconnect();
        });
    }, { rootMargin: '800px 0px' });

    observer.observe(story);

    // Failsafe: make sure the player exists even if the observer never fires.
    setTimeout(init, 8000);
});
