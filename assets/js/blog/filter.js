// Insights filter — AJAX progressive enhancement.
//
// The bar (template-parts/insights-filter.php) works with no JS: the search is
// a real form to the search page and the category links point at the category
// archives. With JS we intercept both, fetch filtered results from the
// vc/v1/insights REST endpoint, swap the grid in place and keep the URL in
// sync (shareable, back/forward works). The category picker is a searchable
// dropdown on the right; the search has a custom clear button.
document.addEventListener('DOMContentLoaded', () => {
    const filter = document.querySelector('[data-insights-filter]');
    const grid = document.querySelector('[data-insights-grid]');
    const pagination = document.querySelector('[data-insights-pagination]');
    const cfg = window.vcInsights;

    if (!filter || !grid || !cfg || !cfg.rest) return;

    const form = filter.querySelector('.insights-filter-search');
    const input = filter.querySelector('.insights-filter-input');
    const clearBtn = filter.querySelector('.insights-filter-clear');
    const dropdown = filter.querySelector('[data-insights-dropdown]');
    const toggle = filter.querySelector('.insights-filter-toggle');
    const panel = filter.querySelector('.insights-filter-panel');
    const catSearch = filter.querySelector('.insights-filter-cat-search');
    const currentLabel = filter.querySelector('.insights-filter-current');
    const links = Array.from(filter.querySelectorAll('.insights-filter-link'));
    const catItems = Array.from(filter.querySelectorAll('.insights-filter-item'));
    const catEmpty = filter.querySelector('.insights-filter-empty');
    const status = filter.querySelector('.insights-filter-status');

    const activeLink = filter.querySelector('.insights-filter-link.is-active');
    let state = {
        category: activeLink ? parseInt(activeLink.dataset.category, 10) || 0 : 0,
        search: input ? input.value.trim() : '',
        paged: 1,
    };

    try { history.replaceState({ vcInsights: true, ...state }, '', location.href); } catch (e) {}

    /* ---- shared helpers ---- */

    const setBusy = (busy) => {
        grid.classList.toggle('is-loading', busy);
        grid.setAttribute('aria-busy', busy ? 'true' : 'false');
    };

    const announce = (found) => {
        if (status) status.textContent = found === 1 ? '1 article' : `${found} articles`;
    };

    const linkFor = (category) => links.find((l) => (parseInt(l.dataset.category, 10) || 0) === category);

    const syncControls = (next) => {
        if (input && input.value.trim() !== next.search) input.value = next.search;
        if (clearBtn) clearBtn.hidden = next.search === '';

        links.forEach((l) => {
            const on = (parseInt(l.dataset.category, 10) || 0) === next.category;
            l.classList.toggle('is-active', on);
            if (on) l.setAttribute('aria-current', 'page');
            else l.removeAttribute('aria-current');
        });

        if (currentLabel) {
            const link = linkFor(next.category);
            currentLabel.textContent = link ? link.textContent.trim() : 'All categories';
        }
    };

    const buildPagination = (maxPages, current) => {
        if (!pagination || maxPages <= 1) {
            if (pagination) pagination.innerHTML = '';
            return;
        }
        const parts = [];
        const item = (paged, label, cls) =>
            `<a class="page-numbers${cls ? ' ' + cls : ''}" href="#" data-paged="${paged}">${label}</a>`;

        if (current > 1) parts.push(item(current - 1, '<span aria-hidden="true">&lsaquo;</span><span class="visually-hidden">Previous page</span>', 'prev'));

        for (let i = 1; i <= maxPages; i += 1) {
            if (i === 1 || i === maxPages || (i >= current - 1 && i <= current + 1)) {
                if (i === current) parts.push(`<span class="page-numbers current" aria-current="page">${i}</span>`);
                else parts.push(item(i, i));
            } else if (i === current - 2 || i === current + 2) {
                parts.push('<span class="page-numbers dots">&hellip;</span>');
            }
        }

        if (current < maxPages) parts.push(item(current + 1, '<span aria-hidden="true">&rsaquo;</span><span class="visually-hidden">Next page</span>', 'next'));

        pagination.innerHTML = parts.join('');
    };

    const urlFor = (next) => {
        if (next.search) return `${location.origin}/?s=${encodeURIComponent(next.search)}`;
        const link = linkFor(next.category);
        if (next.category && link) return link.href;
        return cfg.blogUrl;
    };

    let reqId = 0;

    const load = (next, { push = true, scroll = false } = {}) => {
        state = next;
        const mine = ++reqId;
        setBusy(true);
        syncControls(next);

        const params = new URLSearchParams({ paged: String(next.paged) });
        if (next.category) params.set('category', String(next.category));
        if (next.search) params.set('search', next.search);

        fetch(`${cfg.rest}?${params.toString()}`, { headers: { Accept: 'application/json' } })
            .then((r) => r.json())
            .then((data) => {
                if (mine !== reqId) return;
                grid.innerHTML = data.cards || '';
                buildPagination(data.maxPages || 0, data.paged || 1);
                announce(data.found || 0);
                if (push) {
                    try { history.pushState({ vcInsights: true, ...next }, '', urlFor(next)); } catch (e) {}
                }
                if (scroll) {
                    const top = grid.getBoundingClientRect().top + window.scrollY - 120;
                    window.scrollTo({ top, behavior: 'auto' });
                }
            })
            .catch(() => {})
            .finally(() => { if (mine === reqId) setBusy(false); });
    };

    /* ---- category dropdown ---- */

    const openDropdown = () => {
        if (!panel) return;
        panel.hidden = false;
        toggle.setAttribute('aria-expanded', 'true');
        if (catSearch) {
            catSearch.value = '';
            filterCatList('');
            window.requestAnimationFrame(() => catSearch.focus());
        }
    };

    const closeDropdown = ({ focusToggle = false } = {}) => {
        if (!panel) return;
        panel.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
        if (focusToggle) toggle.focus();
    };

    const dropdownOpen = () => toggle && toggle.getAttribute('aria-expanded') === 'true';

    const filterCatList = (query) => {
        const q = query.trim().toLowerCase();
        let visible = 0;
        catItems.forEach((item) => {
            const match = !q || item.textContent.trim().toLowerCase().includes(q);
            item.hidden = !match;
            if (match) visible += 1;
        });
        if (catEmpty) catEmpty.hidden = visible > 0;
    };

    if (toggle) {
        toggle.addEventListener('click', () => { if (dropdownOpen()) closeDropdown(); else openDropdown(); });
    }
    if (catSearch) {
        catSearch.addEventListener('input', () => filterCatList(catSearch.value));
        catSearch.addEventListener('keydown', (e) => {
            if (e.key !== 'Enter') return;
            const first = catItems.find((i) => !i.hidden && i.querySelector('.insights-filter-link'));
            const link = first && first.querySelector('.insights-filter-link');
            if (link) { e.preventDefault(); link.click(); }
        });
    }

    links.forEach((link) => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const category = parseInt(link.dataset.category, 10) || 0;
            closeDropdown();
            load({ category, search: '', paged: 1 });
        });
    });

    /* ---- article search ---- */

    let debounce;
    if (input) {
        input.addEventListener('input', () => {
            if (clearBtn) clearBtn.hidden = input.value.trim() === '';
            window.clearTimeout(debounce);
            debounce = window.setTimeout(() => {
                load({ category: 0, search: input.value.trim(), paged: 1 });
            }, 350);
        });
    }
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            if (input) input.value = '';
            clearBtn.hidden = true;
            window.clearTimeout(debounce);
            load({ category: 0, search: '', paged: 1 });
            if (input) input.focus();
        });
    }
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            window.clearTimeout(debounce);
            load({ category: 0, search: input ? input.value.trim() : '', paged: 1 });
        });
    }

    /* ---- pagination ---- */

    if (pagination) {
        pagination.addEventListener('click', (e) => {
            const link = e.target.closest('a.page-numbers');
            if (!link) return;
            e.preventDefault();
            let paged = parseInt(link.dataset.paged, 10);
            if (!paged) {
                const m = link.getAttribute('href').match(/\/page\/(\d+)|[?&]paged=(\d+)/);
                paged = m ? parseInt(m[1] || m[2], 10) : 1;
            }
            load({ ...state, paged }, { push: false, scroll: true });
        });
    }

    /* ---- close-on-outside / Escape ---- */

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && dropdownOpen()) closeDropdown({ focusToggle: true });
    });
    document.addEventListener('click', (e) => {
        if (dropdownOpen() && dropdown && !dropdown.contains(e.target)) closeDropdown();
    });

    /* ---- back/forward ---- */

    window.addEventListener('popstate', (e) => {
        if (e.state && e.state.vcInsights) {
            load({ category: e.state.category || 0, search: e.state.search || '', paged: e.state.paged || 1 }, { push: false });
        }
    });
});
