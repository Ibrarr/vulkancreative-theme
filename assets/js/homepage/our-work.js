import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';
import { revealFailsafe } from '../components/reveal-failsafe';

// The work wheel: every card sits on the rim of a huge invisible wheel whose
// centre is far below the stage. One progress value drives the whole thing —
// each card's x/y/rotation/scale derive from its angle to the centre card —
// so dragging, momentum, arrow steps and the fan-open entrance are all just
// tweens of progress (or of spread, for the entrance). Interaction notes:
// pointer drag anywhere on the stage spins it (touch-action: pan-y keeps the
// page scrollable on touch); a real drag suppresses the card click behind it
// and blurs any focused card so focus never sits in a hidden slide; cards
// more than two slots out get inert + aria-hidden, and focusing a visible
// card recentres it. Under reduced motion the geometry still applies (it is
// state, not motion) but every change is instant and the entrance is skipped.
// Without JS the stage stays a native horizontal scroller.
document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('work-wheel');
    if (!root) return;

    const stage = root.querySelector('.wheel-stage');
    const track = root.querySelector('.wheel-track');
    const cards = track ? gsap.utils.toArray(track.querySelectorAll('.wheel-card')) : [];
    if (!stage || !cards.length) return;

    const reduceMotion = prefersReducedMotion();
    const count = cards.length;
    const prevBtn = root.querySelector('.wheel-arrow--prev');
    const nextBtn = root.querySelector('.wheel-arrow--next');

    // Wheel geometry per breakpoint: spacing is the arc distance between
    // neighbouring cards, the radius sets how hard the arc bends.
    const geometry = () => {
        const w = window.innerWidth;
        if (w < 576) return { radius: 1150, spacing: 336 };
        if (w < 992) return { radius: 1400, spacing: 408 };
        return { radius: 1800, spacing: 540 };
    };
    let geo = geometry();
    let anglePer = Math.asin(geo.spacing / geo.radius);

    // Every card can come to centre, including the first and last — the wheel
    // travels the full range so nothing is ever stranded off the end (on
    // small screens the end cards were otherwise unreachable). At the ends one
    // side of the arc is naturally emptier; that reads fine for a carousel.
    const minProgress = 0;
    const maxProgress = count - 1;

    // The template orders the cards centre-out (first pick in the middle),
    // so the wheel starts on the middle card with flanks both sides.
    const state = {
        progress: gsap.utils.clamp(minProgress, maxProgress, Math.floor((count - 1) / 2)),
        spread: 1,
    };

    const render = () => {
        cards.forEach((card, i) => {
            const theta = (i - state.progress) * anglePer * state.spread;
            const distance = Math.abs(i - state.progress);
            gsap.set(card, {
                x: Math.sin(theta) * geo.radius,
                y: (1 - Math.cos(theta)) * geo.radius,
                rotation: theta * (180 / Math.PI) * 0.6,
                scale: 1 - Math.min(distance, 3) * 0.045 * state.spread,
                zIndex: 100 - Math.round(distance * 10),
            });
            const hidden = distance > 2.05;
            if (card.__wheelHidden !== hidden) {
                card.__wheelHidden = hidden;
                card.toggleAttribute('inert', hidden);
                card.setAttribute('aria-hidden', hidden ? 'true' : 'false');
            }
            const front = distance < 0.5;
            if (card.__wheelFront !== front) {
                card.__wheelFront = front;
                card.classList.toggle('is-front', front);
            }
        });
        if (prevBtn) prevBtn.disabled = state.progress < minProgress + 0.5;
        if (nextBtn) nextBtn.disabled = state.progress > maxProgress - 0.5;
    };

    let tween = null;
    const goTo = (target, thrown = 0) => {
        target = gsap.utils.clamp(minProgress, maxProgress, Math.round(target));
        if (Math.abs(state.progress - target) < 0.01) return;
        if (tween) tween.kill();
        if (reduceMotion) {
            state.progress = target;
            render();
            return;
        }
        const distance = Math.abs(target - state.progress);
        tween = gsap.to(state, {
            progress: target,
            duration: Math.min(0.45 + distance * 0.15, 1),
            // A hard throw decays naturally; a single step settles with spring.
            ease: thrown > 0.6 ? 'power3.out' : 'back.out(1.3)',
            onUpdate: render,
        });
    };

    if (prevBtn) prevBtn.addEventListener('click', () => goTo(Math.round(state.progress) - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => goTo(Math.round(state.progress) + 1));

    // Drag to spin. Pointer moves only record a target; a rAF loop eases the
    // wheel toward it each frame, so input-rate jitter never reaches the
    // cards and the spin feels weighted instead of twitchy. The wheel does
    // not move at all below the 6px threshold (clicks stay perfectly still),
    // the start is re-based at the threshold so crossing it never jumps, a
    // mostly-vertical touch gesture hands straight back to page scrolling,
    // and the release velocity is smoothed so equal flicks throw equally.
    let dragging = false;
    let dragged = false;
    let dragStartX = 0;
    let dragStartY = 0;
    let dragStartProgress = 0;
    let targetProgress = state.progress;
    let lastX = 0;
    let lastT = 0;
    let velocity = 0;
    let dragRaf = null;

    const dragTick = () => {
        const ease = reduceMotion ? 1 : 0.35;
        state.progress += (targetProgress - state.progress) * ease;
        if (Math.abs(targetProgress - state.progress) < 0.0005) {
            state.progress = targetProgress;
        }
        render();
        if (dragging || state.progress !== targetProgress) {
            dragRaf = requestAnimationFrame(dragTick);
        } else {
            dragRaf = null;
        }
    };

    stage.addEventListener('pointerdown', (event) => {
        if (event.button) return;
        dragging = true;
        dragged = false;
        dragStartX = event.clientX;
        dragStartY = event.clientY;
        dragStartProgress = state.progress;
        targetProgress = state.progress;
        lastX = event.clientX;
        lastT = performance.now();
        velocity = 0;
        if (tween) tween.kill();
        stage.classList.add('is-dragging');
    });

    stage.addEventListener('pointermove', (event) => {
        if (!dragging) return;
        const dx = event.clientX - dragStartX;
        const dy = event.clientY - dragStartY;

        if (!dragged) {
            if (Math.abs(dx) <= 6) {
                // A mostly-vertical touch gesture is a page scroll, not a spin.
                if (event.pointerType === 'touch' && Math.abs(dy) > 12 && Math.abs(dy) > Math.abs(dx) * 1.2) {
                    dragging = false;
                    stage.classList.remove('is-dragging');
                }
                return;
            }
            dragged = true;
            // Nothing else may write progress once a real drag owns it.
            if (tween) tween.kill();
            // Re-base so crossing the threshold never jumps the wheel.
            dragStartX = event.clientX;
            dragStartProgress = state.progress;
            // Capture only once a real drag starts: capturing on pointerdown
            // retargets the eventual click to the stage, killing card links.
            try {
                stage.setPointerCapture(event.pointerId);
            } catch (err) {
                // A stale pointer id must not break the drag.
            }
            const active = document.activeElement;
            if (active && root.contains(active) && active.closest('.wheel-card')) {
                active.blur();
            }
        }

        const now = performance.now();
        if (now > lastT) {
            const instant = (event.clientX - lastX) / (now - lastT);
            velocity = velocity * 0.8 + instant * 0.2;
            lastX = event.clientX;
            lastT = now;
        }
        targetProgress = gsap.utils.clamp(
            minProgress - 0.35,
            maxProgress + 0.35,
            dragStartProgress - (event.clientX - dragStartX) / geo.spacing
        );
        if (!dragRaf) dragRaf = requestAnimationFrame(dragTick);
    });

    const endDrag = () => {
        if (!dragging) return;
        dragging = false;
        stage.classList.remove('is-dragging');
        if (dragRaf) {
            cancelAnimationFrame(dragRaf);
            dragRaf = null;
        }
        if (!dragged) return;
        const thrown = reduceMotion ? 0 : (velocity * -260) / geo.spacing;
        goTo(targetProgress + thrown, Math.abs(thrown));
    };
    stage.addEventListener('pointerup', endDrag);
    stage.addEventListener('pointercancel', endDrag);

    // Links are natively draggable: without this, a spin that starts on a
    // card becomes an HTML5 link-drag, the pointer stream is cancelled and
    // the wheel never moves.
    stage.addEventListener('dragstart', (event) => event.preventDefault());

    // A spin is not a click: suppress the card link behind a real drag.
    stage.addEventListener('click', (event) => {
        if (dragged) {
            event.preventDefault();
            event.stopPropagation();
            dragged = false;
        }
    }, true);

    // Keyboard focus recentres the wheel on the focused card. Mouse focus
    // must not: clicking a card should just open its link, and a drag that
    // starts on a card must not fight a recentre tween — so only
    // keyboard-style focus (:focus-visible) moves the wheel.
    root.addEventListener('focusin', (event) => {
        if (!event.target.matches(':focus-visible')) return;
        const card = event.target.closest('.wheel-card');
        if (!card) return;
        const index = cards.indexOf(card);
        if (index >= 0 && Math.abs(index - state.progress) > 0.55) goTo(index);
    });

    let resizeTimer = null;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            geo = geometry();
            anglePer = Math.asin(geo.spacing / geo.radius);
            render();
        }, 150);
    });

    stage.classList.add('is-wheel');

    // Entrance: the deck fans open from a centre stack on first view.
    if (!reduceMotion && 'IntersectionObserver' in window) {
        state.spread = 0;
        gsap.set(cards, { opacity: 0 });
        revealFailsafe(cards, 4000, { opacity: 1 });
        render();
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                gsap.to(cards, { opacity: 1, duration: 0.45, stagger: 0.06, ease: 'power1.out' });
                gsap.to(state, { spread: 1, duration: 0.9, ease: 'back.out(1.4)', onUpdate: render });
                obs.unobserve(entry.target);
            });
        }, { threshold: 0.2 });
        observer.observe(stage);
    } else {
        render();
    }
});
