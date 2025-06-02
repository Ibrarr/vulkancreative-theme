import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { fireworkEffect } from '../components/firework-button-effect';

gsap.registerPlugin(ScrollTrigger);

// Add CSS to prevent initial flash - run immediately
const hideStyle = document.createElement('style');
hideStyle.id = 'animation-hide-style';
hideStyle.textContent = `
    .hero h1,
    .hero .bottom,
    .split-text-hero,
    .dynamic-text,
    .spark path {
        opacity: 0 !important;
    }
`;
document.head.appendChild(hideStyle);

// Simple loading state tracker
let domReady = false;
let fontsReady = false;

function initializeWhenReady() {
    if (domReady && fontsReady) {
        // Small delay to ensure everything is settled
        setTimeout(() => {
            initializeAllAnimations();
        }, 100);
    }
}

function initializeAllAnimations() {
    // Remove the specific CSS hiding styles now that we're ready
    const hideStyleElement = document.getElementById('animation-hide-style');
    if (hideStyleElement) hideStyleElement.remove();

    // Hero animations (keep original)
    const heroContentTimeline = gsap.timeline({
        scrollTrigger: {
            trigger: '.hero',
            start: 'top 100%',
            toggleActions: 'play none none none',
        }
    });

    heroContentTimeline.fromTo('.hero h1',
        {
            opacity: 0,
            y: 50
        },
        {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: 'power2.out'
        }
    );

    gsap.fromTo('.hero .bottom',
        {
            opacity: 0,
            y: 30
        },
        {
            opacity: 1,
            y: 0,
            duration: 0.8,
            delay: 0.75,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.hero .bottom',
                start: 'top 100%',
                toggleActions: 'play none none none',
            }
        }
    );

    // Split text (keep original)
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
                delay: 0.2,
                ease: "expo.out",
            });
            return split;
        }
    });

    // Rolling words (keep original logic)
    const dynamicText = document.querySelector(".dynamic-text");
    const words = document.querySelectorAll(".dynamic-text .word");

    if (dynamicText && words.length > 0) {
        // Make dynamic text visible now
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
                animationInterval = setInterval(animateWords, 2000);
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
        }, 1000);
    }

    // Spark SVG (keep original)
    const paths = document.querySelectorAll(".spark path");

    paths.forEach((path) => {
        // Make spark visible now
        gsap.set(path, { opacity: 1 });

        const length = path.getTotalLength();

        gsap.set(path, {
            strokeDasharray: length,
            strokeDashoffset: length,
            fill: "transparent"
        });

        const getRandomDuration = (min, max) => Math.random() * (max - min) + min;

        const animatePath = () => {
            const timeline = gsap.timeline({
                onComplete: () => animatePath(),
            });

            timeline
                .to(path, {
                    strokeDashoffset: 0,
                    duration: getRandomDuration(1, 3),
                    ease: "power1.inOut",
                })
                .to(path, {
                    fill: "#ff4500",
                    duration: getRandomDuration(0.5, 1.5),
                }, "-=1")
                .to(path, {
                    strokeDashoffset: length,
                    duration: getRandomDuration(1, 3),
                    ease: "power1.inOut",
                })
                .to(path, {
                    fill: "transparent",
                    duration: getRandomDuration(0.5, 1.5),
                }, "-=1");
        };

        animatePath();
    });

    // Button effects (keep original)
    if (window.jQuery) {
        const $ = window.jQuery;
        const cursor = document.querySelector('.custom-cursor');

        if (cursor) {
            $('.button.disable-custom-cursor').on('mouseenter', function() {
                cursor.classList.add('hidden');
            });

            $('.button.disable-custom-cursor').on('mouseleave', function() {
                cursor.classList.remove('hidden');
            });
        }

        fireworkEffect('.hero .button');
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