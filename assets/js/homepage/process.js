import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// How-we-work timeline: an ember line draws across the top of the four steps
// as the section scrolls through, and each step's outlined number fills solid
// as the line passes it. Desktop only, motion allowed only (gsap.matchMedia
// handles both); below lg the line is hidden and steps just stagger in via
// reveal.js.
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.querySelector('.process .process-steps');
    const line = document.querySelector('.process .process-progress');
    if (!grid || !line) return;

    const steps = gsap.utils.toArray('.process .process-step');

    const mm = gsap.matchMedia();

    mm.add('(min-width: 992px) and (prefers-reduced-motion: no-preference)', () => {
        gsap.fromTo(line,
            { scaleX: 0 },
            {
                scaleX: 1,
                ease: 'none',
                scrollTrigger: {
                    trigger: grid,
                    start: 'top 78%',
                    end: 'bottom 45%',
                    scrub: 0.5,
                    onUpdate(self) {
                        const drawn = self.progress * grid.offsetWidth;
                        steps.forEach((step) => {
                            step.classList.toggle('is-passed', drawn >= step.offsetLeft + 40);
                        });
                    },
                },
            }
        );

        return () => steps.forEach((step) => step.classList.remove('is-passed'));
    });
});
