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
    let submenuId = 0;
    document.querySelectorAll('.menu-theme-toggle nav li.menu-item-has-children').forEach((item) => {
        const link = item.querySelector(':scope > a');
        if (!link) return;

        link.setAttribute('aria-haspopup', 'true');
        link.setAttribute('aria-expanded', 'false');

        // aria-expanded needs a target: stamp the panel and point at it.
        const panel = item.querySelector(':scope > .sub-menu');
        if (panel) {
            if (!panel.id) panel.id = 'vc-submenu-' + (++submenuId);
            link.setAttribute('aria-controls', panel.id);
        }

        const setExpanded = (open) => link.setAttribute('aria-expanded', open ? 'true' : 'false');
        const hoverable = window.matchMedia('(hover: hover)');

        // A tap on touch fires mouseenter with no matching mouseleave and then
        // navigates, so only hover-capable devices report the hover-open state.
        item.addEventListener('mouseenter', () => { if (hoverable.matches) setExpanded(true); });
        item.addEventListener('mouseleave', () => setExpanded(false));
        item.addEventListener('focusin', () => setExpanded(true));
        item.addEventListener('focusout', () => {
            if (!item.contains(document.activeElement)) setExpanded(false);
        });

        // Escape closes the panel even while focus sits on the parent link:
        // :focus-within keeps it open, so is-escaped (see _desktop.scss)
        // forces it shut until the pointer leaves or focus moves on.
        item.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            if (!item.contains(document.activeElement)) return;
            e.stopPropagation();
            item.classList.add('is-escaped');
            setExpanded(false);
            link.focus();
        });
        item.addEventListener('mouseleave', () => item.classList.remove('is-escaped'));
        link.addEventListener('blur', () => item.classList.remove('is-escaped'));
    });

    // ----- Mobile overlay accordion (two levels) -----
    const CHEVRON = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"/></svg>';

    // Wrap a sub-list in a single grid child (.submenu-wrap) and inject a 44px
    // disclosure button after `afterEl`, toggling `is-open` on `item`.
    let wrapId = 0;
    const addDisclosure = (item, afterEl, list, label) => {
        const wrap = document.createElement('div');
        wrap.className = 'submenu-wrap';
        wrap.id = 'vc-subwrap-' + (++wrapId);
        list.parentNode.insertBefore(wrap, list);
        wrap.appendChild(list);

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'submenu-toggle';
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-controls', wrap.id);
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
