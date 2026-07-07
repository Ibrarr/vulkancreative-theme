import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

// FAQ accordions. The server renders every answer open (no-JS never hides
// content); this collapses them on init and toggles from there. Works across
// any number of FAQ blocks on the page. Height animation is skipped under
// reduced motion; the toggle itself always works.
document.addEventListener('DOMContentLoaded', () => {
    const items = Array.from(document.querySelectorAll('.lp-faq .lp-faq-item'));
    if (!items.length) return;

    const reduced = prefersReducedMotion();

    items.forEach((item) => {
        const button = item.querySelector('.faq-toggle');
        const answer = item.querySelector('.faq-a');
        if (!button || !answer) return;

        button.setAttribute('aria-expanded', 'false');
        answer.hidden = true;

        button.addEventListener('click', () => {
            const isOpen = button.getAttribute('aria-expanded') === 'true';

            if (isOpen) {
                button.setAttribute('aria-expanded', 'false');
                if (reduced) { answer.hidden = true; return; }
                gsap.to(answer, {
                    height: 0,
                    opacity: 0,
                    duration: 0.3,
                    ease: 'power2.in',
                    onComplete: () => {
                        answer.hidden = true;
                        gsap.set(answer, { clearProps: 'height,opacity' });
                    },
                });
                return;
            }

            button.setAttribute('aria-expanded', 'true');
            answer.hidden = false;
            if (reduced) return;
            gsap.fromTo(
                answer,
                { height: 0, opacity: 0 },
                {
                    height: 'auto',
                    opacity: 1,
                    duration: 0.35,
                    ease: 'power2.out',
                    onComplete: () => gsap.set(answer, { clearProps: 'height,opacity' }),
                }
            );
        });
    });
});
