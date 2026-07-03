import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// The values scrub-fill. Base CSS renders the words solid red; with motion
// allowed this switches the section to outline mode (.is-scrub) and scrubs
// each word's red fill in with a clip-path as it crosses the viewport, both
// directions. Under reduced motion or without JS the class is never added
// and the finished solid-red state stands.
document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('.about-values');
    if (!section) return;

    const words = gsap.utils.toArray('.value-word', section);
    if (!words.length) return;

    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
        section.classList.add('is-scrub');

        const triggers = words.map((word) => {
            const fill = word.querySelector('.value-fill');
            if (!fill) return null;
            return gsap.fromTo(fill,
                { clipPath: 'inset(0% 100% 0% 0%)' },
                {
                    clipPath: 'inset(0% 0% 0% 0%)',
                    ease: 'none',
                    scrollTrigger: {
                        trigger: word,
                        start: 'top 82%',
                        end: 'top 38%',
                        scrub: 0.5,
                    },
                }
            );
        }).filter(Boolean);

        return () => {
            triggers.forEach((tween) => {
                if (tween.scrollTrigger) tween.scrollTrigger.kill();
                tween.kill();
            });
            section.classList.remove('is-scrub');
        };
    });
});
