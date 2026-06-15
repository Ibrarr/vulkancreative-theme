// Table-of-contents scrollspy: marks the in-view section's link .is-active so
// the reader can see their progress. State, not motion, so it works under
// reduced motion. No-ops where there is no TOC.
document.addEventListener('DOMContentLoaded', () => {
    const toc = document.querySelector('.insight-toc');
    if (!toc) return;

    const links = Array.from(toc.querySelectorAll('a[href^="#"]'));
    if (!links.length) return;

    const entries = links
        .map((link) => {
            const id = decodeURIComponent(link.getAttribute('href').slice(1));
            const target = document.getElementById(id);
            return target ? { link, target } : null;
        })
        .filter(Boolean);
    if (!entries.length) return;

    const OFFSET = 140; // clears the fixed header so the active item flips in step

    let ticking = false;

    const update = () => {
        ticking = false;
        const scrollY = window.scrollY || window.pageYOffset;
        let activeIndex = 0;
        for (let i = 0; i < entries.length; i += 1) {
            const top = entries[i].target.getBoundingClientRect().top + scrollY;
            if (top - OFFSET <= scrollY) activeIndex = i;
            else break;
        }
        entries.forEach((entry, i) => {
            entry.link.classList.toggle('is-active', i === activeIndex);
        });
    };

    const onScroll = () => {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(update);
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
});
