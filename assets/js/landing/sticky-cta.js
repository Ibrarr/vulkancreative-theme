// Mobile sticky CTA: visible only while neither the hero nor the CTA anchor is
// on screen, so it never covers the form or duplicates the hero CTA. Without a
// hero or a CTA anchor, or without IntersectionObserver, it stays hidden. Also
// pushes the form submit to the dataLayer (GF fires the confirmation via jQuery).
document.addEventListener('DOMContentLoaded', () => {
    const bar = document.querySelector('.lp-sticky-cta');
    const hero = document.querySelector('.lp-hero');
    const cta = document.getElementById('lp-cta');

    if (bar && hero && cta && 'IntersectionObserver' in window) {
        let heroVisible = true;
        let ctaVisible = false;

        const update = () => {
            bar.classList.toggle('is-visible', !heroVisible && !ctaVisible);
        };

        new IntersectionObserver((entries) => {
            entries.forEach((entry) => { heroVisible = entry.isIntersecting; });
            update();
        }, { threshold: 0.05 }).observe(hero);

        new IntersectionObserver((entries) => {
            entries.forEach((entry) => { ctaVisible = entry.isIntersecting; });
            update();
        }, { threshold: 0.05 }).observe(cta);
    }
});

if (window.jQuery) {
    window.jQuery(document).on('gform_confirmation_loaded', function (event, formId) {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'landing_submit',
            formId: parseInt(formId, 10) || formId,
        });
    });
}
