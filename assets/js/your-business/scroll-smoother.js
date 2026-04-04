import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ScrollSmoother } from 'gsap/ScrollSmoother';

gsap.registerPlugin(ScrollTrigger, ScrollSmoother);

const isTouchDevice = () => {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
};

// Delay ScrollSmoother init until after DOMContentLoaded + a short buffer
// so hero animations play first without interference
if (!isTouchDevice() && window.innerWidth >= 991) {
    window.addEventListener('load', () => {
        setTimeout(() => {
            ScrollSmoother.create({
                smooth: 1.2,
                effects: true,
                smoothTouch: false,
            });
            ScrollTrigger.refresh();
        }, 500);
    });
}
