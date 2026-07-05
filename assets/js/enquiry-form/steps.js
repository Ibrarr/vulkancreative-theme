import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

// Step transitions, scroll/focus management, loading state and dataLayer
// events for the multi-step enquiry form (the Gravity Form with cssClass
// `enquiry-form`). GF ajax re-renders replace everything inside the persistent
// #gform_wrapper_{id}, so bindings live on document / the persistent panel and
// elements are re-queried per event. The entrance animation must run on
// gform_post_render, never gform_page_loaded: with conditional logic on the
// form GF holds it at opacity:0 until its own post-render pass finishes, so we
// defer two frames behind that. Nothing here is ever pre-hidden: without this
// module (or under reduced motion) the form behaves as stock GF, just instant.
document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.querySelector('.gform_wrapper[class*="enquiry-form_wrapper"]');
    const container = wrapper && wrapper.closest('.form-container');
    if (!wrapper || !container || !window.jQuery) return;

    const formId = parseInt((wrapper.id.match(/gform_wrapper_(\d+)/) || [])[1], 10);
    if (!formId) return;

    const $ = window.jQuery;
    let pendingPage = null;
    let started = false;
    let submitHooksAttached = false;

    // Step announcements only; GF already announces validation errors
    // (assertive) and the confirmation (wp.a11y.speak) itself.
    const live = document.createElement('p');
    live.className = 'visually-hidden';
    live.setAttribute('role', 'status');
    container.appendChild(live);

    const pushEvent = (payload) => {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push(payload);
    };

    const stepInfo = () => {
        const steps = container.querySelectorAll('.gf_page_steps .gf_step');
        const active = container.querySelector('.gf_page_steps .gf_step_active');
        const numberEl = active && active.querySelector('.gf_step_number');
        const labelEl = active && active.querySelector('.gf_step_label');
        return {
            total: steps.length || 4,
            number: numberEl ? parseInt(numberEl.textContent, 10) : null,
            label: labelEl ? labelEl.textContent.trim() : '',
        };
    };

    const visiblePage = () => (
        Array.prototype.find.call(wrapper.querySelectorAll('.gform_page'), (p) => p.offsetParent !== null) || wrapper
    );

    const scrollToForm = () => {
        const header = document.getElementById('header');
        const offset = (header ? header.offsetHeight : 80) + 24;
        const top = container.getBoundingClientRect().top;
        // GF's own post-render scroll usually lands the panel near the top
        // (the header auto-hides on downward scroll), so only correct when the
        // panel is genuinely out of view — competing smooth scrolls read as
        // jank.
        if (top < -8 || top > window.innerHeight * 0.5) {
            window.scrollTo({
                top: top + window.scrollY - offset,
                behavior: prefersReducedMotion() ? 'auto' : 'smooth',
            });
        }
    };

    // The steps bar stays put (its state change is the continuity signal);
    // fields and footer rise in like every other section reveal on the site.
    const animateStep = () => {
        if (prefersReducedMotion()) return;
        const page = visiblePage();
        const fields = Array.prototype.filter.call(
            page.querySelectorAll('.gfield'),
            (el) => el.offsetParent !== null && !el.classList.contains('gform_validation_container'),
        );
        const footer = page.querySelector('.gform_page_footer') || wrapper.querySelector('.gform_footer');
        const targets = footer ? [...fields, footer] : fields;
        if (!targets.length) return;
        gsap.from(targets, {
            y: 24,
            opacity: 0,
            duration: 0.55,
            ease: 'power2.out',
            stagger: 0.06,
            // Stale inline styles would leak into GF's next re-render and
            // conditional-logic toggles; always clear.
            clearProps: 'opacity,transform',
        });
    };

    const focusFirstField = () => {
        // Desktop only: on touch, focusing a text input pops the keyboard over
        // the step the visitor has just landed on.
        if (window.matchMedia('(hover: none)').matches) return;
        const first = visiblePage().querySelector(
            'input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled])',
        );
        if (first) first.focus({ preventScroll: true });
    };

    const markStarted = () => {
        if (started) return;
        started = true;
        const info = stepInfo();
        pushEvent({
            event: 'enquiry_step',
            form_id: formId,
            step: info.number || 1,
            step_name: info.label || 'Services',
        });
    };

    const attachSubmitHooks = () => {
        if (submitHooksAttached) return;
        if (!(window.gform && window.gform.utils && typeof window.gform.utils.addFilter === 'function')) return;
        submitHooksAttached = true;

        // Fires on every Continue/Back/Send click before the iframe POST, so
        // the dim also covers the reCAPTCHA token fetch. Priority 20 runs
        // after GF's own spinner filter.
        window.gform.utils.addFilter('gform/submission/pre_submission', (data) => {
            const formEl = data && data.form;
            if (formEl && formEl.id === `gform_${formId}`) {
                markStarted();
                container.classList.add('is-submitting');
            }
            return data;
        }, 20);

        document.addEventListener('gform/submission/submission_aborted', () => {
            container.classList.remove('is-submitting');
        });
    };

    attachSubmitHooks();
    container.addEventListener('input', markStarted);
    container.addEventListener('change', markStarted);

    $(document).on('gform_page_loaded', (e, id, page) => {
        if (parseInt(id, 10) !== formId) return;
        pendingPage = parseInt(page, 10) || 0;
    });

    $(document).on('gform_post_render', (e, id) => {
        if (parseInt(id, 10) !== formId) return;
        attachSubmitHooks();

        // Two frames so GF's persistent post-render pass (conditional logic
        // applied, wrapper shown, form opacity cleared) has finished first.
        requestAnimationFrame(() => requestAnimationFrame(() => {
            container.classList.remove('is-submitting');

            // Initial load: the page's own reveal owns that moment.
            if (pendingPage === null) return;
            pendingPage = null;

            const hasErrors = !!wrapper.querySelector('.gform_validation_errors, .gfield_error');
            if (hasErrors) {
                // Same page re-rendered with errors: GF focuses and announces
                // the summary itself; adding motion or focus here fights it.
                // GF's own scroll can still tuck the summary under the fixed
                // header (it ignores scroll-margin), so nudge just enough to
                // clear it — measured by header height, since the hide-on-
                // scroll header reappears the moment we scroll up.
                const plate = wrapper.querySelector('.gform_validation_errors');
                const headerEl = document.getElementById('header');
                if (plate) {
                    const clearance = (headerEl ? headerEl.offsetHeight : 70) + 12;
                    const gap = plate.getBoundingClientRect().top - clearance;
                    if (gap < 0) {
                        window.scrollBy({ top: gap, behavior: prefersReducedMotion() ? 'auto' : 'smooth' });
                    }
                }
                return;
            }

            scrollToForm();
            animateStep();
            focusFirstField();

            const info = stepInfo();
            if (info.number) {
                live.textContent = `Step ${info.number} of ${info.total}: ${info.label}`;
                pushEvent({
                    event: 'enquiry_step',
                    form_id: formId,
                    step: info.number,
                    step_name: info.label,
                });
            }
        }));
    });

    $(document).on('gform_confirmation_loaded', (e, id) => {
        if (parseInt(id, 10) !== formId) return;
        container.classList.remove('is-submitting');
        pushEvent({ event: 'enquiry_submit', form_id: formId });

        const confirmation = container.querySelector('.gform_confirmation_wrapper, .gform_confirmation_message');
        if (!confirmation) return;
        confirmation.setAttribute('tabindex', '-1');
        scrollToForm();
        if (!prefersReducedMotion()) {
            gsap.from(confirmation, { y: 24, opacity: 0, duration: 0.55, ease: 'power2.out', clearProps: 'all' });
        }
        confirmation.focus({ preventScroll: true });
    });
});
