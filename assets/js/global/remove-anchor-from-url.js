document.addEventListener('DOMContentLoaded', function () {
    // Keep the current page's path when tidying the hash away: hard-coding '/'
    // sent every non-homepage anchor click back to the homepage on refresh.
    const cleanUrl = () => window.location.pathname + window.location.search;
    const scrollBehavior = () =>
        window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth';

    // Cancelling the jump also cancels the browser's focus move, which left
    // keyboard and screen-reader users on the link they had just followed
    // (the skip link included). Hand focus to the target ourselves; sections
    // are not focusable, so they take tabindex -1 for the purpose.
    const moveFocus = (target) => {
        if (!target.hasAttribute('tabindex') && !/^(A|BUTTON|INPUT|SELECT|TEXTAREA)$/.test(target.tagName)) {
            target.setAttribute('tabindex', '-1');
        }
        target.focus({ preventScroll: true });
    };

    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            const id = link.getAttribute('href').slice(1);   // "why"
            const target = document.getElementById(id);
            if (target) {
                e.preventDefault();                          // stop the hash appearing
                target.scrollIntoView({ behavior: scrollBehavior() });
                moveFocus(target);
                history.replaceState(null, '', cleanUrl());  // tidy URL, same page
            }
        });
    });

    if (window.location.hash) {
        // getElementById, not querySelector: a hash such as #1 or #/path is not a
        // valid CSS selector and threw an uncaught DOMException on load.
        let target = null;
        try {
            target = document.getElementById(decodeURIComponent(window.location.hash.slice(1)));
        } catch (e) {
            target = null;
        }
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: scrollBehavior() });
                history.replaceState(null, '', cleanUrl());
            }, 10);
        }
    }
});
