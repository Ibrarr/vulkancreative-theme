import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// The welded lattice. On lg+ with motion allowed, one scrubbed timeline
// assembles the plate as it enters the viewport: the hairline welds draw in
// staggered, the cell content rises and the red ticks (and the heading's
// ember underline) ignite — scrolling back unwinds it. Below lg the plate is
// a ruled list whose cells get a light one-shot fade instead. The base CSS
// renders the plate fully assembled, so no JS and reduced motion always see
// the complete state; this module only winds it back inside its own motion
// contexts. Every tween is transform/opacity only.
document.addEventListener('DOMContentLoaded', () => {
    const lattice = document.querySelector('.service-deliverables .deliv-lattice');
    if (!lattice) return;

    const weldsX = gsap.utils.toArray('.weld-x', lattice);
    const weldsY = gsap.utils.toArray('.weld-y', lattice);
    const hotsX = gsap.utils.toArray('.weld-hot-x', lattice);
    const hotsY = gsap.utils.toArray('.weld-hot-y', lattice);
    const headWeld = gsap.utils.toArray('.head-weld', lattice);
    const joints = gsap.utils.toArray('.weld-joint', lattice);
    const inners = gsap.utils.toArray('.cell-inner', lattice);
    if (!inners.length) return;

    const mm = gsap.matchMedia();

    mm.add('(min-width: 992px) and (prefers-reduced-motion: no-preference)', () => {
        gsap.set(weldsX, { scaleX: 0 });
        gsap.set(weldsY, { scaleY: 0 });
        gsap.set(hotsX, { scaleX: 0, opacity: 1 });
        gsap.set(hotsY, { scaleY: 0, opacity: 1 });
        gsap.set(headWeld, { scaleX: 0 });
        gsap.set(joints, { scale: 0 });
        gsap.set(inners, { opacity: 0, y: 16 });

        // The structure breathes with the scroll (welds scrub both ways, each
        // led by its ember-hot tip, which cools away once the line lands; the
        // red joints ignite where the lines cross and stay). The words reveal
        // exactly once and never leave — content is always visible after its
        // first appearance (standing decision).
        const tl = gsap.timeline({
            defaults: { ease: 'none' },
            scrollTrigger: {
                trigger: lattice,
                start: 'top 75%',
                end: 'bottom 65%',
                scrub: 0.5,
            },
        });
        tl.to(weldsX, { scaleX: 1, stagger: 0.1 }, 0)
            .to(hotsX, { scaleX: 1, stagger: 0.1 }, 0)
            .to(weldsY, { scaleY: 1, stagger: 0.1 }, 0.08)
            .to(hotsY, { scaleY: 1, stagger: 0.1 }, 0.08)
            .to(headWeld, { scaleX: 1 }, 0.1)
            .to([...hotsX, ...hotsY], { opacity: 0, stagger: 0.04 }, 0.62)
            .to(joints, { scale: 1, stagger: 0.05 }, 0.75);

        const contentTrigger = ScrollTrigger.create({
            trigger: lattice,
            start: 'top 70%',
            once: true,
            onEnter: () => {
                gsap.to(inners, {
                    opacity: 1,
                    y: 0,
                    duration: 0.6,
                    stagger: 0.07,
                    ease: 'power2.out',
                    clearProps: 'transform',
                });
            },
        });

        return () => {
            if (tl.scrollTrigger) tl.scrollTrigger.kill();
            tl.kill();
            contentTrigger.kill();
            gsap.set([...weldsX, ...weldsY, ...headWeld, ...joints], { clearProps: 'transform' });
            gsap.set([...hotsX, ...hotsY], { clearProps: 'transform,opacity' });
            gsap.set(inners, { clearProps: 'opacity,transform' });
        };
    });

    // Below lg: no scrub — the ruled list's cells fade up once as they enter
    // (the house IntersectionObserver pattern).
    mm.add('(max-width: 991.98px) and (prefers-reduced-motion: no-preference)', () => {
        if (!('IntersectionObserver' in window)) return;

        gsap.set(inners, { opacity: 0, y: 16 });
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                gsap.to(entry.target, { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out' });
                obs.unobserve(entry.target);
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -4% 0px' });
        inners.forEach((el) => observer.observe(el));

        return () => {
            observer.disconnect();
            gsap.set(inners, { clearProps: 'opacity,transform' });
        };
    });
});
