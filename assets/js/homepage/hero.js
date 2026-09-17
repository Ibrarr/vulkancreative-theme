import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(ScrollTrigger);

const reduceMotion = prefersReducedMotion();

// The headline and the buttons rise in as CSS animations from the first painted
// frame (_hero.scss): the headline is the page's LCP element, so it cannot wait
// for this bundle or for fonts. This module owns what genuinely needs JS: the
// statue's scroll drift, the sub-line's SplitText line reveal (which needs
// settled fonts, and is pre-hidden behind the html.js gate with a 2.8s CSS
// failsafe) and the rolling words.

// Simple loading state tracker
let domReady = false;
let fontsReady = false;

function initializeWhenReady() {
    if (domReady && fontsReady) {
        initializeAllAnimations();
    }
}

function initializeAllAnimations() {
    // Reduced motion: everything is static. The first rolling word is shown by
    // CSS; the remaining words stay in the DOM but out of view.
    if (reduceMotion) {
        gsap.set('.split-text-hero', { opacity: 1 });
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

    // Sub-line: a line-by-line rise. aria: 'none' because SplitText's default
    // writes aria-label onto the paragraph, a prohibited attribute there;
    // reverting on completion hands assistive tech the untouched paragraph.
    gsap.set('.split-text-hero', { opacity: 1 });

    SplitText.create('.split-text-hero', {
        type: 'lines',
        linesClass: 'line',
        mask: 'lines',
        aria: 'none',
        autoSplit: false,
        onSplit(self) {
            return gsap.from(self.lines, {
                duration: 0.8,
                yPercent: 100,
                stagger: 0.2,
                delay: 0.4,
                ease: 'expo.out',
                onComplete: () => self.revert(),
            });
        },
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
