import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(ScrollTrigger);

const reduceMotion = prefersReducedMotion();

// The hero reveal targets are hidden from first paint by the stylesheet
// (html.js gate in _hero.scss) so a warm reload can never flash them; this
// module only has to reveal them. A CSS failsafe shows everything at 2.8s
// if this script never runs, and reduced motion is force-shown in CSS.

// Simple loading state tracker
let domReady = false;
let fontsReady = false;

function initializeWhenReady() {
    if (domReady && fontsReady) {
        initializeAllAnimations();
    }
}

function initializeAllAnimations() {
    // Reduced motion: show everything statically. The first rolling word is shown
    // by CSS; the remaining words stay in the DOM (for SEO) but out of view.
    if (reduceMotion) {
        gsap.set(['.hero h1', '.hero .bottom', '.split-text-hero', '.dynamic-text'], { opacity: 1, y: 0 });
        return;
    }

    // The statue drifts gently as the hero scrolls away
    gsap.to('.hero .graphic', {
        yPercent: 7,
        ease: 'none',
        scrollTrigger: {
            trigger: '.hero',
            start: 'top top',
            end: 'bottom top',
            scrub: true,
        },
    });

    // Hero heading: eyebrow first, then the headline rises in
    const heroContentTimeline = gsap.timeline({
        scrollTrigger: {
            trigger: '.hero',
            start: 'top 100%',
            toggleActions: 'play none none none',
        }
    });

    heroContentTimeline
        .fromTo('.hero h1',
            { opacity: 0, y: 60 },
            { opacity: 1, y: 0, duration: 1, ease: 'power3.out' }
        );

    gsap.fromTo('.hero .bottom',
        { opacity: 0, y: 30 },
        {
            opacity: 1,
            y: 0,
            duration: 0.8,
            delay: 0.6,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.hero .bottom',
                start: 'top 100%',
                toggleActions: 'play none none none',
            }
        }
    );

    // Split text
    gsap.set(".split-text-hero", { opacity: 1 });

    let split;
    SplitText.create(".split-text-hero", {
        type: "words,lines",
        linesClass: "line",
        autoSplit: true,
        mask: "lines",
        onSplit: (self) => {
            split = gsap.from(self.lines, {
                duration: 0.8,
                yPercent: 100,
                opacity: 0,
                stagger: 0.2,
                delay: 0.4,
                ease: "expo.out",
            });
            return split;
        }
    });

    // Rolling words
    const dynamicText = document.querySelector(".dynamic-text");
    const words = document.querySelectorAll(".dynamic-text .word");

    if (dynamicText && words.length > 0) {
        gsap.set(dynamicText, { opacity: 1 });

        let animationInterval = null;
        let currentIndex = 0;

        function setFixedWidth() {
            let maxWidth = 0;

            words.forEach((word) => {
                word.style.position = "static";
                word.style.transform = "none";

                const wordWidth = word.offsetWidth;
                maxWidth = Math.max(maxWidth, wordWidth);

                word.style.position = "absolute";
                word.style.transform = "translateY(100%)";
            });

            dynamicText.style.width = `${maxWidth}px`;
            words.forEach((word) => {
                word.style.width = `${maxWidth}px`;
            });
        }

        function animateWords() {
            const nextIndex = (currentIndex + 1) % words.length;

            gsap.timeline()
                .set(words[nextIndex], { y: "100%" })
                .to(words[currentIndex], { y: "-100%", duration: 0.8, ease: "power2.inOut" }, 0)
                .to(words[nextIndex], { y: "0%", duration: 0.8, ease: "power2.inOut" }, 0);

            currentIndex = nextIndex;
        }

        function startAnimation() {
            if (!animationInterval) {
                animationInterval = setInterval(animateWords, 2400);
            }
        }

        function stopAnimation() {
            clearInterval(animationInterval);
            animationInterval = null;
        }

        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                stopAnimation();
            } else {
                startAnimation();
            }
        });

        setFixedWidth();

        words.forEach((word, index) => {
            gsap.set(word, { y: index === 0 ? "0%" : "100%", opacity: 1 });
        });

        setTimeout(() => {
            animateWords();
            startAnimation();
        }, 1400);
    }
}

// DOM ready
document.addEventListener('DOMContentLoaded', () => {
    domReady = true;
    initializeWhenReady();
});

// Fonts ready
if (document.fonts) {
    document.fonts.ready.then(() => {
        fontsReady = true;
        initializeWhenReady();
    });
} else {
    window.addEventListener('load', () => {
        fontsReady = true;
        initializeWhenReady();
    });
}
