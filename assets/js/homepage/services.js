import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from '../components/reduced-motion';

gsap.registerPlugin(ScrollTrigger);

// Stop mobile browser address-bar show/hide from forcing full ScrollTrigger
// refreshes mid-scroll (a common source of jank on phones).
ScrollTrigger.config({ ignoreMobileResize: true });

// Services: on desktop (with motion allowed) the section grows tall, its
// inner viewport sticks natively, and the panel track scrubs horizontally
// with the scroll. Native position: sticky instead of ScrollTrigger pinning,
// so there is no fixed-position swap (and no layout shift or boundary jump).
// Below lg, and under reduced motion, it stays a vertical rail.
document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('.services');
    if (!section) return;

    const rail = section.querySelector('.service-rail');
    const track = section.querySelector('.service-track');
    if (!rail || !track) return;

    const mm = gsap.matchMedia();

    mm.add('(min-width: 992px) and (prefers-reduced-motion: no-preference)', () => {
        const distance = () => Math.max(0, track.scrollWidth - rail.clientWidth);
        const setHeight = () => {
            section.style.height = `${window.innerHeight + distance()}px`;
        };

        setHeight();

        gsap.to(track, {
            x: () => -distance(),
            ease: 'none',
            scrollTrigger: {
                trigger: section,
                start: 'top top',
                end: 'bottom bottom',
                scrub: 1,
                invalidateOnRefresh: true,
                onRefreshInit: setHeight,
            },
        });

        // Keyboard support: when a panel link receives focus, move the page
        // scroll to the position that brings that panel into view.
        const onFocus = (e) => {
            const panel = e.target.closest('.service-row');
            if (!panel) return;
            const d = distance();
            if (!d) return;
            const sectionTop = section.getBoundingClientRect().top + window.pageYOffset;
            const progress = Math.min(1, Math.max(0, panel.offsetLeft / d));
            window.scrollTo({ top: sectionTop + progress * d, behavior: 'auto' });
        };
        track.addEventListener('focusin', onFocus);

        return () => {
            track.removeEventListener('focusin', onFocus);
            section.style.height = '';
        };
    });

    // Vertical rail entrance (tablet/mobile, motion allowed): rows are hidden
    // up front (below the fold) and staggered in, so the reveal never pops.
    mm.add('(max-width: 991.98px) and (prefers-reduced-motion: no-preference)', () => {
        if (!('IntersectionObserver' in window)) return;

        const rows = section.querySelectorAll('.service-row');
        gsap.set(rows, { opacity: 0, y: 24 });

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                gsap.to(rows, { opacity: 1, y: 0, duration: 0.55, stagger: 0.08, ease: 'power2.out' });
                obs.unobserve(entry.target);
            });
        }, { threshold: 0.05, rootMargin: '0px 0px -4% 0px' });

        observer.observe(rail);

        return () => {
            observer.disconnect();
            gsap.set(rows, { opacity: 1, y: 0 });
        };
    });

    if (!prefersReducedMotion()) {
        gsap.from('.services .services-head', {
            opacity: 0,
            y: 24,
            duration: 0.6,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.services .services-head',
                start: 'top 95%',
                toggleActions: 'play none none none',
                once: true,
            },
        });
    }
});
