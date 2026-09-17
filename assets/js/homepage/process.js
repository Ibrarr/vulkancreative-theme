import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// How-we-work timeline: an ember line draws along the steps as the section
// scrolls through, and each step's outlined number fills solid as the line
// reaches it. Motion allowed only; gsap.matchMedia swaps the contexts and
// reverts on resize. Under reduced motion neither runs.
//
// Desktop: every step owns its top rule and the columns have gaps between
// them, so the line is one segment per step (an injected .step-fill) and the
// scroll progress is shared out across them in order. It never paints the gap.
// Below lg the track is one continuous vertical rail, so the fill is too.
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.querySelector('.process .process-steps');
    const line = document.querySelector('.process .process-progress');
    if (!grid || !line) return;

    const steps = gsap.utils.toArray('.process .process-step');
    if (!steps.length) return;

    const mm = gsap.matchMedia();

    mm.add('(min-width: 992px) and (prefers-reduced-motion: no-preference)', () => {
        const fills = steps.map((step) => {
            const fill = document.createElement('span');
            fill.className = 'step-fill';
            fill.setAttribute('aria-hidden', 'true');
            step.appendChild(fill);
            return fill;
        });

        const state = { progress: 0 };
        const paint = () => {
            const along = state.progress * steps.length;
            steps.forEach((step, i) => {
                const share = Math.min(Math.max(along - i, 0), 1);
                fills[i].style.transform = `scaleX(${share})`;
                // The numeral sits at the start of its rule: it fills once the
                // line has run 40px into the step.
                step.classList.toggle('is-passed', share * step.offsetWidth >= 40);
            });
        };

        gsap.to(state, {
            progress: 1,
            ease: 'none',
            onUpdate: paint,
            scrollTrigger: {
                trigger: grid,
                start: 'top 78%',
                end: 'bottom 45%',
                scrub: 0.5,
            },
        });

        return () => {
            fills.forEach((fill) => fill.remove());
            steps.forEach((step) => step.classList.remove('is-passed'));
        };
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
