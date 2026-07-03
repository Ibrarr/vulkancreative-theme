import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// The how-we-work ember rail: scrubbed scaleY over the ledger rows (the
// vertical process-timeline treatment). Reduced motion never enters the
// context; misc/_motion.scss shows the rail fully drawn instead.
document.addEventListener('DOMContentLoaded', () => {
    const wrap = document.querySelector('.about-how .how-rail-wrap');
    if (!wrap) return;

    const rail = wrap.querySelector('.how-progress');
    if (!rail) return;

    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
        const tween = gsap.fromTo(rail,
            { scaleY: 0 },
            {
                scaleY: 1,
                ease: 'none',
                scrollTrigger: {
                    trigger: wrap,
                    start: 'top 70%',
                    end: 'bottom 60%',
                    scrub: 0.5,
                },
            }
        );

        return () => {
            if (tween.scrollTrigger) tween.scrollTrigger.kill();
            tween.kill();
        };
    });
});
