import '@splinetool/viewer';

document.addEventListener('DOMContentLoaded', () => {
    // Only proceed if viewport width is >= 991px
    if (window.innerWidth >= 991) {
        const container = document.querySelector('.hero .graphic');
        if (!container) return;

        // Create the <spline-viewer> element
        const splineEl = document.createElement('spline-viewer');
        splineEl.setAttribute('loading-anim-type', 'spinner-big-dark');
        splineEl.setAttribute(
            'url',
            `${window.location.origin}/wp-content/themes/vulkancreative-theme/assets/spline/scene.splinecode`
    );

        container.appendChild(splineEl);
    }
});