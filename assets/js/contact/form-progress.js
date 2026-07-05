// Fills the decorative ember hairline on top of the enquiry form panel as the
// visitor completes required fields. The hairline is state, not motion, so it
// has no reduced-motion gate — the global transition reset just makes each
// update instant. Gravity Forms ajax re-renders replace the whole
// .gform_wrapper, so listeners are delegated on the persistent .form-container
// and the fields are re-queried on every sync (never cached).
document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.contact-main .form-container');
    const fill = container && container.querySelector('.form-progress-fill');
    if (!container || !fill) return;

    const sync = () => {
        const fields = container.querySelectorAll('.gform_wrapper .gfield.gfield_contains_required');
        if (!fields.length) {
            fill.style.transform = 'scaleX(0)';
            return;
        }
        let done = 0;
        fields.forEach((field) => {
            const inputs = Array.from(field.querySelectorAll('input:not([type="hidden"]), textarea, select'));
            if (!inputs.length) return;
            // Checkable groups (checkbox/radio) count complete when any option
            // is picked — a required multi-choice field can never have every
            // box ticked. Text-like inputs must all be filled.
            const checkables = inputs.filter((el) => el.type === 'checkbox' || el.type === 'radio');
            const others = inputs.filter((el) => el.type !== 'checkbox' && el.type !== 'radio');
            const complete = (checkables.length ? checkables.some((el) => el.checked) : true)
                && others.every((el) => el.value.trim() !== '');
            if (complete) done += 1;
        });
        fill.style.transform = `scaleX(${done / fields.length})`;
    };

    container.addEventListener('input', sync);
    container.addEventListener('change', sync);

    // GF's lifecycle hooks are jQuery events; gform_post_render also fires
    // after validation-error re-renders, when persisted values re-fill the bar.
    if (window.jQuery) {
        window.jQuery(document).on('gform_post_render', sync);
        window.jQuery(document).on('gform_confirmation_loaded', () => {
            fill.style.transform = 'scaleX(1)';
        });
    }

    sync();
});
