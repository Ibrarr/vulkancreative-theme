import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

// Service filter for the /work/ archive grid. The chips are real links
// (/work/ and /work/?service={slug}) rendered server-side, so filtering works
// with no JS at all; this module intercepts them and filters the grid in
// place instead, keeping the URL in sync via pushState. Cards carry
// data-services="slug slug"; the editorial column pattern (wide/narrow) is
// recomputed over the visible subset so the rhythm holds under any filter.
// On small screens the chips sit in a dropdown panel behind a toggle button;
// selection closes the panel and returns focus to the toggle. Under reduced
// motion the swap is instant: the state change stays functional, only the
// transition goes.
document.addEventListener('DOMContentLoaded', () => {
    const bar = document.querySelector('[data-work-filter]');
    const grid = document.querySelector('[data-work-grid]');
    if (!bar || !grid) return;

    const chips = Array.from(bar.querySelectorAll('[data-service]'));
    const cards = Array.from(grid.querySelectorAll('.work-card'));
    const empty = document.querySelector('[data-work-empty]');
    const status = document.querySelector('[data-work-status]');
    const toggle = bar.querySelector('[data-work-toggle]');
    const current = bar.querySelector('[data-work-current]');
    if (!chips.length || !cards.length) return;

    const reduced = prefersReducedMotion();
    let animating = false;

    const matches = (card, slug) => {
        if (!slug) return true;
        return (card.dataset.services || '').split(/\s+/).includes(slug);
    };

    // Mirror of the PHP pattern: per visible index, 7/5 then 5/7 rows.
    const applyPattern = (visible) => {
        visible.forEach((card, i) => {
            const wide = i % 4 === 0 || i % 4 === 3;
            card.classList.remove('col-lg-7', 'col-lg-5', 'work-card--wide');
            card.classList.add(wide ? 'col-lg-7' : 'col-lg-5');
            if (wide) card.classList.add('work-card--wide');
        });
    };

    const setActive = (chip) => {
        chips.forEach((item) => {
            const active = item === chip;
            item.classList.toggle('is-active', active);
            if (active) {
                item.setAttribute('aria-current', 'true');
            } else {
                item.removeAttribute('aria-current');
            }
        });
        if (current) current.textContent = chip.textContent.trim();
    };

    const announce = (count) => {
        if (!status) return;
        status.textContent = count === 1 ? '1 project shown.' : count + ' projects shown.';
    };

    // Dropdown state (small screens; on desktop the toggle is display: none
    // and the panel is always visible, so open/close is a no-op there).
    const isOpen = () => bar.classList.contains('is-open');

    const closePanel = (focusToggle) => {
        if (!toggle || !isOpen()) return;
        bar.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        if (focusToggle) toggle.focus();
    };

    if (toggle) {
        toggle.addEventListener('click', () => {
            const open = !isOpen();
            bar.classList.toggle('is-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        document.addEventListener('click', (event) => {
            if (isOpen() && !bar.contains(event.target)) closePanel(false);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && isOpen()) closePanel(true);
        });
    }

    const swap = (show) => {
        cards.forEach((card) => {
            card.hidden = !show.includes(card);
        });
        applyPattern(show);
        if (empty) empty.hidden = show.length > 0;
        announce(show.length);
    };

    const apply = (slug, animate) => {
        const show = cards.filter((card) => matches(card, slug));

        if (!animate || reduced) {
            swap(show);
            gsap.set(show, { clearProps: 'opacity,transform' });
            return;
        }

        animating = true;
        const visible = cards.filter((card) => !card.hidden);
        gsap.to(visible, {
            opacity: 0,
            y: 8,
            duration: 0.22,
            ease: 'power1.in',
            onComplete: () => {
                swap(show);
                gsap.fromTo(show, { opacity: 0, y: 16 }, {
                    opacity: 1,
                    y: 0,
                    duration: 0.35,
                    stagger: 0.05,
                    ease: 'power2.out',
                    clearProps: 'transform',
                    onComplete: () => {
                        animating = false;
                    },
                });
            },
        });
    };

    const slugFromLocation = () => new URLSearchParams(window.location.search).get('service') || '';

    chips.forEach((chip) => {
        chip.addEventListener('click', (event) => {
            // Modified clicks keep native link behaviour (new tab etc.).
            if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
            event.preventDefault();
            if (chip.classList.contains('is-active')) {
                closePanel(true);
                return;
            }
            if (animating) return;
            window.history.pushState({}, '', chip.href);
            setActive(chip);
            closePanel(true);
            apply(chip.dataset.service || '', true);
        });
    });

    window.addEventListener('popstate', () => {
        const slug = slugFromLocation();
        const chip = chips.find((item) => (item.dataset.service || '') === slug) || chips[0];
        setActive(chip);
        closePanel(false);
        apply(chip.dataset.service || '', false);
    });
});
