# Pre-launch audit (Sep 2026)

Build notes for `fix/prelaunch-audit`: the slop, design, SEO and Lighthouse pass done before the site went live. Read this before touching script loading, the first-paint motion, the shared testimonial partial, the services hub directory or the replay pack.

## Results (local, Lighthouse 13, 17 Sep 2026)

Twelve pages, mobile as the median of three runs, desktop one run each.

| | Before | After |
|---|---|---|
| Mobile performance | 66 to 80 | 87 to 97 (eleven of twelve pages at 90 or more; the homepage 87) |
| Desktop performance | 90 to 99 | 97 to 100 (eight pages at 100) |
| Accessibility | 92 to 96 | 100 on every page, and no serious axe violations in light mode either |
| SEO | 100 | 100 |
| Best practices | 96 | 96 (CookieYes throws on any domain that is not the registered one; it clears on production) |
| Mobile LCP (simulated) | 4.8 to 10.0s | 2.5 to 4.0s |
| app.css | 812KB | about 420KB |
| about.js | 903KB | 170KB |
| header.js | 79KB | 6KB |

**Read local mobile scores with one caveat.** With no network latency the `load` event fires at about 0.45s. If a page's first frame is not out before `load`, headless Chrome drops every frame for roughly the next second, so the first paint is recorded near 1.4s and Lighthouse's simulator then counts every byte on the page against LCP. Heavier pages (home, Contact) lose that race on most runs; lighter ones win it. It is a property of the local setup, not of the pages: with applied throttling (`--throttling-method=devtools`) the homepage scores 94 to 95 with LCP at 2.3 to 2.4s, and on a real host `load` fires long after first paint. Single runs also swing by up to ten points, so compare medians. Lighthouse only ever audits dark mode (the default); light mode was checked with axe-core directly.

## Performance architecture

- **Script loading.** Every theme bundle goes through `vc_enqueue_bundle()` in `inc/styles-scripts.php`: `strategy => defer`, in the head, no jQuery dependency. jQuery loads in the footer only on pages that render a Gravity Form (GF brings it), without jquery-migrate (`inc/remove.php`). The footer bundle is gone: the wordmark parallax is a CSS scroll-driven animation and the footer logo is an `<img>`.
- **No GSAP in the header.** The logo draws in with CSS (`stroke-dashoffset` keyframes, `pathLength="1"` on paths 7 to 16 of `logo.svg`, order and timing in `header/components/_desktop.scss`). `header.js` only handles scroll state and the vanilla mobile menu (Escape, Tab trap, `inert` on `#smooth-wrapper`).
- **Bootstrap subset** in `app.scss`: functions, variables, maps, mixins, a trimmed `$utilities` map (`align-items`, `order`, non-responsive `padding-x`), root, reboot, containers, grid, visually-hidden, utilities API. Need another utility? Add it to the map; do not import all of Bootstrap.
- **Fonts.** Poppins is subset to Latin (`*-latin.woff2`) with the full files behind an extended `unicode-range`; Archivo is instanced to the display range in use (`Archivo-Display.woff2`, wght 700 to 900, wdth 100 to 125). Four preloads in `header.php`.
- **About film** is a native `<video preload="none">` with the house play button (`about/story.js`); video.js and its YouTube plugin are gone.
- **Gravity Forms CSS** (four files, about 38KB) loads with `media="print"` swapped to `all` on load everywhere except Contact, where the form is in the first screen (`vc_defer_form_styles()`).
- **Conditional-logic forms render visible.** GF prints a form with conditional logic as `display:none` and reveals it from script, which pushed the contact details down on a phone (0.15 CLS). `vc_show_form_when_page_has_no_rules()` strips that when the page being rendered carries no rules.
- **reCAPTCHA** is dequeued where no form rendered (`vc_recaptcha_only_with_forms()`); the Meta Pixel waits for CookieYes advertising consent.
- **Images.** `vc_image()` wraps `wp_get_attachment_image()` so every image has width, height and a srcset. `medium_large` (768w) is back on and `vc-1440` is new; existing uploads need the replay pack's `images` section.

## First paint and motion rules

- **The LCP element never waits for script.** Below lg, hero text paints with the first frame through the CSS entrance in `misc/_motion.scss` (`vc-first-paint-rise`). It starts at `opacity: 0.01`, not 0, because Chrome ignores opacity 0 for LCP. JS hero sequences run at lg and up only (`heroOnJs` in each reveal module).
- **The homepage poster is server-rendered** in a `<picture>` with a preload; `statue-hero.js` only hands over the desktop still for reduced motion or a failed scene.
- **No blanket fade-ups.** Heading SplitText line reveals stay (owner's call). Body copy, sidebars, forms, FAQs and article bodies are never hidden. Lists of objects move: cards and steps stagger (70 to 80ms apart), founder panels clip in, partner badges cascade, stat rules draw with the count-up, hub directory rows rise one by one.
- **SplitText** always runs with `aria: 'none'` and `revert()` on completion. Its default writes `aria-label` onto the split element, which is prohibited on a `<p>`.
- **Hover heat, not shadows.** `$vc-glow-card`, `$vc-glow-card-dark` and `$vc-glow-button` have no y offset. The remaining offset shadows belong to floating layers only (scrolled header, mega menu, filter dropdown).
- The theme toggle's vendor morph is overridden to 300ms with no overshoot.
- `/review-animations` is user-invoked only; run it yourself after motion changes.

## Shared parts added

- `template-parts/testimonial-spotlight.php` (+ `vc_testimonial_items()`, `vc_testimonial_item()`, `vc_quote_scale()`): one partial for the homepage, About, hub, free-website page, landing block and case study singles. A review with no photo shows no portrait (the old fallback put one headshot beside every client's name). Each quote sets its type size from its length (`--q`), because Splide's fade track is always as tall as its tallest slide. Very long reviews clamp with a "Read Full Review" control that pauses autoplay.
- `template-parts/offer-links.php`: the "Not sure where to start?" row linking the free visibility report and the free website offer (homepage process foot, hub directory foot).
- Services hub directory (`services-hub/components/_directory.scss`): one row per pillar with its children as links, pillar link stretched over the row.
- Blog: `content-card.php` takes `lead => true` for the wide lead card (index, category archives, the filter's REST renderer); titles are never clamped; authors show `profile_photo` from the User group through `pre_get_avatar_data`.

## SEO plumbing

- Thin categories (fewer than three posts) are noindexed and dropped from the sitemap together (`vc_is_thin_category()`); `/404-preview/` is excluded from the sitemap. Every theme-level robots exception pairs with a sitemap filter.
- Service children no longer inherit their pillar's process steps.
- Blog posts print "Updated" when modified after publishing, and wrap Key takeaways in its own labelled section.
- `homepage.json` defines `hp_why_items` and the Why stat fields again (an old commit had reused their keys). ACF reads the JSON directly while it is newer than the database copy; syncing under Custom Fields is optional.

## Replay pack

`replay-prelaunch-audit.php` lives outside the theme (handed over with the branch; a copy sits in the content repo under `replay/`). Dry run by default, `--apply` to write, `--only=a,b` for sections, idempotent, slug-matched:

```bash
VC_WP_LOAD=/path/to/wp-load.php php replay-prelaunch-audit.php
VC_WP_LOAD=/path/to/wp-load.php php replay-prelaunch-audit.php --apply
```

Sections: `images` (768w and 1440w renditions), `landing-placeholders`, `seo-meta`, `home-copy`, `labels`, `service-copy`, `page-copy`, `authors`. Run it after the theme deploys, on staging first.

After it runs on production: regenerate Yoast's `llms.txt` (SEO > Settings > llms.txt, or re-save the setting). The old file listed deleted sample entries and no service pages, and its first line comes from the site tagline the pack changes.

## Local quirks worth knowing

- **Valet sends unknown top-level URLs to the homepage** (its WordPress driver sets `PHP_SELF` to the request path, so core skips the 404). It does not happen on LiteSpeed. Check 404s on staging, not locally.
- Headless QA runs from a Playwright script through Bash with `channel: 'chrome'`; the Playwright MCP prompts for every action.
- Element screenshots of sections taller than the viewport draw fixed elements (header, skip link) in the wrong place. That is the capture, not the page.
