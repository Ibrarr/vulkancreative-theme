// "What We Do" services mega menu. Desktop bar: the panel opens via CSS
// (li:hover on hover-capable devices, li:focus-within for keyboards); this
// module only keeps aria-expanded truthful and closes the panel on Escape.
// Mobile overlay: a two-level accordion — "What We Do" discloses the pillars,
// and each pillar discloses its child services. Each level gets a 44px
// disclosure chevron and its sub-list wrapped in a single grid child
// (.submenu-wrap) so the accordion animates grid-template-rows. Without JS
// there are no wrappers and every list is simply visible, so nothing is ever
// unreachable.
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

    // ----- Mobile overlay accordion (two levels) -----
    const CHEVRON = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"/></svg>';

    // Wrap a sub-list in a single grid child (.submenu-wrap) and inject a 44px
    // disclosure button after `afterEl`, toggling `is-open` on `item`.
    const addDisclosure = (item, afterEl, list, label) => {
        const wrap = document.createElement('div');
        wrap.className = 'submenu-wrap';
        list.parentNode.insertBefore(wrap, list);
        wrap.appendChild(list);

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'submenu-toggle';
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', label);
        toggle.innerHTML = CHEVRON;
        afterEl.after(toggle);

        item.classList.add('has-disclosure');

        toggle.addEventListener('click', () => {
            const open = item.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    };

    // Level 0: top-level parents ("What We Do" -> the mega panel, and any other
    // parent item that carries a sub-menu).
    document.querySelectorAll('.mobile-menu nav li.menu-item-has-children').forEach((item) => {
        const link = item.querySelector(':scope > a');
        const submenu = item.querySelector(':scope > .sub-menu');
        if (!link || !submenu) return;
        addDisclosure(item, link, submenu, 'Show ' + link.textContent.trim() + ' menu');
    });

    // Level 1: each pillar column inside the mega panel discloses its children.
    document.querySelectorAll('.mobile-menu .sub-menu--mega > li.mega-col').forEach((col) => {
        const head = col.querySelector(':scope > .mega-col-head');
        const list = col.querySelector(':scope > .mega-col-list');
        if (!head || !list) return;
        addDisclosure(col, head, list, 'Show ' + head.textContent.trim() + ' services');
    });
});
