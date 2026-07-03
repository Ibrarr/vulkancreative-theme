// "What We Do" services dropdown. Desktop bar: the panel itself opens via CSS
// (li:hover on hover-capable devices, li:focus-within for keyboards); this
// module only keeps aria-expanded truthful and closes the panel on Escape.
// Mobile overlay: injects a 44px disclosure chevron beside the parent link
// and wraps the sub-list in a single grid child (.submenu-wrap) so the
// accordion animates grid-template-rows; without JS the sub-list is simply
// visible, so nothing is ever unreachable.
document.addEventListener('DOMContentLoaded', () => {
    // ----- Desktop bar -----
    document.querySelectorAll('.menu-theme-toggle nav li.menu-item-has-children').forEach((item) => {
        const link = item.querySelector(':scope > a');
        if (!link) return;

        link.setAttribute('aria-haspopup', 'true');
        link.setAttribute('aria-expanded', 'false');

        const setExpanded = (open) => link.setAttribute('aria-expanded', open ? 'true' : 'false');

        item.addEventListener('mouseenter', () => setExpanded(true));
        item.addEventListener('mouseleave', () => setExpanded(false));
        item.addEventListener('focusin', () => setExpanded(true));
        item.addEventListener('focusout', () => {
            if (!item.contains(document.activeElement)) setExpanded(false);
        });

        // Escape closes the panel and hands focus back to the parent link.
        item.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            if (item.contains(document.activeElement) && document.activeElement !== link) {
                e.stopPropagation();
                link.focus();
            }
        });
    });

    // ----- Mobile overlay accordion -----
    document.querySelectorAll('.mobile-menu nav li.menu-item-has-children').forEach((item) => {
        const link = item.querySelector(':scope > a');
        const submenu = item.querySelector(':scope > .sub-menu');
        if (!link || !submenu) return;

        const wrap = document.createElement('div');
        wrap.className = 'submenu-wrap';
        submenu.parentNode.insertBefore(wrap, submenu);
        wrap.appendChild(submenu);

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'submenu-toggle';
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Show ' + link.textContent.trim() + ' services');
        toggle.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"/></svg>';
        link.after(toggle);

        item.classList.add('has-disclosure');

        toggle.addEventListener('click', () => {
            const open = item.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    });
});
