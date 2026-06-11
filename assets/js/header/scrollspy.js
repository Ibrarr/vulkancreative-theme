// Highlights the nav item for the section currently in view (one-page nav).
// Deterministic: the active section is the last anchor target whose top sits
// above the upper-middle of the viewport. No-ops on pages whose nav has no
// same-page anchor targets.
document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('header nav a[href^="#"]');
    if (!navLinks.length) {
        return;
    }

    const entries = []; // { section, lis: [<li>, ...] }
    const byId = new Map();

    navLinks.forEach((link) => {
        const id = link.getAttribute('href');
        if (id === '#') {
            return;
        }
        const section = document.querySelector(id);
        if (!section) {
            return;
        }
        const li = link.closest('li');
        if (!li || li.classList.contains('menu-button')) {
            return;
        }
        if (!byId.has(id)) {
            byId.set(id, { section, lis: [] });
            entries.push(byId.get(id));
        }
        byId.get(id).lis.push(li);
    });

    if (!entries.length) {
        return;
    }

    let current = null;
    let ticking = false;

    const update = () => {
        ticking = false;
        const probe = window.pageYOffset + window.innerHeight * 0.4;

        // The current section is the one furthest down the page whose top has
        // passed the probe line (menu order and page order can differ).
        let active = null;
        let activeTop = -Infinity;
        entries.forEach((entry) => {
            const top = entry.section.getBoundingClientRect().top + window.pageYOffset;
            if (top <= probe && top > activeTop) {
                active = entry;
                activeTop = top;
            }
        });

        if (active === current) {
            return;
        }
        current = active;
        entries.forEach((entry) => {
            entry.lis.forEach((li) => li.classList.toggle('current-anchor', entry === active));
        });
    };

    const onScroll = () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(update);
        }
    };

    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
});
