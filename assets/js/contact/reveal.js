import gsap from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(SplitText);

// Section entrances for the contact page, mirroring the homepage reveal: with
// motion allowed, targets are hidden at load and revealed by IntersectionObserver
// as they enter the viewport — headings rise with a SplitText line mask, the rest
// fade up. Under reduced motion, without JS, or without IntersectionObserver
// nothing is ever hidden (misc/_motion.scss is the CSS safety net).
document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const headings = gsap.utils.toArray('.contact-hero h1, .contact-main .contact-form-col h2');
    const fades = gsap.utils.toArray('.contact-hero .sub-heading, .contact-main .contact-form-col .form-container');
    // Staggered groups: the observer watches the list, the items cascade in.
    // (.contact-next is owned by next-steps.js so the rail draw and the step
    // stagger sequence as one timeline.)
    const groups = gsap.utils.toArray('.contact-main .contact-channels');

    if (!headings.length && !fades.length && !groups.length) return;

    // animation:none cancels the CSS 2.5s failsafe once JS owns the reveal —
    // its `forwards` fill outranks inline styles, so left alive it would force
    // below-fold targets visible early and swallow their entrance.
    gsap.set([...headings, ...fades], { opacity: 0, y: 24, animation: 'none' });
    groups.forEach((group) => {
        gsap.set(group.querySelectorAll('li'), { opacity: 0, y: 16, animation: 'none' });
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

    const showGroup = (el) => {
        gsap.to(el.querySelectorAll('li'), {
            opacity: 1,
            y: 0,
            duration: 0.5,
            stagger: 0.09,
            ease: 'power2.out',
        });
    };

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

    handlers.forEach((fn, el) => observer.observe(el));
});
