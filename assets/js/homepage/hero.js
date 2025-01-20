import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { fireworkEffect } from '../components/firework-button-effect';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    const heroContentTimeline = gsap.timeline({
        scrollTrigger: {
            trigger: '.hero',
            start: 'top 90%',
            toggleActions: 'play none none none',
        }
    });

    heroContentTimeline.from('.hero .content', {
        opacity: 0,
        y: 50,
        duration: 1,
        ease: 'power2.out',
    });
});

// Rolling words
document.addEventListener("DOMContentLoaded", () => {
    const dynamicText = document.querySelector(".dynamic-text");
    const words = document.querySelectorAll(".dynamic-text .word");
    let animationInterval = null; // Store the interval for the animation
    let currentIndex = 0; // Track the current word index

    // Function to calculate and set the container and word widths
    function setFixedWidth() {
        let maxWidth = 0;

        // Temporarily make all words visible and measure their widths
        words.forEach((word) => {
            word.style.position = "static"; // Temporarily reset positioning
            word.style.transform = "none"; // Remove transformations

            const wordWidth = word.offsetWidth; // Measure the word's width
            maxWidth = Math.max(maxWidth, wordWidth);

            // Revert styles after measuring
            word.style.position = "absolute";
            word.style.transform = "translateY(100%)";
        });

        // Apply the maximum width to the container and all words
        dynamicText.style.width = `${maxWidth}px`;
        words.forEach((word) => {
            word.style.width = `${maxWidth}px`;
        });
    }

    // Function to run the word animation
    function animateWords() {
        const nextIndex = (currentIndex + 1) % words.length;

        // Seamless animation: current word slides up, next word slides in at the same time
        gsap.timeline()
            .set(words[nextIndex], { y: "100%" }) // Ensure the next word starts from the bottom
            .to(words[currentIndex], { y: "-100%", duration: 0.8, ease: "power2.inOut" }, 0) // Current word slides up
            .to(words[nextIndex], { y: "0%", duration: 0.8, ease: "power2.inOut" }, 0); // Next word slides in at the same time

        currentIndex = nextIndex; // Update the index for the next cycle
    }

    // Function to start the animation
    function startAnimation() {
        if (!animationInterval) {
            animationInterval = setInterval(animateWords, 2000); // Loop the animation every 2 seconds
        }
    }

    // Function to stop the animation
    function stopAnimation() {
        clearInterval(animationInterval);
        animationInterval = null;
    }

    // Handle visibility change
    document.addEventListener("visibilitychange", () => {
        if (document.hidden) {
            stopAnimation(); // Stop the animation when the tab is inactive
        } else {
            startAnimation(); // Resume the animation when the tab becomes active
        }
    });

    // Ensure fonts are fully loaded before starting
    if (document.fonts) {
        // Modern browsers: Wait for all fonts to load
        document.fonts.ready.then(() => {
            setFixedWidth();

            // Ensure all words are positioned correctly before animation starts
            words.forEach((word, index) => {
                gsap.set(word, { y: index === 0 ? "0%" : "100%", opacity: 1 }); // First word in view, others below
            });

            // Add an initial delay before starting the first animation cycle
            setTimeout(() => {
                animateWords(); // Run the first animation immediately
                startAnimation(); // Start the loop
            }, 1000); // 2-second initial delay
        });
    } else {
        // Fallback for older browsers
        window.onload = () => {
            setFixedWidth();

            // Ensure all words are positioned correctly before animation starts
            words.forEach((word, index) => {
                gsap.set(word, { y: index === 0 ? "0%" : "100%", opacity: 1 }); // First word in view, others below
            });

            // Add an initial delay before starting the first animation cycle
            setTimeout(() => {
                animateWords(); // Run the first animation immediately
                startAnimation(); // Start the loop
            }, 1000); // 2-second initial delay
        };
    }
});

// Draw spark SVG
document.addEventListener("DOMContentLoaded", () => {
    const paths = document.querySelectorAll("path");

    paths.forEach((path) => {
        const length = path.getTotalLength(); // Get the total path length

        // Set initial stroke-dasharray, stroke-dashoffset, and fill
        gsap.set(path, {
            strokeDasharray: length,
            strokeDashoffset: length,
            fill: "transparent"
        });

        // Create a function for random timing
        const getRandomDuration = (min, max) => Math.random() * (max - min) + min;

        // Create a timeline with randomized behavior
        const animatePath = () => {
            const timeline = gsap.timeline({
                onComplete: () => animatePath(), // Recursively trigger another random animation
            });

            timeline
                .to(path, {
                    strokeDashoffset: 0,
                    duration: getRandomDuration(1, 3), // Random draw duration
                    ease: "power1.inOut",
                })
                .to(path, {
                    fill: "#ff4500",
                    duration: getRandomDuration(0.5, 1.5), // Random fill duration
                }, "-=1") // Overlap fill with draw
                .to(path, {
                    strokeDashoffset: length,
                    duration: getRandomDuration(1, 3), // Random undraw duration
                    ease: "power1.inOut",
                })
                .to(path, {
                    fill: "transparent",
                    duration: getRandomDuration(0.5, 1.5), // Random unfill duration
                }, "-=1"); // Overlap unfill with undraw
        };

        // Start the animation
        animatePath();
    });
});

// Button
jQuery(document).ready(function($) {
    const cursor = document.querySelector('.custom-cursor');

    $('.button.disable-custom-cursor').on('mouseenter', function() {
        cursor.classList.add('hidden');
    });

    // Handle mouse leaving a button
    $('.button.disable-custom-cursor').on('mouseleave', function() {
        cursor.classList.remove('hidden');
    });

    fireworkEffect('.hero .button');
});