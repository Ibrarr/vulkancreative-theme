import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Scroll fill for the steps numerals — one instance per steps block, so a page
// can carry several. The rail draws with scroll and each numeral forges solid
// the instant the line reaches its leading edge (LEAD px early), so a numeral
// is always lit before the line arrives. Horizontal on desktop, vertical down
// the numeral column below lg. Without JS / under reduced motion the finished
// state (drawn rail, hot numerals) is the CSS default.
function initStepsRail(wrap) {
    const rail = wrap.querySelector('.lp-steps-rail');
    const grid = wrap.querySelector('.lp-steps-list');
    const steps = gsap.utils.toArray(wrap.querySelectorAll('.lp-step'));
    const numerals = steps.map((s) => s.querySelector('.step-index'));
    if (!rail || !grid || !steps.length || numerals.some((n) => !n)) return;

    const LEAD = 12;
    const MOBILE_RAIL_NUDGE = 5;

    const clearRail = () => rail.removeAttribute('style');
    const resetSteps = () => steps.forEach((s) => s.classList.remove('is-passed'));

    const mm = gsap.matchMedia();

    mm.add('(min-width: 992px) and (prefers-reduced-motion: no-preference)', () => {
        let edges = [];
        let centres = [];
        let railStart = 0;
        let railSpan = 1;

        const measure = () => {
            const wrapRect = wrap.getBoundingClientRect();
            edges = numerals.map((n) => n.getBoundingClientRect().left - wrapRect.left);
            centres = numerals.map((n) => {
                const r = n.getBoundingClientRect();
                return r.left - wrapRect.left + r.width / 2;
            });
            railStart = centres[0];
            railSpan = Math.max(1, centres[centres.length - 1] - railStart);
            rail.style.left = `${railStart}px`;
            rail.style.width = `${railSpan}px`;
            rail.style.right = 'auto';
        };

        const apply = (progress) => {
            rail.style.transform = `scaleX(${progress})`;
            const tip = progress * railSpan;
            steps.forEach((step, i) => {
                step.classList.toggle('is-passed', tip >= edges[i] - railStart - LEAD);
            });
        };

        measure();
        apply(0);

        const st = ScrollTrigger.create({
            trigger: grid,
            start: 'top 75%',
            end: 'bottom 45%',
            scrub: 0.5,
            onUpdate: (self) => apply(self.progress),
            onRefresh: () => { measure(); },
        });

        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(() => ScrollTrigger.refresh());
        }

        return () => { st.kill(); resetSteps(); clearRail(); };
    });

    mm.add('(max-width: 991.98px) and (prefers-reduced-motion: no-preference)', () => {
        let edges = [];
        let centres = [];
        let railStart = 0;
        let railSpan = 1;

        const measure = () => {
            const wrapRect = wrap.getBoundingClientRect();
            edges = numerals.map((n) => n.getBoundingClientRect().top - wrapRect.top);
            centres = numerals.map((n) => {
                const r = n.getBoundingClientRect();
                return r.top - wrapRect.top + r.height / 2;
            });
            const first = numerals[0].getBoundingClientRect();
            const centreX = first.left - wrapRect.left + first.width / 2 + MOBILE_RAIL_NUDGE;
            railStart = centres[0];
            railSpan = Math.max(1, centres[centres.length - 1] - railStart);
            rail.style.left = `${centreX}px`;
            rail.style.top = `${railStart}px`;
            rail.style.height = `${railSpan}px`;
            rail.style.bottom = 'auto';
        };

        const apply = (progress) => {
            rail.style.transform = `scaleY(${progress})`;
            const tip = progress * railSpan;
            steps.forEach((step, i) => {
                step.classList.toggle('is-passed', tip >= edges[i] - railStart - LEAD);
            });
        };

        measure();
        apply(0);

        const st = ScrollTrigger.create({
            trigger: grid,
            start: 'top 72%',
            end: 'bottom 55%',
            scrub: 0.5,
            onUpdate: (self) => apply(self.progress),
            onRefresh: () => { measure(); },
        });

        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(() => ScrollTrigger.refresh());
        }

        return () => { st.kill(); resetSteps(); clearRail(); };
    });
}

document.addEventListener('DOMContentLoaded', () => {
    gsap.utils.toArray('.lp-steps .lp-steps-rail-wrap').forEach(initStepsRail);
});
