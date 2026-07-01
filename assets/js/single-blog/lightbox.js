// Lightweight, dependency-free lightbox for blog content images.
// Click an image inside `.content` to open it enlarged; close with Esc,
// the close button, or a click on the backdrop.

(function () {
    let overlay = null;

    function close() {
        if (!overlay) return;
        const el = overlay;
        overlay = null;
        el.classList.remove('is-open');
        document.body.classList.remove('no-scroll', 'lightbox-open');
        document.removeEventListener('keydown', onKeydown);

        const remove = () => el.remove();
        el.addEventListener('transitionend', remove, { once: true });
        // Fallback in case the transition never fires.
        setTimeout(remove, 400);
    }

    function onKeydown(e) {
        if (e.key === 'Escape') close();
    }

    function open(src, alt) {
        if (overlay) return; // never stack two overlays

        overlay = document.createElement('div');
        overlay.className = 'vc-lightbox';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.setAttribute('aria-label', alt || 'Image preview');

        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'vc-lightbox__close';
        closeBtn.setAttribute('aria-label', 'Close');
        closeBtn.innerHTML = '&times;';

        const img = document.createElement('img');
        img.src = src;
        if (alt) img.alt = alt;

        overlay.appendChild(closeBtn);
        overlay.appendChild(img);
        document.body.appendChild(overlay);
        document.body.classList.add('no-scroll', 'lightbox-open');
        document.addEventListener('keydown', onKeydown);

        // Clicking anywhere in the overlay (image, backdrop or the X) closes it,
        // which matches the zoom-out "minimise" cursor shown across the overlay.
        overlay.addEventListener('click', close);

        // Trigger the fade-in on the next frame.
        requestAnimationFrame(function () {
            if (overlay) overlay.classList.add('is-open');
        });
    }

    // Event delegation so images revealed by the GSAP intro are covered too.
    document.addEventListener('click', function (e) {
        const img = e.target.closest('.content img');
        if (!img) return;
        open(img.currentSrc || img.src, img.alt);
    });
})();
