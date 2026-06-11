import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// How-we-work timeline: an ember line draws along the steps as the section
// scrolls through, and each step's outlined number fills solid as the line
// passes it. Horizontal across the top on desktop, vertical down the left
// rail below lg. Motion allowed only; gsap.matchMedia swaps the contexts and
// reverts inline transforms on resize. Under reduced motion neither runs.
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

    // 991.98 mirrors Bootstrap's media-breakpoint-down(lg) exactly, so the
    // vertical rail styles and this context always switch together.
    mm.add('(max-width: 991.98px) and (prefers-reduced-motion: no-preference)', () => {
        gsap.fromTo(line,
            { scaleY: 0 },
            {
                scaleY: 1,
                ease: 'none',
                scrollTrigger: {
                    trigger: grid,
                    start: 'top 70%',
                    end: 'bottom 60%',
                    scrub: 0.5,
                    onUpdate(self) {
                        const drawn = self.progress * grid.offsetHeight;
                        steps.forEach((step) => {
                            step.classList.toggle('is-passed', drawn >= step.offsetTop + 40);
                        });
                    },
                },
            }
        );

        return () => steps.forEach((step) => step.classList.remove('is-passed'));
    });
});
