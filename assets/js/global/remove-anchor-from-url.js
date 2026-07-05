document.addEventListener('DOMContentLoaded', function () {
    // Keep the current page's path when tidying the hash away: hard-coding '/'
    // sent every non-homepage anchor click back to the homepage on refresh.
    const cleanUrl = () => window.location.pathname + window.location.search;
    const scrollBehavior = () =>
        window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth';

    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            const id = link.getAttribute('href').slice(1);   // "why"
            const target = document.getElementById(id);
            if (target) {
                e.preventDefault();                          // stop the hash appearing
                target.scrollIntoView({ behavior: scrollBehavior() });
                history.replaceState(null, '', cleanUrl());  // tidy URL, same page
            }
        });
    });

    if (window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: scrollBehavior() });
                history.replaceState(null, '', cleanUrl());
            }, 10);
        }
    }
});
