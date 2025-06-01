import videojs from 'video.js';
import 'videojs-youtube';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import {SplitText} from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    const heroContentTl = gsap.timeline({
        scrollTrigger: {
            trigger: '.story',
            start: 'top 100%',
            toggleActions: 'play none none none'
        }
    });

    heroContentTl.from('.story h2', {
        opacity: 0,
        y: 50,
        duration: 1,
        ease: 'power2.out'
    });

    gsap.from('.story .bottom', {
        opacity: 0,
        y: 30,
        duration: 0.8,
        delay: 0.75,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.story .bottom',
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
                stagger: 0.2,
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

document.addEventListener("DOMContentLoaded", () => {
    const cursor       = document.querySelector(".custom-cursor");
    const content      = document.querySelector(".story .content");
    const videoWrapper = document.querySelector(".story .video-wrapper");

    const player = videojs("our-story");

    const isTouchDevice = () =>
        "ontouchstart" in window ||
        navigator.maxTouchPoints > 0 ||
        navigator.msMaxTouchPoints > 0;

    if (!isTouchDevice()) {
        videoWrapper.addEventListener("mouseenter", () => {
            cursor.classList.add("expanded");
            cursor.innerHTML = `<span class="learn-more-cursor">${
                player.paused() ? "Watch" : "Pause"
            }</span>`;
        });

        videoWrapper.addEventListener("mouseleave", () => {
            cursor.classList.remove("expanded");
            cursor.innerHTML = "";
        });

        player.on("play", () => {
            if (cursor.classList.contains("expanded")) {
                cursor.innerHTML =
                    '<span class="learn-more-cursor">Pause</span>';
            }
        });

        player.on("pause", () => {
            if (cursor.classList.contains("expanded")) {
                cursor.innerHTML =
                    '<span class="learn-more-cursor">Watch</span>';
            }
        });
    }

    gsap.timeline({
        scrollTrigger: {
            trigger: ".story",
            start: "top top",
            end: "bottom bottom",
            scrub: true,
        },
    })
        .to(
            content,
            { opacity: 0, scale: 0.8, duration: 0.3, ease: "power1.out" },
            0
        )
        .fromTo(
            videoWrapper,
            { scale: 0.8 },
            { scale: 1, duration: 0.8, ease: "power1.out" },
            0.1
        );
});