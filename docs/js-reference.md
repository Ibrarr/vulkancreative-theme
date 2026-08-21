# JS reference

Full JS architecture: folder structure, bundle map, GSAP setup, animation pattern catalogue and per-module notes. Moved out of the main CLAUDE.md on 2026-07-03 as a long lookup catalogue: see CLAUDE.md's "JS Architecture" section for the condensed version (reduced-motion rule, IntersectionObserver-first preference, the one pattern most new sections need) that stays loaded by default.

## Folder structure

```
assets/js/
├── global/                     # Always loaded
│   ├── dark-mode.js
│   ├── load-at-top.js
│   ├── smooth-scrolling.js     # Currently commented out
│   └── remove-anchor-from-url.js
├── header/                     # Always loaded
│   ├── header.js
│   ├── mobile-menu.js
│   └── scrollspy.js
├── footer/                     # Always loaded
│   └── footer.js
├── homepage/                   # Front page only (testimonials.js and marquee.js also ship in the about bundle)
│   ├── hero.js
│   ├── marquee.js
│   ├── why.js
│   ├── services.js
│   ├── work.js
│   ├── our-work.js
│   ├── process.js
│   ├── testimonials.js
│   ├── reveal.js
│   ├── counter.js
│   └── contact.js
├── spline/                     # Part of homepage bundle
│   └── spline-viewer.js
├── about/                      # About template only
│   ├── reveal.js
│   ├── founders.js
│   ├── story.js
│   ├── values.js
│   └── how.js
├── contact/                    # Contact template only
│   ├── reveal.js
│   ├── next-steps.js
│   └── form-progress.js
├── your-business/              # Your-business template only
│   ├── hero.js
│   ├── logo-bar.js
│   ├── problem.js
│   ├── solution.js
│   ├── outcomes.js
│   ├── testimonials.js
│   └── cta.js
├── blog/                       # Insights modules (feed the single-blog, archive-blog and archive-author bundles)
│   ├── reveal.js
│   ├── filter.js
│   └── toc.js
├── single-blog/                # Legacy loading.js, no longer bundled
│   └── loading.js
├── archive-blog/                # Legacy loading.js, no longer bundled
│   └── loading.js
├── archive-author/             # Legacy loading.js, no longer bundled
│   └── loading.js
└── components/                 # Shared utility modules
    └── reduced-motion.js       # prefersReducedMotion() helper
```

## Bundles (`webpack.mix.js`)

10 bundles, each concatenating source files:

| Bundle | Source files | Condition |
|---|---|---|
| `global.js` | dark-mode, load-at-top, smooth-scrolling, remove-anchor-from-url | Always |
| `header.js` | header, mobile-menu, scrollspy | Always |
| `footer.js` | footer | Always |
| `homepage.js` | spline-viewer, hero, marquee, why, services, work, our-work, process, testimonials, reveal, counter, contact | `is_front_page()` |
| `single-blog.js` | blog/reveal, blog/filter, blog/toc | `is_single()` |
| `archive-blog.js` | blog/reveal, blog/filter, blog/toc | `is_home() \|\| is_category()` |
| `archive-author.js` | blog/reveal, blog/filter, blog/toc | `is_author()` |
| `your-business.js` | hero, logo-bar, problem, solution, outcomes, testimonials, cta | `is_page_template('page-templates/page-your-business.php')` |
| `contact.js` | reveal, next-steps, form-progress | `is_page_template('page-templates/page-contact-us.php')` |
| `about.js` | about/reveal, about/founders, about/story, about/values, about/how, plus the shared homepage/testimonials.js and homepage/marquee.js (they bind by element id; the blog bundles set the same multi-bundle precedent) | `is_page_template('page-templates/page-about-us.php')` |

## GSAP setup

GSAP v3.12.5 is imported as an npm module. Plugins used:

| Plugin | Registered in | Purpose |
|---|---|---|
| `ScrollTrigger` | Homepage hero, services, process, contact; the About founders, story, values and how modules; the your-business modules | Scroll-based animation triggers |
| `SplitText` | Homepage hero, reveal, contact; About reveal and story; the contact and blog reveals; the your-business modules | Text line/word splitting for reveal animations |
| `DrawSVGPlugin` | `header/header.js` | SVG path draw animation for the logo |

The newer homepage modules (`reveal.js`, `work.js`, `our-work.js`, `why.js`, `counter.js`) deliberately use `IntersectionObserver` instead of ScrollTrigger, so content can never be left stuck hidden under fast scrolling. Every animation module branches on the reduced-motion query, via the shared `prefersReducedMotion()` helper from `assets/js/components/reduced-motion.js`, or a `gsap.matchMedia('(prefers-reduced-motion: no-preference)')` context in the About founders/values/how modules, and falls back to a static, fully visible state. Aug 2026 trust-signals additions ride the existing lists with no new modules or bundles: `homepage/reveal.js` fades gained `.why .partner-logos`, and `about/reveal.js` gained `.about-press .content h2` (headings) plus `.about-proof .partner-logos` and `.about-press .press-body` (fades).

Registration pattern:

```js
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);
```

## Common animation patterns

**1. Scroll-triggered fade-up (most common)**

```js
gsap.from('.selector', {
    opacity: 0,
    y: 50,
    duration: 0.6,
    ease: 'power2.out',
    scrollTrigger: {
        trigger: '.selector',
        start: 'top 95%',
        toggleActions: 'play none none none',
        once: true,
    },
});
```

**2. Staggered group animation**

```js
gsap.from('.items .item', {
    opacity: 0,
    y: 30,
    duration: 0.8,
    stagger: 0.2,
    ease: 'power2.out',
    scrollTrigger: { trigger: '.items', start: 'top 90%', once: true },
});
```

**3. Split text reveal**

```js
SplitText.create('.split-text-section', {
    type: 'words,lines',
    linesClass: 'line',
    mask: 'lines',
    autoSplit: true,
    onSplit: (self) => {
        gsap.from(self.lines, {
            yPercent: 100,
            opacity: 0,
            duration: 0.8,
            stagger: 0.1,
            delay: 0.15,
            ease: 'expo.out',
            scrollTrigger: { trigger: '.split-text-section', start: 'top 95%', once: true },
        });
    },
});
```

**4. Dual trigger pattern (DOM + fonts ready)**

Used in hero sections to prevent flash of unstyled animations:

```js
let domReady = false;
let fontsReady = false;

document.addEventListener('DOMContentLoaded', () => {
    domReady = true;
    if (fontsReady) initAnimations();
});

document.fonts.ready.then(() => {
    fontsReady = true;
    if (domReady) initAnimations();
});
```

Paired with an injected `<style>` that sets `opacity: 0 !important` on animated elements until animations initialise. The injected style is skipped under reduced motion, so nothing is ever hidden.

**5. IntersectionObserver reveal (preferred for new sections)**

```js
import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion()) return;

    const targets = document.querySelectorAll('.section .content');
    if (!targets.length || !('IntersectionObserver' in window)) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            gsap.fromTo(entry.target,
                { opacity: 0, y: 24 },
                { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }
            );
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -5% 0px' });

    targets.forEach((el) => observer.observe(el));
});
```

Nothing is pre-hidden, so content cannot be left invisible if a script fails. Used by `reveal.js`, `work.js`, `our-work.js`, `why.js` and `counter.js`.

## Shared components

**`reduced-motion.js`** -- Exports `prefersReducedMotion()`, a `matchMedia('(prefers-reduced-motion: reduce)')` check. Every animation module calls it and falls back to a static, fully visible state. Paired with the CSS safety net in `misc/_motion.scss`. (The previous shared modules, `firework-button-effect.js` and `shadow-cursor.js`, were removed along with the custom cursor.)

## Other JS modules

- **`load-at-top.js`**: Sets `history.scrollRestoration = 'manual'`.
- **`remove-anchor-from-url.js`**: Intercepts `#` links, smooth scrolls to target, removes hash from URL via `history.replaceState()`.
- **`header/header.js`**: Class-based fixed header states: `header--scrolled` (gains a surface after 24px) and `header--hidden` (hides on scroll down past 160px, reappears on scroll up). Static on the your-business template. Also runs the DrawSVG logo draw animation, with a reduced-motion fallback that shows the finished logo immediately.
- **`header/scrollspy.js`**: One-page nav highlight. Marks the nav `<li>` whose anchor target is in view with `.current-anchor` (last section whose top sits above 40% of the viewport, rAF-throttled). No-ops on pages whose nav has no same-page anchors.
- **`header/mobile-menu.js`**: Full-screen overlay menu (jQuery). Toggles `mobile-menu-active` on the header and `no-scroll` on the body, updates `aria-expanded`/`aria-label`, moves focus into the menu once the overlay transition finishes, closes on Escape, traps Tab focus while open, and closes then animates scroll for in-menu anchor links.
- **`footer/footer.js`**: Empty stub. The custom cursor and mouse-follow glow were removed; hover/focus states live in CSS.
- **`spline/spline-viewer.js`**: Prepends the poster (`assets/images/hero/statue-poster.webp`) into `.hero .graphic` as an instant visual. Then, only on viewports >= 992px without reduced motion, lazy-loads `@splinetool/viewer` via dynamic `import()` (code-split chunk; `__webpack_public_path__` is set from the inline `window.__vc_public_path`) once fonts are ready, on `requestIdleCallback`, and appends a `<spline-viewer>` for `assets/spline/scene.splinecode`. On mobile and under reduced motion the poster stays as a faint backdrop (`.graphic` at 0.28 opacity, masked).
- **Splide carousels**: homepage `marquee.js` (hero logo marquee `#logo-splide`: loop, free drag, AutoScroll at speed 0.8, 6/4/3 logos per page; static but draggable under reduced motion), homepage `testimonials.js` (`#testimonial-splide`), plus the your-business `logo-bar.js` and `testimonials.js`. The homepage `marquee.js` and `testimonials.js` are also concatenated into the about bundle: they bind by element id, and the About proof section renders the same `#logo-splide` and `#testimonial-splide` ids.
- **`homepage/our-work.js`**: The work wheel, a fully custom GSAP arc carousel (no Splide). One `progress` value drives everything: each card's transform derives from its angle on a large wheel (`x = sin·R`, `y = (1−cos)·R`, tangent rotation damped), so drag, momentum, arrow steps and the fan-open entrance are all tweens of `progress` (or `spread` for the entrance). It starts on the middle card (the template orders cards centre-out) and toggles `is-front` on whichever card holds the centre slot; the hover layers themselves are pure CSS. The drag input pipeline is smoothed: pointer moves only record a target and a rAF loop eases progress toward it (input-rate jitter never reaches the cards), the wheel does not move at all below the 6px threshold (clicks stay still), the start is re-based at the threshold so crossing it never jumps, a mostly-vertical touch gesture hands back to page scrolling, and the release velocity is an exponential moving average so equal flicks throw equally. Gotchas baked in: pointer capture is taken only after the 6px drag threshold (capturing on pointerdown makes the browser retarget the subsequent click to the stage, killing card links); a real drag suppresses the click behind it (capture-phase listener) and blurs any focused card; the spin range clamps to [1, count−2] so the stage never shows a dead half; far cards get `inert` + `aria-hidden`; `touch-action: pan-y` keeps page scroll alive on touch. Reduced motion keeps the wheel fully working with direct pointer tracking, instant steps and no entrance.
- **`homepage/counter.js`**: Counts the `.results` stat numbers up from zero on first view (requestAnimationFrame + IntersectionObserver; keeps prefixes, suffixes and decimals; instant under reduced motion).
- **Video.js**: `about/story.js` initialises a Video.js player on element `#our-story` (city theme; the videojs-youtube plugin is bundled), lazily via IntersectionObserver just before the story section scrolls into view. It ships in the about bundle only; the homepage dropped story.js and Video.js when the story section moved to the About page.


## Work bundle (July 2026)

`js/project.js` (enqueued on `is_post_type_archive('project') || is_singular('project')`) = `assets/js/project/reveal.js` (the services-hub reveal pattern with the work/project selector lists) + `assets/js/project/filter.js` (archive chip filtering: real-link progressive enhancement, pushState/popstate sync, aria-live counts, the mobile dropdown toggle, and the 7/5 pattern maths mirrored from `archive-project.php`) + the shared `homepage/our-work.js` for the related wheel. The single-blog bundle is gated on `is_singular('post')` so it never leaks onto project singles. Full notes in `docs/work-system.md`.

## Fonts gate on the reveal pattern (July 2026)

Every shared reveal module (`homepage`/`blog`/`contact`/`about`/`services-hub`/`service`/`project`/`case-study` reveal.js) starts observing only inside `document.fonts.ready.then(...)`: SplitText must measure the settled metrics (Archivo's width stretch changes line breaks), or a heading reached before fonts activate animates with fallback-font wrapping and visibly rewraps when the reveal reverts. Pre-hiding still happens at DOMContentLoaded so nothing flashes; `fonts.ready` resolves immediately on a warm cache. The bespoke split modules (`homepage/hero.js`, `homepage/contact.js`, `about/story.js`) already carried the same gate. Give any NEW SplitText module the same treatment.

## Case study bundle (July 2026)

`js/case-study.js` (enqueued on `is_post_type_archive('case_study') || is_singular('case_study')`) = `assets/js/case-study/reveal.js` (the work-pages reveal pattern with the case-study selector lists, incl. the stats grid and gallery groups) + `assets/js/case-study/filter.js` (the project filter minus the 7/5 pattern maths: uniform grid, "N case studies shown." announcements) + the shared `homepage/counter.js` for the results band (no-ops on the archive). No wheel and no Splide on these pages: the testimonial is static markup. The service bundle's `service/reveal.js` gained the case-studies strip (h2 + `.cs-card-item` group) and `project/reveal.js` gained the `.project-case-study-band .band-inner` fade. Full notes in `docs/case-studies-system.md`.
