# Free Website offer page (July 2026)

The standalone conversion landing page at `/free-website/` (page 880, template `page-templates/page-free-website.php`): a professional website built free for local businesses, paid for by a rolling monthly fee **from £49** covering hosting, domain and SSL, updates/security/backups, support and small content changes. No minimum term. Built 5 Jul 2026; copy approved via the build plan. Not in any menu: reached by URL, ads and search. Indexable from launch (this is a real offer page and an SEO play).

## Page anatomy (top to bottom)

1. **Hero** (`.fw-hero`, `#top`): `$vc-background-dark` #121212 in BOTH modes. H1 (`fw_hero_heading_*` parts, "Built free." red), sub, forge CTA → `#enquire`, ghost CTA → `#how`, one-line stat note with an ember rule that draws on load. The decorative outlined "£0" monument (`.fw-hero-mark`, aria-hidden, `-webkit-text-stroke`) hides below lg.
2. **How it works** (`.fw-how`, `#how`): #FFF / #1E1E1E. Three steps (`fw_how_steps`) on a horizontal ember rail with outlined red numerals; vertical rail-less layout below lg. The rail is fully drawn by default; JS zeroes then draws it (no-JS never hides it).
3. **The ledger** (`.fw-ledger`): #F5F5F5 / #121212. The signature section: sticky head left, plate right with itemised rows (`fw_ledger_items`: item + detail), dotted leaders, an editable "Included" stamp per row (muted, never red, never ticks), ember rule, display-scale red "£0" total and the monthly note. `ledger.js` prints rows/leaders and settles the total on intersect; the finished state is server-rendered.
4. **Contrast** (`.fw-contrast`): #FFF / #1E1E1E. "Most agencies charge thousands. We don't." + the why-free statement (`fw_contrast_note`), then paired rows (`fw_contrast_rows`: usual/this) as a 5fr/7fr grid; the right cells form a continuous dark plate (#121212 in light mode, `$vc-grey-dark-surface` raised plate in dark). Below lg the head row hides and per-cell `.cr-label`s (real text, screen-reader visible) label each half of the stacked pairs.
5. **Proof** (`.fw-proof`): #F5F5F5 / #121212. The shared testimonial spotlight verbatim (`#testimonial-splide`, testimonial CPT query, About-page pattern; `homepage/testimonials.js` is bundled in).
6. **FAQ** (`.fw-faq`): #FFF / #1E1E1E. Accessible accordion built for this page: answers are server-rendered OPEN (`aria-expanded="true"`, no `hidden`), `faq.js` collapses them on init and toggles `hidden` + `aria-expanded` with a GSAP height tween (instant under reduced motion). Button-in-h3, CSS plus→minus glyph, red `:focus-visible` outline. `fw_faqs` also feeds the FAQPage JSON-LD.
7. **Enquire** (`.fw-enquire`, `#enquire`): #121212 both modes. Heading + sub left; the form panel right (white plate in light mode, #1E1E1E in dark) holding **Gravity Form 7** via `vc_render_form( 7 )` (see `docs/enquiry-form-system.md` → "The free-website variant"), with `fw_enquire_note` under the form.
8. **Sticky CTA** (`.fw-sticky-cta`): below lg only; frosted deep bar with a full-width forge button → `#enquire`. `sticky-cta.js` shows it only while neither the hero nor `#enquire` intersects; hidden state is `visibility: hidden` (unfocusable); no-JS never shows it.

Two JSON-LD blocks render after the sections: **FAQPage** (from `fw_faqs`) and **Service** (offer description, `provider` → the Yoast Organization `@id` `/#organization`, `offers.priceSpecification` = UnitPriceSpecification minPrice 49 GBP per MON). If the fee changes, update the ACF copy AND the `minPrice` in the template.

## Chrome

- **Slim header:** `header.php` branches on the template: logo + `.fw-header-actions` (forge CTA reading `fw_header_cta_label` + the desktop theme toggle) and NO nav, hamburger or mobile overlay; the header keeps the standard scrolled/frosted and hide-on-scroll behaviour. The base header hides `.menu-theme-toggle` below lg, so `components/_header.scss` re-shows it in page scope; **below sm the header CTA hides** (the row cannot fit all three elements at 375px) and the hero CTA + sticky bar carry the action. The header container is boxed (`container` class) like your-business.
- **Footer:** standard, with the CTA row suppressed (template added to `$vc_hide_footer_cta` in `footer.php`).
- **Dark mode:** normal dark-first behaviour (the inline pre-paint script runs here, unlike your-business); the toggle stays available.

## Files

- Template: `page-templates/page-free-website.php` (all fallbacks mirror the seeded copy).
- ACF: `acf-json/free-website-page.json` (`fw_` prefix; see `docs/acf-reference.md`).
- SCSS: `assets/css/components/free-website/` (page scope + header/hero/how/ledger/contrast/proof/faq/enquire/sticky-cta partials), imported in `app.scss` after case-study. Dark-mode overrides per section via `@at-root body.dark-mode` + `!important`. Hero pre-hide (h1/sub/actions/note) is `html.js`-gated with the 2.5s failsafe; every reveal target is force-shown in `misc/_motion.scss`.
- JS: `assets/js/free-website/`: `reveal.js` (hero timeline + IntersectionObserver section reveals, SplitText headings, per-section variety), `ledger.js`, `faq.js`, `sticky-cta.js` (also the `free_website_submit` dataLayer push); bundled with `homepage/testimonials.js` as `js/free-website.js` (webpack.mix.js), enqueued in `inc/styles-scripts.php` on the template.
- The build also fixed the sitewide anchor bug in `assets/js/global/remove-anchor-from-url.js`: it rewrote every anchor click's URL to the hard-coded homepage `/` and misspelled `behaviour:` so smooth scroll never ran. It now preserves the current path and respects reduced motion; homepage and About anchors re-verified.

## Gotchas

- The three-element mobile header does not fit at 375px; do not re-add the header CTA below sm without shortening the label.
- The ledger stamp and the contrast `.cr-label`s are repeated marks: keep them muted, never red and never ticks (the tick-restraint rule).
- `.cr-label` colour needs both light (grey-600) and dark (`$vc-muted-on-dark`) treatments; grey-400 fails contrast on white.
- The £0 total is red display type on #F5F5F5 (large-text 3:1, owner-approved metric red); do not shrink it to body scale.
- Form id 7 appears only in the template's `vc_render_form( 7 )` call; everything else keys on the `enquiry-form` cssClass.

Verified 5 Jul 2026 with Playwright at 375/768/1024/1440 in both modes: form submission (entry 348) + validation states, anchors clear of the fixed header with the URL preserved, sticky bar show/hide, FAQ keyboard + `aria-expanded`, reduced-motion finished-states, no-JS content visibility, no horizontal overflow, FAQPage + Service JSON-LD parsing, homepage regression (nav/footer CTA intact).
