import gsap from 'gsap';

// SW-04 hardening: whatever a reveal module pre-hides must never stay hidden.
// Call it with the same targets a module just set to opacity 0; after the
// delay anything still un-revealed fades in. Already-revealed targets end on
// the same values, so the catch-up is invisible. Pass props to limit what the
// failsafe touches (the work wheel positions its cards by transform, so it
// restores opacity only).
export function revealFailsafe(targets, delay = 4000, props = null) {
    if (!targets || (Array.isArray(targets) && !targets.length)) return;
    setTimeout(() => {
        gsap.to(targets, {
            ...(props || { opacity: 1, y: 0, x: 0, scale: 1 }),
            duration: 0.4,
            ease: 'power2.out',
            overwrite: false,
        });
    }, delay);
}
