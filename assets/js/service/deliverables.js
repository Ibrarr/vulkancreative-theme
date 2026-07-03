import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Deliverables ledger: the ember rail draws down the list with the scroll and
// each row's numeral heats (is-passed) just behind the rail tip. Runs inside
// a reduced-motion media context: with motion off, or without JS, the CSS
// renders the finished state instead — base numerals are red, and
// misc/_motion.scss forces the rail fully drawn (the about-how pattern).
document.addEventListener('DOMContentLoaded', () => {
    const wrap = document.querySelector('.service-deliverables .deliv-rail-wrap');
    if (!wrap) return;

    const rail = wrap.querySelector('.deliv-progress');
    const rows = gsap.utils.toArray('.deliv-row', wrap);

    const mm = gsap.matchMedia();
    mm.add('(prefers-reduced-motion: no-preference)', () => {
        if (rail) {
            gsap.fromTo(rail, { scaleY: 0 }, {
                scaleY: 1,
                ease: 'none',
                scrollTrigger: {
                    trigger: wrap,
                    start: 'top 70%',
                    end: 'bottom 60%',
                    scrub: 0.5,
                },
            });
        }

        // Numerals heat at 66% so the red arrives just behind the rail tip
        // (the rail starts drawing at 70%).
        const triggers = rows.map((row) => ScrollTrigger.create({
            trigger: row,
            start: 'top 66%',
            onEnter: () => row.classList.add('is-passed'),
            onLeaveBack: () => row.classList.remove('is-passed'),
        }));

        return () => triggers.forEach((trigger) => trigger.kill());
    });
});
