// Global loading spinner for every Gravity Form on the site.
//
// On submit, GF (ajax="true") injects its own spinner GIF next to the button,
// which widens the footer and nudges the button. This module suppresses that
// (CSS + a PHP filter) and instead shows one house spinner centred *over* the
// pressed button, with the label made transparent so nothing reflows.
//
// It keys on the Gravity Forms wrapper and the form node GF hands us, never on
// a theme wrapper div (those vary: .form-container, .form, .hero-form-container),
// so a single hook covers every form and page. On pages with no form it no-ops.
//
// It co-exists with the contact form's steps.js: that owns `is-submitting` on
// `.form-container` (the body dim); this owns `vc-form-loading` on `.gform_wrapper`
// (the button spinner). Different node, different class — no collision.

import { prefersReducedMotion } from '../components/reduced-motion';

const BTN_SELECTOR = 'input[type="submit"], button[type="submit"], .gform_next_button, .gform_previous_button';

let filterAttached = false;
// The last submit/next/back control the user actually pressed. GF's
// pre_submission filter doesn't tell us which button fired, so we track it.
let lastPressed = null;

const isButton = (node) =>
    node && typeof node.closest === 'function' ? node.closest(BTN_SELECTOR) : null;

// Centre the spinner on the pressed button. Its offsetParent is the button's
// parent (we mark that parent position:relative), so offsetLeft/Top are already
// relative to where we append the spinner.
const injectSpinner = (btn) => {
    const parent = btn.parentNode;
    if (!parent || parent.nodeType !== 1) return;

    parent.classList.add('vc-form-loading-anchor');
    if (parent.querySelector(':scope > .vc-form-spinner')) return; // guard double-inject

    const spinner = document.createElement('span');
    spinner.className = 'vc-form-spinner';
    if (btn.classList.contains('gform_previous_button')) {
        spinner.classList.add('vc-form-spinner--prev');
    }
    spinner.setAttribute('aria-hidden', 'true');
    spinner.style.left = `${btn.offsetLeft + btn.offsetWidth / 2}px`;
    spinner.style.top = `${btn.offsetTop + btn.offsetHeight / 2}px`;
    parent.appendChild(spinner);
};

const startLoading = (wrapper, btn) => {
    if (!wrapper || !btn) return;
    wrapper.classList.add('vc-form-loading');
    btn.setAttribute('aria-busy', 'true');

    // Lock against a second press with pointer-events, NOT `disabled`: a
    // disabled button drops its name/value from the POST and GF needs that to
    // know which button (next/back/submit) fired. Inline !important so no form's
    // button rule can leave it clickable.
    btn.style.setProperty('pointer-events', 'none', 'important');
    btn.style.setProperty('cursor', 'wait', 'important');

    // Reduced motion: no spinner (the safety net would freeze it mid-spin).
    // A dimmed, non-interactive button carries the state instead; the label
    // stays readable and nothing animates.
    if (prefersReducedMotion()) {
        btn.classList.add('vc-btn-loading-static');
        btn.style.setProperty('opacity', '0.6', 'important');
        return;
    }

    btn.classList.add('vc-btn-loading');
    // Hide the label in place. GF/Orbital button colours are !important at high
    // specificity, so a stylesheet rule can't reliably win — an inline
    // !important always does, and keeps the button's box unchanged (no reflow).
    btn.style.setProperty('color', 'transparent', 'important');
    btn.style.setProperty('-webkit-text-fill-color', 'transparent', 'important');
    injectSpinner(btn);
};

// The inline props start-loading sets; cleared here so the button restores fully.
const INLINE_PROPS = ['color', '-webkit-text-fill-color', 'pointer-events', 'cursor', 'opacity'];

const stopLoading = (wrapper) => {
    if (!wrapper) return;
    wrapper.classList.remove('vc-form-loading');
    wrapper.querySelectorAll('.vc-btn-loading, .vc-btn-loading-static').forEach((btn) => {
        btn.classList.remove('vc-btn-loading', 'vc-btn-loading-static');
        btn.removeAttribute('aria-busy');
        INLINE_PROPS.forEach((p) => btn.style.removeProperty(p));
    });
    wrapper.querySelectorAll('.vc-form-loading-anchor').forEach((el) =>
        el.classList.remove('vc-form-loading-anchor'));
    wrapper.querySelectorAll('.vc-form-spinner').forEach((el) => el.remove());
};

const handleSubmit = (data) => {
    const form = data && data.form;
    if (!form || typeof form.closest !== 'function') return;
    const wrapper = form.closest('.gform_wrapper');
    if (!wrapper) return;

    // Prefer the button the user pressed; fall back (keyboard submit, stale
    // node after a re-render) to the last visible, primary action button.
    let btn = lastPressed && form.contains(lastPressed) ? lastPressed : null;
    if (!btn) {
        const candidates = Array.from(form.querySelectorAll('input[type="submit"], button[type="submit"], .gform_next_button'));
        btn = candidates.reverse().find((b) => b.offsetParent !== null) || candidates[0] || null;
    }
    lastPressed = null;
    if (!btn) return;

    startLoading(wrapper, btn);
};

// GF core (loaded because ajax="true") exposes gform.utils. Its load order vs
// this bundle isn't guaranteed, so attach at DOMContentLoaded and re-attempt on
// the first post_render, guarded so the filter never registers twice.
const attachFilter = () => {
    if (filterAttached) return;
    if (!(window.gform && window.gform.utils && typeof window.gform.utils.addFilter === 'function')) return;
    filterAttached = true;

    // Priority 30 runs after steps.js's own callback (20); both just read `data`.
    window.gform.utils.addFilter('gform/submission/pre_submission', (data) => {
        try {
            handleSubmit(data);
        } catch (e) {
            /* never let the spinner block a submit */
        }
        return data;
    }, 30);
};

document.addEventListener('DOMContentLoaded', () => {
    attachFilter();

    // Capture phase so we still see the press even if a handler stops bubbling.
    document.addEventListener('pointerdown', (e) => {
        const btn = isButton(e.target);
        if (btn) lastPressed = btn;
    }, true);

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Enter' && e.key !== ' ' && e.key !== 'Spacebar') return;
        const btn = isButton(e.target);
        if (btn) lastPressed = btn;
    }, true);

    // Abort fires no post_render, so clear any stuck loading state here.
    document.addEventListener('gform/submission/submission_aborted', () => {
        document.querySelectorAll('.gform_wrapper.vc-form-loading').forEach(stopLoading);
    });

    const $ = window.jQuery;
    if (!$) return;

    // GF rebuilds the wrapper's inner markup on every AJAX render (validation
    // errors or the next step), discarding the injected spinner; clear the flag
    // on the persistent wrapper too, and re-attempt the filter attach.
    $(document).on('gform_post_render', (e, formId) => {
        attachFilter();
        stopLoading(document.getElementById(`gform_wrapper_${formId}`));
    });

    $(document).on('gform_confirmation_loaded', (e, formId) => {
        stopLoading(document.getElementById(`gform_wrapper_${formId}`));
    });
});
