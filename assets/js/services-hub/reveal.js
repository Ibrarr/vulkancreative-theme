import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';
import { revealFailsafe } from '../components/reveal-failsafe';

gsap.registerPlugin(SplitText);

// Section entrances for the services hub, mirroring the contact page reveal:
// with motion allowed, targets are hidden at load and revealed by
// IntersectionObserver as they enter the viewport — headings rise with a
// SplitText line mask, the rest fade up. Under reduced motion, without JS, or
// without IntersectionObserver nothing is ever hidden (misc/_motion.scss is
// the CSS safety net). The services directory rows rise one by one.
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    // Below lg the hero text paints with the first frame through the CSS
    // entrance in misc/_motion.scss (it is the page's LCP element), so this
    // module only owns the hero at lg+.
    const heroOnJs = window.matchMedia('(min-width: 992px)').matches;

    const headings = gsap.utils.toArray(`${heroOnJs ? '.hub-hero h1, ' : ''}.hub-services .content h2, .hub-process .content h2, .hub-proof .content h2, .hub-cta .content h2`);
    // The hero sub-line is the one fade left (part of the hero's load sequence).
    const fades = heroOnJs ? gsap.utils.toArray('.hub-hero .sub-heading') : [];
    // Staggered groups: the observer watches the container, the items cascade in.
    const groups = gsap.utils.toArray('.hub-process .process-steps');

    if (!headings.length && !fades.length && !groups.length) return;

    // animation:none cancels the CSS 2.5s failsafe once JS owns the reveal —
    // its `forwards` fill outranks inline styles, so left alive it would force
    // pre-hidden targets visible early and swallow their entrance.
    gsap.set([...headings, ...fades], { opacity: 0, y: 24, animation: 'none' });
    revealFailsafe([...headings, ...fades], 4000);
    groups.forEach((group) => {
        gsap.set(group.querySelectorAll('.process-step'), { opacity: 0, y: 16, animation: 'none' });
        revealFailsafe(group.querySelectorAll('.process-step'), 4000);
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
        gsap.to(el.querySelectorAll('.process-step'), {
            opacity: 1,
            y: 0,
            duration: 0.5,
            stagger: 0.08,
            ease: 'power2.out',
        });
    };

    // Directory rows rise one by one as they enter. Rows arriving in the same
    // frame (the first screen, or a fast scroll) cascade 60ms apart, so the
    // list reads top to bottom instead of landing as a block.
    const rows = gsap.utils.toArray('.services-directory .directory-row');
    if (rows.length) {
        gsap.set(rows, { opacity: 0, y: 16 });
        revealFailsafe(rows, 4000);
        const rowObserver = new IntersectionObserver((entries, obs) => {
            entries.filter((entry) => entry.isIntersecting).forEach((entry, i) => {
                gsap.to(entry.target, {
                    opacity: 1,
                    y: 0,
                    duration: 0.5,
                    delay: i * 0.06,
                    ease: 'power2.out',
                    clearProps: 'transform',
                });
                obs.unobserve(entry.target);
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -4% 0px' });
        rows.forEach((row) => rowObserver.observe(row));
    }

    const handlers = new Map();
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
