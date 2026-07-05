import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Scroll fill for the how-it-works numerals. The rail draws with scroll and
// each numeral forges solid the instant the line reaches its leading EDGE (not
// its centre), so a numeral is always lit before the line visually arrives at
// it: you never see the line touching an unlit number. The numerals mask the
// rail in CSS, so it never shows behind a number, and the rail runs only from
// the first numeral to the last so it never trails past either. Horizontal on
// desktop, vertical down the numeral column below lg (the rail is centred on
// the numerals). Step text is always full-contrast; only the numeral colour
// tracks progress. Motion allowed only; without JS or under reduced motion the
// finished state (drawn rail, hot numerals) is the CSS default.
document.addEventListener('DOMContentLoaded', () => {
    const wrap = document.querySelector('.fw-how .fw-how-rail-wrap');
    const grid = document.querySelector('.fw-how .fw-how-steps');
    if (!wrap || !grid) return;

    const rail = wrap.querySelector('.fw-how-rail');
    const steps = gsap.utils.toArray('.fw-how .how-step');
    const numerals = steps.map((s) => s.querySelector('.step-index'));
    if (!steps.length || !rail || numerals.some((n) => !n)) return;

    // Light a numeral this many px before the line reaches its edge, so the
    // number is always already lit by the time the line arrives.
    const LEAD = 12;

    // The two-digit numerals read visually left of their box centre (the wider
    // "0" carries the mass), so nudge the mobile rail a few px right to sit
    // down the middle of the numbers.
    const MOBILE_RAIL_NUDGE = 5;

    const clearRail = () => rail.removeAttribute('style');
    const resetSteps = () => steps.forEach((s) => s.classList.remove('is-passed'));

    const mm = gsap.matchMedia();

    // Desktop: horizontal rail from the first numeral's centre to the last.
    mm.add('(min-width: 992px) and (prefers-reduced-motion: no-preference)', () => {
        let edges = [];      // box left of each numeral (the mask boundary)
        let centres = [];    // box centre of each numeral (rail endpoints)
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

    // Below lg: vertical rail down the numeral column, centred on the numerals.
    mm.add('(max-width: 991.98px) and (prefers-reduced-motion: no-preference)', () => {
        let edges = [];      // box top of each numeral
        let centres = [];    // box centre-Y of each numeral (rail endpoints)
        let railStart = 0;
        let railSpan = 1;

        const measure = () => {
            const wrapRect = wrap.getBoundingClientRect();
            edges = numerals.map((n) => n.getBoundingClientRect().top - wrapRect.top);
            centres = numerals.map((n) => {
                const r = n.getBoundingClientRect();
                return r.top - wrapRect.top + r.height / 2;
            });
            // Centre the rail on the numerals horizontally, nudged right to sit
            // over their visual middle.
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
});
