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

**Not needed for this launch** (Ibrar, 17 Sep 2026): he copies the whole local site, files and database, to staging and production and search-replaces the URL, so the local database is the source of truth. The pack stays as a record of what changed and as a fallback if a target database is ever kept. Later copy edits (the review round below) were made straight in the local database and are not in the pack.

`replay-prelaunch-audit.php` lives outside the theme (handed over with the branch; a copy sits in the content repo under `replay/`). Dry run by default, `--apply` to write, `--only=a,b` for sections, idempotent, slug-matched:

```bash
VC_WP_LOAD=/path/to/wp-load.php php replay-prelaunch-audit.php
VC_WP_LOAD=/path/to/wp-load.php php replay-prelaunch-audit.php --apply
```

Sections: `images` (768w and 1440w renditions), `landing-placeholders`, `seo-meta`, `home-copy`, `labels`, `service-copy`, `page-copy`, `authors`. Run it after the theme deploys, on staging first.

After it runs on production: regenerate Yoast's `llms.txt` (SEO > Settings > llms.txt, or re-save the setting). The old file listed deleted sample entries and no service pages, and its first line comes from the site tagline the pack changes.

## Review round (17 Sep 2026)

- **Agentic Browsing 3/3.** Splide sets `role="group"` on every slide, which axe rejects on an `<li>`, so carousel lists and slides are `<div>`s (`vc_logo_slide()`, the four marquee hosts, the landing hero, and the testimonial partial when it runs as a carousel; the static stack keeps `ul`/`li`). Yoast prepends a byte order mark to `llms.txt`, which fails Lighthouse's parser; `wpseo_llmstxt_encoding_prefix` returns an empty string. Run Lighthouse without `--only-categories` or the Agentic Browsing category never appears.
- **Regenerating `llms.txt` from the command line:** Yoast only populates the file inside cron, so `php -r 'define("DOING_CRON", true); require "wp-load.php"; do_action("wpseo_llms_txt_population");'` from the WordPress root. It is a static file holding absolute URLs, so it must be regenerated on every environment after the URL changes.
- **Process steps fill their own rule.** At lg and up each `.process-step` carries a `.step-fill` on its top border, driven from one scrubbed ScrollTrigger in `homepage/process.js` (`scaleX(clamp(progress * steps - i, 0, 1))`). The continuous `.process-progress` rail painted across the gaps between steps, so it now shows below lg only, as the vertical rail. Used on the homepage and the hub. The landing steps, the free-website rail, About how, Contact next steps and the service journey have an unbroken track by design, so a continuous fill is correct there.
- **Nothing moves on hover, arrows included.** Measured by hovering every link and button on eleven pages and diffing computed transforms: the 3 to 6px arrow nudge came off the hub directory, the offer links, the homepage service rows, service cards, work cards, case-study cards, the case-study band, the related strip, the slim header CTA and the author Read More link. What still transforms on hover: images zooming inside their frame, the service card watermark drift (an earlier ruling), and the dropdown caret flip, which marks the menu opening.
- **Header CTA:** `.menu-button a` sets `text-decoration: none` on hover and focus. The nav link underline rule reached it, and the dark-context reset skipped `.menu-button`.
- **Hub directory foot:** `.hub-services .offer-links` drops its own top border; the directory's bottom rule is the only line.
- **Service icons:** `vc_service_icon_url( $term )` returns nothing when a child's icon file is the same as its parent's, so children never borrow a pillar icon (pillar children grids, the homepage rail, the Contact picker). The pillar icons are Font Awesome Pro 7.1 Slab Regular. The Font Awesome API token on file has no Pro SVG scope (the API returns no Slab SVGs and the npm registry answers 401), so new Slab icons have to be supplied as SVG files: drop them in `assets/images/icons/services/`, name the file in the child's `icon` field, and they render with no code change.
- **Author photos take the About duotone:** grayscale image under a `$vc-primary` soft-light wash at 0.75 (`.insight-author-avatar`, `.author-avatar`), static on hover.
- **The About film is self-hosted:** `vulkan-creative-film-1080p.mp4` in the media library (1080p H.264, about 30MB, fast-start), encoded from the 4K master with `ffmpeg -vf scale=1920:-2:flags=lanczos -c:v libx264 -preset slow -crf 23 -profile:v high -level 4.1 -pix_fmt yuv420p -c:a aac -b:a 128k -movflags +faststart`. The 453MB master sits at `wp-content/VulkanTrailer.mp4` and must not travel with the site.
- **Positioning:** "London digital marketing agency" everywhere the site describes itself (home title and description, tagline, hero, About hero, footer strapline, archive descriptions), with social media management named in the description, tagline and strapline because it is one of the biggest services. The founders are mentioned once in the homepage hero and once as a Why proof line; other sections say something new instead. The Our Work sub-heading names the sectors served (law, finance, property, recruitment, hospitality), taken from the published work entries.
- **Cost FAQs carry no figures, on purpose** (Ibrar's call). Each answer names what drives the price and how it is agreed (fixed price before a build, monthly scope for retainers), which is what answer engines can lift in place of a number.

## Before go-live (parked for staging and production)

1. Move `wp-content/VulkanTrailer.mp4` (the 453MB 4K master) out of the site folder before copying it anywhere.
2. After the URL search-replace on each environment: regenerate `llms.txt` with the command above, flush permalinks, purge LiteSpeed.
3. Security headers: confirm nosniff, SAMEORIGIN, the referrer policy and the Permissions-Policy still arrive on a LiteSpeed cache hit; move them to `.htaccess` if the cache strips them.
4. CookieYes: confirm the banner loads without the console error it throws on the local domain (Best Practices should reach 100), and that the Meta Pixel fires only after consent.
5. reCAPTCHA v3: confirm the keys cover the staging and live domains and that every form submits (forms 2, 3, 5, 7, 8, 10).
6. 404s: unknown top-level URLs must return a 404 (Valet sends them to the homepage locally).
7. Delete the draft `[SAMPLE]` case study (#1200) once the real case studies are in.
8. The `claude` administrator account was created for the publishing pipeline; its author archive is noindexed, but decide whether the account should exist on production.
9. Ibrar runs `/review-animations` over the motion changes.

## Local quirks worth knowing

- **Valet sends unknown top-level URLs to the homepage** (its WordPress driver sets `PHP_SELF` to the request path, so core skips the 404). It does not happen on LiteSpeed. Check 404s on staging, not locally.
- Headless QA runs from a Playwright script through Bash with `channel: 'chrome'`; the Playwright MCP prompts for every action.
- Element screenshots of sections taller than the viewport draw fixed elements (header, skip link) in the wrong place. That is the capture, not the page.
