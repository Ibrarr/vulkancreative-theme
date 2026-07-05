// Mobile sticky CTA: visible only while neither the hero nor the enquiry
// section is on screen, so it never covers the form or duplicates the hero
// CTA. Without IntersectionObserver the bar simply stays hidden. Also pushes
// the enquiry submit event to the dataLayer (GF fires the confirmation event
// through jQuery).
document.addEventListener('DOMContentLoaded', () => {
    const bar = document.querySelector('.fw-sticky-cta');
    const hero = document.querySelector('.fw-hero');
    const enquire = document.getElementById('enquire');

    if (bar && hero && enquire && 'IntersectionObserver' in window) {
        let heroVisible = true;
        let enquireVisible = false;

        const update = () => {
            bar.classList.toggle('is-visible', !heroVisible && !enquireVisible);
        };

        new IntersectionObserver((entries) => {
            entries.forEach((entry) => { heroVisible = entry.isIntersecting; });
            update();
        }, { threshold: 0.05 }).observe(hero);

        new IntersectionObserver((entries) => {
            entries.forEach((entry) => { enquireVisible = entry.isIntersecting; });
            update();
        }, { threshold: 0.05 }).observe(enquire);
    }
});

if (window.jQuery) {
    window.jQuery(document).on('gform_confirmation_loaded', function (event, formId) {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'free_website_submit',
            formId: parseInt(formId, 10) || formId,
        });
    });
}
