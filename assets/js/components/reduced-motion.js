// Shared prefers-reduced-motion helper.
// Returns true when the user has asked the system to reduce motion, so each
// animation module can fall back to a static, fully-visible state.
export const prefersReducedMotion = () =>
    !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
