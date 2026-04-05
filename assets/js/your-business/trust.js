import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

document.addEventListener('DOMContentLoaded', () => {
    // Tag fade
    gsap.from('.yb-trust .tag', {
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-trust .tag',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    // Decorative quote mark: scale in and settle at low opacity
    gsap.fromTo('.yb-trust .decorative-quote',
        { scale: 0.6, opacity: 0 },
        {
            scale: 1,
            opacity: 0.08,
            duration: 0.8,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.yb-trust .testimonial',
                start: 'top 90%',
                toggleActions: 'play none none none',
                once: true
            }
        }
    );

    // Citation fade in after quote
    gsap.from('.yb-trust cite', {
        opacity: 0,
        y: 10,
        duration: 0.5,
        delay: 0.8,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-trust .testimonial',
            start: 'top 90%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    // Trust logos stagger fade in
    gsap.from('.yb-trust .trust-logos .trust-logo', {
        opacity: 0,
        y: 15,
        duration: 0.5,
        stagger: 0.2,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.yb-trust .trust-logos',
            start: 'top 95%',
            toggleActions: 'play none none none',
            once: true
        }
    });
});

document.fonts.ready.then(() => {
    // SplitText on heading
    gsap.set('.split-text-yb-trust', { opacity: 1 });

    const splitHeading = SplitText.create('.split-text-yb-trust', {
        type: 'words,lines',
        linesClass: 'line',
        mask: 'lines',
        autoSplit: true
    });

    gsap.from(splitHeading.lines, {
        yPercent: 100,
        opacity: 0,
        duration: 0.8,
        stagger: 0.1,
        delay: 0.15,
        ease: 'expo.out',
        scrollTrigger: {
            trigger: '.split-text-yb-trust',
            start: 'top 100%',
            toggleActions: 'play none none none',
            once: true
        }
    });

    // SplitText on testimonial quote
    const quoteEl = document.querySelector('.split-text-yb-trust-quote');
    if (quoteEl) {
        gsap.set('.split-text-yb-trust-quote', { opacity: 1 });

        const splitQuote = SplitText.create('.split-text-yb-trust-quote', {
            type: 'words,lines',
            linesClass: 'line',
            mask: 'lines',
            autoSplit: true
        });

        gsap.from(splitQuote.lines, {
            yPercent: 100,
            opacity: 0,
            duration: 0.8,
            stagger: 0.15,
            delay: 0.2,
            ease: 'expo.out',
            scrollTrigger: {
                trigger: '.split-text-yb-trust-quote',
                start: 'top 95%',
                toggleActions: 'play none none none',
                once: true
            }
        });
    }

    ScrollTrigger.refresh();
});
