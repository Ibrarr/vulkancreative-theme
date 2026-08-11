import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';
import { revealFailsafe } from '../components/reveal-failsafe';

gsap.registerPlugin(SplitText);

// Section entrances for the service pages, mirroring the contact page reveal:
// with motion allowed, targets are hidden at load and revealed by
// IntersectionObserver as they enter the viewport — headings rise with a
// SplitText line mask, the rest fade up, list groups cascade. Under reduced
// motion, without JS, or without IntersectionObserver nothing is ever hidden
// (misc/_motion.scss is the CSS safety net). The related cards are owned by
// grid.js and the deliverables rail by deliverables.js.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    // (.service-deliverables is absent on purpose: the welded lattice owns
    // every entrance inside its section, heading included.)
    const headings = gsap.utils.toArray('.service-hero h1, .service-journey .content h2, .service-results .content h2, .service-work .content h2, .service-case-studies .content h2, .service-insights .content h2, .service-related .content h2, .service-cta .content h2');
    const fades = gsap.utils.toArray('.service-hero .sub-heading, .service-hero .hero-actions, .service-journey .content .sub-heading, .service-cta .content .sub-heading, .service-cta .cta-actions');
    // Staggered groups: the observer watches the container, the items cascade
    // in. Each group carries its own child selector. (The journey plates and
    // the deliverables stack get no entrance — the scrub and the deck are
    // their motion.)
    const groupConfig = [
        ['.service-case-studies .case-studies-row', '.cs-card-item'],
        ['.service-insights .insights-row', '.insight-card'],
    ];

    const groups = [];
    groupConfig.forEach(([containerSel, childSel]) => {
        gsap.utils.toArray(containerSel).forEach((el) => {
            groups.push([el, childSel]);
        });
    });

    if (!headings.length && !fades.length && !groups.length) return;

    // animation:none cancels the CSS 2.5s failsafe once JS owns the reveal —
    // its `forwards` fill outranks inline styles, so left alive it would force
    // pre-hidden targets visible early and swallow their entrance.
    gsap.set([...headings, ...fades], { opacity: 0, y: 24, animation: 'none' });
    revealFailsafe([...headings, ...fades], 4000);
    groups.forEach(([el, childSel]) => {
        gsap.set(el.querySelectorAll(childSel), { opacity: 0, y: 16, animation: 'none' });
        revealFailsafe(el.querySelectorAll(childSel), 4000);
    });

    const showHeading = (el) => {
        SplitText.create(el, {
            type: 'lines',
            linesClass: 'line',
            mask: 'lines',
            autoSplit: false,
            onSplit(self) {
                gsap.set(el, { opacity: 1, y: 0 });
                return gsap.from(self.lines, {
                    yPercent: 110,
                    opacity: 0,
                    duration: 0.7,
                    stagger: 0.09,
                    ease: 'expo.out',
                    // Display headings run line-height 1, so the overflow mask
                    // clips descenders; restore the intact heading once done.
                    onComplete: () => self.revert(),
                });
            },
        });
    };

    const showFade = (el) => {
        gsap.to(el, { opacity: 1, y: 0, duration: 0.55, ease: 'power2.out' });
    };

    const childSelectors = new Map(groups);
    const showGroup = (el) => {
        gsap.to(el.querySelectorAll(childSelectors.get(el)), {
            opacity: 1,
            y: 0,
            duration: 0.5,
            stagger: 0.08,
            ease: 'power2.out',
        });
    };

    const handlers = new Map();
    headings.forEach((el) => handlers.set(el, showHeading));
    fades.forEach((el) => handlers.set(el, showFade));
    groups.forEach(([el]) => handlers.set(el, showGroup));

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const fn = handlers.get(entry.target);
            if (fn) fn(entry.target);
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -4% 0px' });

    // Observation starts once fonts are active: SplitText must measure the
    // settled metrics (Archivo's width stretch changes line breaks), or a
    // heading can animate with fallback-font wrapping and rewrap when the
    // reveal reverts. fonts.ready resolves immediately on a warm cache.
    document.fonts.ready.then(() => {
        handlers.forEach((fn, el) => observer.observe(el));
    });
});
