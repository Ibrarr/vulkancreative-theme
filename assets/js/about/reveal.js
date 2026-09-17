import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';
import { revealFailsafe } from '../components/reveal-failsafe';

gsap.registerPlugin(SplitText);

// Section entrances for the About page. Headings rise with a SplitText line
// mask; beyond that each section gets the motion that belongs to it rather
// than a blanket fade-up: the founder panels wipe open, the how-we-work rows
// cascade down their rail, and body copy simply sits there. Under reduced
// motion, without JS, or without IntersectionObserver nothing is ever hidden
// (misc/_motion.scss is the CSS safety net). The film's sub-line and frame are
// owned by story.js.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    // Below lg the hero text paints with the first frame through the CSS
    // entrance in misc/_motion.scss (it is the page's LCP element), so this
    // module only owns the hero at lg+.
    const heroOnJs = window.matchMedia('(min-width: 992px)').matches;

    const headings = gsap.utils.toArray(`${heroOnJs ? '.about-hero h1, ' : ''}.about-founders .content h2, .about-story .content h2, .about-values .content h2, .about-how .content h2, .about-proof .content h2, .about-press .content h2`);
    // The hero sub-line is the one fade left: it is part of the hero's load sequence.
    const fades = heroOnJs ? gsap.utils.toArray('.about-hero .sub-heading') : [];
    // Staggered groups: the observer watches the list, the items cascade in.
    const groups = gsap.utils.toArray('.about-how .how-rows');
    // The founder panels wipe open from the base, second panel a beat later.
    const duo = document.querySelector('.about-founders .founders-duo');
    const panels = duo ? gsap.utils.toArray(duo.querySelectorAll('.founder-panel')) : [];

    if (!headings.length && !fades.length && !groups.length && !panels.length) return;

    if (panels.length) {
        gsap.set(panels, { clipPath: 'inset(0% 0% 100% 0%)' });
        setTimeout(() => gsap.set(panels, { clearProps: 'clipPath' }), 5000); // never stay clipped
    }

    // animation:none cancels the CSS 2.5s failsafe once JS owns the reveal —
    // its `forwards` fill outranks inline styles, so left alive it would force
    // below-fold targets visible early and swallow their entrance.
    gsap.set([...headings, ...fades], { opacity: 0, y: 24, animation: 'none' });
    revealFailsafe([...headings, ...fades], 4000);
    groups.forEach((group) => {
        gsap.set(group.querySelectorAll('.how-row'), { opacity: 0, y: 16 });
        revealFailsafe(group.querySelectorAll('.how-row'), 4000);
    });

    const showHeading = (el) => {
        SplitText.create(el, {
            type: 'lines',
            linesClass: 'line',
            mask: 'lines',
            aria: 'none',
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

    const showGroup = (el) => {
        gsap.to(el.querySelectorAll('.how-row'), {
            opacity: 1,
            y: 0,
            duration: 0.5,
            stagger: 0.09,
            ease: 'power2.out',
        });
    };

    const showPanels = () => {
        gsap.to(panels, { clipPath: 'inset(0% 0% 0% 0%)', duration: 0.7, stagger: 0.12, ease: 'expo.out', clearProps: 'clipPath' });
    };

    const handlers = new Map();
    if (duo && panels.length) handlers.set(duo, showPanels);
    headings.forEach((el) => handlers.set(el, showHeading));
    fades.forEach((el) => handlers.set(el, showFade));
    groups.forEach((el) => handlers.set(el, showGroup));

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
