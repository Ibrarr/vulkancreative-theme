# Frontend Audit — Vulkan Creative

**Date:** 6 July 2026 · **Scope:** every page and template on `https://vulkancreative.test/` · **Method:** live Playwright MCP capture at 375/768/1024/1440 in light and dark mode, objective per-page checks (contrast, overflow, tap targets, headings, schema, alt), interactive checks (nav dropdown, mobile menu, keyboard focus, the enquiry form), a skill-driven per-page analysis pass (impeccable, ui-ux-pro-max, taste-skill; seo-audit, schema, cro, copy-editing, site-architecture), then live re-verification of the highest-severity findings.

This is the working checklist for the fix stage. Every finding has an **ID**, a **severity** (`broken` / `important` / `minor` / `nice-to-have`) and a **Status** (`open` / `done` / `parked` / `for-Ibrar`). Update Status as we go.

**Severity counts:** 2 broken · ~42 important · ~48 minor · ~22 nice-to-have (114 total).

> **Verification note.** Several findings the analysis agents rated "broken/important" were **full-page-screenshot timing artefacts**: reveal-on-scroll and count-up animations had not fired at capture time, so below-fold content looked blank or stuck at 0. I re-checked these live. Where the live page is actually fine, the finding is downgraded and marked **(artefact-corrected)**. The one genuine reveal break is the Work archive grid.

---

## 1. Summary

The site is, overall, in good shape: one coherent forge design system, a real accessibility floor (visible 2px red `:focus-visible` rings everywhere, honest reduced-motion handling), strong structured data on most templates, and a genuinely premium look on the flagship pages. The homepage, About, Services and Free Website pages hold the bar well.

The problems cluster into a few recurring themes rather than one-off mistakes:

1. **One genuinely broken page.** On desktop, the `/work/` archive grid renders invisible on first load and only appears after the user scrolls ~1,000px. The proof-of-craft page shows an empty void on the exact surface meant to prove we build working sites.
2. **A live content mix-up.** The blog post titled "Gemini 3.1 Pro" has a body entirely about "Claude Sonnet 5". It is indexable. This is the single most damaging live issue and it is a content/data fix, not a code one.
3. **Banned patterns that crept back in.** The tint/saturation-on-hover effect (banned outright) survives on the homepage trust logos and the shared insight-card component (so it hits the whole blog family). Numbered `01/02/03` markers are used as decoration in several places. The legacy `/your-business/` page still runs the entire pre-redesign system (this is already agreed for migration).
4. **Yoast is quietly overriding two house conventions site-wide.** Titles use a `-` separator on most pages while the homepage uses `|`; and a breadcrumb filter strips the current-page crumb, leaving a lone red "HOME" above the heading that reads exactly like the banned eyebrow tag.
5. **Content and metadata gaps.** Missing meta descriptions on Contact, Category and legal pages; bare, keyword-free titles on About and Contact; and real data problems on the Privacy Policy (a broken merge field, two conflicting company addresses that also disagree with the footer, and a personal Gmail/mobile as the data-protection contact).
6. **Mobile tap targets** below 44px on shared furniture (footer social icons 22×22, header nav ~31–36px tall, some sub-16px inputs that trigger iOS zoom).

Most of this is fixable at the **shared/component level**: a handful of edits to `inc/filters.php`, `footer.php`, the header/footer SCSS, `common/_post-grid.scss`, the reveal system and the Yoast config resolve findings across many pages at once.

---

## Fix log — session 1 (6 Jul 2026)

Done and verified live (both modes / relevant breakpoints):

- **WRK-01** `Status: done` — work-archive reveal split onto its own threshold-0 observer; verified all 10 cards `opacity:1` at fresh desktop load (was the empty void).
- **SW-01** `Status: done` — breadcrumb filter scoped to content singles; verified "Home > Blog > AI" (no more lone "HOME"). Clears CON-02, CSA-01, CAT-04, LEG-08.
- **SW-02** `Status: done` — removed the banned saturation hover from the shared insight card + homepage trust logos; verified hover rule no longer changes grayscale. Clears BI-03, AUT-01, HOME-02/03.
- **SW-03** `Status: done` — `wpseo_title` filter normalises the separator to `|` and guarantees the brand suffix; verified "AI Posts | Vulkan Creative", "Our Work | Vulkan Creative". Clears AB-01 separator, BLOGS-02, WRK-02/CAT-05 separators.
- **SW-05** `Status: done` — tap targets to ≥44px: footer socials (192×44), desktop nav links, hamburger (44×44), theme toggle (44×44), mobile socials (44×44); verified. Clears the per-page tap-target findings.
- **SW-06** `Status: done` — dark-mode "Category:" label → `$vc-muted-on-dark`; verified 5.9:1 (was 2.16:1). Clears BI-01, CAT-02.
- **SW-07** `Status: done` — your-business added to the footer CTA-hide list.
- **SW-08** `Status: done` — services-hub `&nbsp;` → plain space; blog-index standfirst em dash → colon.
- **SW-10** `Status: done` — footer legal links now `esc_url(home_url())`. (Label/target of "Cookie Settings" left for you — see below.)
- **SW-11** `Status: done` — removed the dead `practice_area` body-class logic.
- **E404-01** `Status: done` — `wpseo_robots_array` noindexes the 404-preview template.
- **HOME-01** `Status: done` — hero rotating words `aria-hidden`, added a visually-hidden clean phrase; verified accessible H1 = "We forge brands, websites, marketing and content, built to perform."
- **BI-02** `Status: done` — imageless insight card now carries a forge ember gradient (latent: no imageless posts currently, applies when one has no featured image).
- **CAT-03** `Status: done` — insights filter inputs → 16px; verified (stops iOS zoom).
- **CON-06** `Status: done` — dark-mode GF validation summary → light text/red links; verified 13.88:1 (was ~1.05:1).
- **AUT-02** `Status: done` — removed the "AUTHOR" eyebrow; verified.
- **AUT-03** `Status: done` — author name no longer uppercased; verified "Ibrarr Khan".
- **SR-01** `Status: done` — added the shared search bar to `search.php`; verified it pre-fills the query.
- **SR-02** `Status: done` — no-results state now offers Insights / Our Work / Case Studies links; verified.
- **LEG-06** `Status: done` — `.content-area` capped to a 72ch readable measure.

Numbered markers (your decision: remove on non-sequential sets):

- **HOME-04** `Status: done` — removed the `01-06` numeral from the homepage service cards; verified the section still reads well (watermark icon + title carry it). Inert `.service-index` SCSS left in place.
- **SHUB-01** `Status: done` — services-hub cards now render with `show_index => false` (no numerals). Process rail numerals kept (real sequence).
- **HOME-08** `Status: done` — removed the `01-06` counter numerals from the mobile nav items.
- **YB-05 (numerals part)** `Status: done` — removed as part of the migration below.

**Your-business forge migration (YB-01…YB-15)** `Status: done` — verified live at 1440 + 375. Removed all six eyebrows (incl. the "From Your Business Magazine" line, per your call) and the pillar/outcome numerals; swapped `vc-h1`/`vc-h4` → `vc-display-1`/`vc-display-2`/`vc-display-3`, `vc-tag` → gone, `vc-button-big` → `vc-button-forge` (10px→2px); replaced the red-bordered problem cards and pillars with forge surface cards (hairline, 2px), the 3px red testimonial strip with a forge card, and the red outcome dividers with ember hairlines; tokenised the padding (`vc-section-padding`/`$space-*`) and the hardcoded `#222222` → `$dark-vc-background-dark`; set the surface-pair rhythm (first post-hero section on the lighter pair); removed the logo-bar tint hover; fixed the `&mdash;`/`&ldquo;` entities; added the yb targets to the reduced-motion safety net, guarded `hero.js`, and gated the hero pre-hide on `html.js` (no-JS visibility); and the 375px overflow is gone (0px, was 36px — the 150px numeral). The default headings all read fine standalone, so no copy changes were needed. The `.gform_wrapper.contact-form_wrapper` form overrides in `_cta.scss` are inert legacy (form 2 uses the shared enquiry-form styling) and were left untouched.

Follow-up round (your feedback, 6 Jul 2026):

- **HDR-SEAM** `Status: done` — the dark title band's ember `border-bottom` (the thin red seam) existed on the `page-hero` pages (about, work, case studies, contact, services) but was missing on the `insights-header` family. Added it to `.insights-header` (blog index, category, author, search) and the blog single `.insight-hero`, so every dark title band now carries the seam.
- **YB-HERO-GLOW** `Status: done` — the your-business hero was flat. Added the shared forge glow (`.page-hero-glow`), set the hero+logo-bar to the `#121212` anchor with the ember seam to the first section, and kept the clean dark `#1E1E1E`/`#121212` section alternation (you chose to keep the page dark-only).
- **YB-OVERFLOW** `Status: done` — found and fixed a real mobile overflow: `solution.js` slides the right pillar in with `translateX(60px)`, which overflowed at 375px until the reveal fired. Clipped `.yb-solution` with `overflow: hidden`; verified 0px overflow at 375 in every reveal state.

Follow-up round 2 (your feedback, 6 Jul 2026):

- **HDR-SEAM-2** `Status: done` — added the ember `border-bottom` seam to the homepage hero (`.hero`) and the default/legal page header band (`.default-page .page-header`), so those title bands now match the rest.
- **LEG-WIDTH** `Status: done` — reverted the 72ch cap on the legal `.content-area` and widened its column from `col-lg-8` to `col-12`, so the content now runs full container width (~1296px) per your request. (Note: at 1440 this is ~180ch lines; say the word if you want a middle-ground max-width for readability.)
- **BLOG-RED** `Status: done` — added brand red to the blog single hero: the breadcrumb links and the meta `·` separators (author/category/date) now read red instead of all-white, legible over the hero's dark bottom veil.

Follow-up round 3 (footer, your feedback, 6 Jul 2026):

- **FOOT-SOCIALS** `Status: done` — the footer socials were spread out vertically (the 44px tap-target fix in a column). Moved them under the brand note in the left column as a compact horizontal row of 44px icon-only links, and removed the standalone "Follow us" column.
- **FOOT-MENUS** `Status: done` — added a **Services** footer column (the 6 service terms in their ACF order, rendered from the taxonomy, placed first) and made the **Explore** column mirror the header nav minus services: it now renders the main menu at depth 1 with a scoped `wp_nav_menu_objects` filter that strips the "What We Do" services entry and the CTA button. Result: Home, About Us, Our Work, Case Studies, Insights. (The `footer-menu` menu location is now unused; safe to leave or unassign.)
- **FOOT-NOTE** `Status: done` — rewrote the footer note to "Brand, web and marketing that performs, built in-house and measured by results." (leads with the outcome, "in-house" is a clearer differentiator than "in person", ties to the forge "built to perform" line). Alternatives offered for your pick.
- **FOOT-GRID** `Status: done` — footer columns set to your chosen widths: left `col-lg-5`, Services `col-lg-3`, Explore `col-lg-2`, Contact `col-lg-2` (5:3:2:2). Tightened the socials: gap to the note halved (32→16px) and the row pulled left 11px so the LinkedIn icon is flush with the brand text.
- **FOOT-HOVER** `Status: done` — removed the sliding left-border tick (`::before`) on the footer menu links; hover/focus is now only the red colour change, on both the Services and Explore lists.

Follow-up round 4 (header, your feedback, 6 Jul 2026):

- **HDR-BP** `Status: done` — the desktop-nav/hamburger switch now happens at **1100px** instead of Bootstrap's `lg` (992px). Added a `$header-nav-bp: 1100px` variable and swapped the three switch rules (`_header.scss` + `_desktop.scss`) plus the free-website slim-header override (kept in sync so its actions still show below 1100). Verified: hamburger + working mobile menu at 1099, desktop nav (no wrap/overflow) at 1100; free-website header unaffected. No JS change needed (the header JS has no width gate).
- **HDR-ARROW** `Status: done` — removed the `→` arrow (`::after`) from the "Let's Talk" nav CTA. (The free-website "Get Your Free Website" button keeps its own arrow — you asked only about Let's Talk.)

Verified-not-an-issue / parked:

- **SW-09** `Status: parked` — checked live: category pages render exactly one header/footer, so the `archive.php` double-`get_header` doesn't manifest. Left untouched to avoid breaking a working page.
- **SW-10b** `Status: for-Ibrar` — "Cookie Settings" links the cookie *policy*, not a consent manager. URL now escaped; the label/target mismatch is a decision for you (relabel to "Cookie Policy", or wire it to the CookieYes banner).
- **Numbered markers** (HOME-04, HOME-08 mobile nav, SHUB-01, CON-04) `Status: for-Ibrar` — awaiting your call (see question below). Sequential Process numerals stay regardless.

Ignored per your instruction: **BLOGS-01** (Gemini/Claude post — local test data).

Still to do: the **your-business forge migration** (YB-*, next focused pass; its pillar/outcome numerals depend on the numbered-marker decision), and the content/`[SAMPLE]` items left to you (§5).

---

## 2. Top priorities (drives the fix order)

Ordered highest-impact first. Shared-level fixes are marked ⭑ because one change clears several page findings.

| # | ID | Severity | What | Fix at |
|---|----|----------|------|--------|
| 1 | WRK-01 | **broken** | `/work/` grid invisible on desktop load until scrolled | `assets/js/project/reveal.js` |
| 2 | BLOGS-01 | **broken** | Blog post "Gemini 3.1 Pro" has a "Claude Sonnet 5" body, live + indexable | **content (Ibrar)** |
| 3 | SW-01 ⭑ | important | Breadcrumb filter strips terminal crumb → lone red "HOME" eyebrow above H1 (Contact, both archives, legal) | `inc/filters.php` |
| 4 | SW-02 ⭑ | important | Tint/saturation hover (banned) on shared insight card + homepage trust logos | `common/_post-grid.scss`, `_testimonials.scss` |
| 5 | SW-03 ⭑ | important | Yoast title separator `-` vs house `|`; Blog single has no brand suffix | Yoast config / `wpseo_title` |
| 6 | LEG-01 | important | Privacy Policy: broken merge field, two conflicting addresses (both ≠ footer), Gmail/mobile DPO contact | **content (Ibrar)** |
| 7 | SW-04 ⭑ | important | Reveal-system has no time-based failsafe below the hero (hardening) | per-template `reveal.js` + `misc/_motion.scss` |
| 8 | SHUB-02 | important | Services hub is indexable while showing `[SAMPLE]` testimonials + unbacked "4.9" rating | Yoast noindex / **content** |
| 9 | CON-01 | important | Contact page has no meta description | Yoast field |
| 10 | SW-05 ⭑ | important | Footer social icons 22×22 + header nav links ~31–36px tall (<44px) | `footer.php`/`_footer.scss`, header SCSS |
| 11 | HOME-01 | important | Hero rolling words: all four sit in the accessibility tree → run-on H1 | shared rolling-word module |
| 12 | YB-ALL | important | `/your-business/` legacy migration to the forge system (agreed) | whole template (larger job) |
| 13 | SW-06 ⭑ | important | Dark-mode "Category:" filter label 2.16:1 (fails) | `archive/components/_posts.scss` |
| 14 | CON-02 | important | Contact/archive lone "HOME" crumb reads as banned eyebrow (instance of SW-01) | via SW-01 |
| 15 | SVC-01 | important | Service pages show `[SAMPLE]` zeroed stats (0.0x/0%/0.0s); confirm before indexing | **content (Ibrar)** |

Everything below the line is important-but-narrower, minor, or nice-to-have, listed per page and in the site-wide section.

---

## 3. Per-page / per-template findings

Grouped by area within each page. IDs are stable; tick Status as fixed.

### 3.1 Homepage (`/`, `front-page.php`) — design source of truth

- **HOME-01** · accessibility · important · `Status: open` — **Hero H1 run-on for screen readers.** The four rolling words (`brands`/`websites`/`marketing`/`content`) all stay `visibility:visible` in the DOM, so the accessible H1 reads "We forge brands websites marketing content built to perform." Mark the three inactive words `aria-hidden="true"` (toggling as they rotate) so AT/SEO see "We forge brands built to perform." Keep all words in the DOM. Build the fix into the shared rolling-word module (also used on your-business).
- **HOME-02** · design-slop · important · `Status: open` — **Trust logos saturate on hover (banned).** `_testimonials.scss:60` flips `grayscale(1)→grayscale(0)` on hover of non-interactive `<img>` logos; not gated behind `@media (hover:hover)`. Remove the hover filter; keep logos static. (Shared fix SW-02 covers the card version.)
- **HOME-03** · design-slop · minor · `Status: open` — **Marquee/trust logos opacity hover.** `_hero.scss:221-223` and `_testimonials.scss:89-91` shift opacity on hover of non-interactive logos, ungated for touch. Remove or gate behind `@media (hover:hover)`.
- **HOME-04** · design-slop · nice-to-have · `Status: open` — **Service card `01–06` numerals.** Decorative numbered-marker scaffolding on a non-sequential set. Demote or drop the `.service-index` (keep the numerals on the genuinely sequential Process rail). Also appears on the Services hub (see SHUB-01).
- **HOME-05** · design-slop · nice-to-have · `Status: open` — **Service card 3px red top strip on hover** (`_services.scss:64-75`). A solid red top bar reads closer to the banned top-accent strip than to "border heat". Prefer border-heat/glow only.
- **HOME-06** · performance · nice-to-have · `Status: open` (artefact-corrected) — **No time-based reveal failsafe below the hero.** Counters and section reveals *do* fire correctly on scroll (verified live: `0+`→`120+`, `4.9`, `2.3x`, `10+`). The "stuck at 0/blank" the analysis saw was a screenshot artefact. Remaining point: unlike the hero, below-fold targets have no CSS timeout failsafe if `fonts.ready` ever stalls. Hardening only — fold into SW-04.
- **HOME-07** · seo-schema · minor · `Status: open` — **Title front-loads a soft phrase.** "Turning Creative Sparks Into Powerful Brands | Vulkan Creative" leads with brand poetry, not core service keywords. Consider fronting a service/keyword phrase. (Low urgency — it's the one page with the correct `|` separator.)
- **HOME-08** · design-slop · minor · `Status: open` — **Mobile menu numbers nav items `01–06`.** Verified in the open overlay: each nav link carries a small red numeral. A nav is not an ordered sequence; remove the numerals (numbered-marker slop on a primary surface).

*Positive:* keyboard `:focus-visible` rings (2px red) present; desktop "What We Do" dropdown is legible in light mode (white panel, `#121212` links); mobile menu is a full-height overlay with the CTA reachable.

### 3.2 About (`/about/`, `page-templates/page-about-us.php`)

- **AB-01** · seo-schema · important · `Status: open` — **Bare title + wrong separator.** "About - Vulkan Creative" (23 chars): no keywords/value prop, and `-` vs the house `|`. Set a Yoast title ~50–60 chars with `|`, e.g. "Meet the Founders | Vulkan Creative". (Separator is the site-wide SW-03.)
- **AB-02** · seo-schema · minor · `Status: open` — Meta description 124 chars; extend toward 150–160.
- **AB-03** · performance · nice-to-have · `Status: open` — Founder fallback headshot `avatar-placeholder` is a **1.37MB 1000×1000 PNG** (real portraits are correct 800×800 webp). Replace the fallback with an optimised webp.
- **AB-04** · design-slop · minor · `Status: open` — Values section base/reduced-motion state paints four **giant solid-red words** (a red wash, not an accent). Ensure the no-JS/reduced-motion fallback keeps red as an accent.
- **AB-05** · conversion · nice-to-have · `Status: open` — No in-body enquiry CTA across five sections; only the footer band converts. Consider one mid-page CTA.
- **AB-06** · conversion · nice-to-have · `Status: open` — "Watch the Film" scrolls to `#watch` but doesn't start playback; consider auto-playing on click.

### 3.3 Contact (`/contact/`, `page-templates/page-contact-us.php`)

- **CON-01** · seo-schema · important · `Status: open` — **No meta description at all** (no `<meta name="description">`, no og/twitter description). Add a Yoast description ~150–160 chars using the one-working-day promise. Primary enquiry landing page.
- **CON-02** · design-slop · important · `Status: open` — **Lone "HOME" breadcrumb reads as the banned eyebrow** above the H1 (instance of SW-01). A one-item breadcrumb also gives no wayfinding. Fix via SW-01 (suppress single-crumb breadcrumbs).
- **CON-03** · consistency · minor · `Status: open` (artefact-corrected) — **"What happens next" reveal + lopsided column.** The block sits at the very bottom edge on load (895px of 911px) so it reveals correctly on scroll (not a broken reveal). The real, minor issue is the desktop left column ending early beside the tall form, leaving a void. Balance the column / lower the observer threshold as hardening.
- **CON-04** · design-slop · minor · `Status: open` — "What happens next" `01/02/03` numerals (numbered-marker scaffolding). These *are* a sequence, so lower priority than the service-card case; judge on balance.
- **CON-05** · seo-schema · minor · `Status: open` — Title bare "Contact - Vulkan Creative" (25 chars); add keyword/location and the `|` separator.

*Verified:* Form 5 (multi-step, 4 steps) validation works; branded red per-field errors render; the dark-mode summary banner is legible (white text in a red-bordered box). **But** see SW-06-related CON note: the GF error-summary *list links* are unstyled in dark mode →

- **CON-06** · light-dark · important · `Status: open` — **GF validation summary list links unreadable in dark mode.** `.gform_validation_error_list li a` keep GF/Orbital's ~`#212529` on the `#262626` dark form surface (~1.05:1). Add a dark-mode colour override in `common/_form-errors.scss`. (Trigger a failed step to confirm.)

### 3.4 Services hub (`/services/`, `page-templates/page-services-hub.php`)

- **SHUB-01** · design-slop · important · `Status: open` — **Two stacked `01–0N` numeral systems** (service cards 01–06 + process 01–04). Drop the service-card numerals (the `--related` variant already omits them); keep them only on the sequential process rail.
- **SHUB-02** · conversion · important · `Status: for-Ibrar` — **Indexable hub shows fabricated testimonials + unbacked "4.9" chip.** Names/companies are `[SAMPLE]` seeds; no `AggregateRating` schema backs the 4.9. Either noindex the hub until real quotes land (as work/case-studies already are) or swap in real, attributable quotes and back the rating.
- **SHUB-03** · design-slop · minor · `Status: open` — 6-up equal-column grid of structurally identical cards (icon/numeral + title + 3-line clamp + arrow) risks templated-grid slop; vary rhythm or lean on the varying watermark icons.
- **SHUB-04** · consistency · nice-to-have · `Status: open` — `&nbsp;` entity in the spotlight counter (`page-services-hub.php:206`). House rule: plain characters, never entity codes. (Part of the site-wide entity/em-dash sweep SW-08.)
- **SHUB-05** · seo-schema · minor · `Status: open` — Title 66 chars / meta 164 chars both run slightly long; trim toward 60 / 155.

### 3.5 Service pages (`taxonomy-service.php`, e.g. `/services/web-design-development/`)

- **SVC-01** · copy · important · `Status: for-Ibrar` — **`[SAMPLE]` zeroed stats live** ("0.0x / 0% / 0.0s" with `[SAMPLE]` labels) and `[SAMPLE]` case-study strip copy. Noindexed for now; replace `sv_results_stats` + intro/deliverables copy with real figures before indexing, or clear the sample rows (the results anchor hides when empty).
- **SVC-02** · seo-schema · important · `Status: open` — **No canonical emitted** (Yoast suppresses it under noindex). Add a go-live check / `wpseo_canonical` for `is_tax('service')` so each indexable term self-canonicalises (the system supports `?service=` variants elsewhere).
- **SVC-03** · seo-schema · minor · `Status: open` — Meta description 124 chars; extend toward 150–160.
- **SVC-04** · consistency · nice-to-have · `Status: open` — Hardcoded CTA copy not in ACF: deliverables nudge "Scoped to your goals, priced before we start." and the CTA cell label (`taxonomy-service.php:236`). Move to ACF per the "every editable string in ACF" rule.
- *Cross-instance:* branding term spot-checked; same template, same behaviour. No per-instance breakage.

### 3.6 Work archive (`/work/`, `archive-project.php`)

- **WRK-01** · responsive · **broken** · `Status: open` — **Grid invisible on desktop load.** Verified live at 1440×911, scrollY 0: all 10 `.work-card` at `opacity:0` even after 2s; they flip to `1` only after scrolling ~1,100px. Cause: `assets/js/project/reveal.js` observes the 2,408px-tall `[data-work-grid]` container at threshold 0.12, but <12% is in view on load, so the IntersectionObserver never fires. Fix: observe cards individually (the homepage `our-work.js` precedent) or add an immediate-reveal for any card intersecting at `observe()` time. Mobile (375) is fine. **This is the #1 fix.**
- **WRK-02** · seo-schema · minor · `Status: open` — Title "Our Work - Vulkan Creative" uses `-` (SW-03); no canonical (`?service=` variants exist); meta 130 chars (short).
- **WRK-03** · accessibility · minor · `Status: open` — Footer social icons 22×22 (shared, SW-05).
- *Noindexed sample content — flag, don't "fix" the copy.*

### 3.7 Work single (`single-project.php`, e.g. `/work/sample-northbridge-property-group/`)

- **WKS-01** · consistency · important · `Status: for-Ibrar` — **Wrong client's hero image.** The Northbridge (property developer) page shows `aldermere-events-collateral-*.jpg` with alt "Aldermere Events lanyards, wristbands and tickets". Since the sample is the editorial template, fix the seed so the hero image + alt match the client. (Sample data, but it ships as the copy-paste template.)
- **WKS-02** · accessibility · minor · `Status: open` — Service taxonomy links in hero meta + fact ledger ~20px tall on mobile (<44px).
- **WKS-03** · accessibility · minor · `Status: open` — Footer socials 22×22 (SW-05).
- **WKS-04** · seo-schema · nice-to-have · `Status: open` — CreativeWork `dateCreated` is a bare year ("2025"); a full ISO date is a stronger signal.

### 3.8 Case studies archive (`/case-studies/`, `archive-case_study.php`)

- **CSA-01** · seo-schema · important · `Status: open` — Lone "HOME" breadcrumb (instance of SW-01) — "Home > Case Studies" collapses to "Home".
- **CSA-02** · design-slop · minor · `Status: open` — 3 cards in a 2-up grid leaves ~half the second row blank; no odd-count treatment. Add a fill/centre rule.
- **CSA-03** · conversion · minor · `Status: open` — Filter shows 6 service chips but only 3 map to content; consider hiding empty chips.
- **CSA-04** · seo-schema · nice-to-have · `Status: open` — Title "Case Studies - Vulkan Creative" (30 chars) thin + `-` separator (SW-03).

### 3.9 Case study single (`single-case_study.php`)

- **CSS-01** · seo-schema · minor · `Status: open` (corrected) — **JSON-LD keywords double-encode the ampersand** (`"Web Design &amp; Development"`). Verified: the *visible* on-page labels are correct; only the structured-data keyword string is affected. Fix the JSON encoding in `single-case_study.php` (use `wp_json_encode`, not `esc_html`, for the keywords). Not a DB change.
- **CSS-02** · conversion · minor · `Status: open` — Duplicate "Start a Project" CTAs (results-band ghost + closing primary) to the same `/contact/`; the results-band label is also hardcoded (not ACF).
- **CSS-03** · seo-schema · minor · `Status: open` — `[SAMPLE]` prefix in `<title>`/og:title/breadcrumb schema; `-` separator; meta 102 chars. (Noindexed for now.)
- **CSS-04** · design-slop · nice-to-have · `Status: open` — `cs_approach_gallery` unseeded → approach section renders copy-only; seed the gallery or the section reads thin.
- **CSS-05** · accessibility · nice-to-have · `Status: open` — Below-fold sections rely on reveal with only the hero failsafe (hardening, SW-04).

### 3.10 Free Website offer (`/free-website/`, `page-templates/page-free-website.php`)

- **FW-01** · seo-schema · minor · `Status: open` — Title 70 chars ("Free Website for Local Businesses | From £49 a Month | Vulkan Creative") — Google truncates; trim toward 60. (Correct `|` separator, though.)
- **FW-02** · accessibility · minor · `Status: open` — "Included" ledger stamp sampled 2.05:1 in dark mode **mid-transition** (transient reveal state); confirm the settled state passes and the transient isn't visible at rest.
- **FW-03** · design-slop · nice-to-have · `Status: open` — Five identical "INCLUDED" stamps down the ledger read eyebrow-ish; the heading already frames it. Consider dropping to one or none.
- **FW-04** · responsive · nice-to-have · `Status: open` — Contrast section: below md the "usual way" head is hidden so the muted claim can read ambiguously; label it.
- *Strong page overall; good conversion structure, FAQ + Service schema present.*

### 3.11 Your Business (`/your-business/`, `page-templates/page-your-business.php`) — legacy, agreed for migration

This whole template is being migrated to the forge system (your decision). The specific drift to fix:

- **YB-01** · consistency · important · `Status: open` — Headings use legacy `vc-h1`/`vc-h4` + `vc-tag`; migrate to `vc-display-1/2/3` + `vc-eyebrow`.
- **YB-02** · design-slop · important · `Status: open` — **Six `<p class="tag">` eyebrows above every section** (hard house ban). Delete all.
- **YB-03** · design-slop · important · `Status: open` — **Full 2px red borders** around problem cards + solution pillars (red-as-wash ban); replace with surface fill + ember hairline.
- **YB-04** · design-slop · important · `Status: open` — **3px red top strip + 10px corners** on the bespoke testimonial card (accent-strip ban + diverging component); reuse the shared testimonial-spotlight.
- **YB-05** · design-slop · important · `Status: open` — **Giant numbered markers** (pillars 01/02 at 150px, outcomes 01–05); drop, rebuild in a varied forge layout.
- **YB-06** · consistency · important · `Status: open` — `vc-button-big` (10px radius) → `vc-button-forge`/`vc-button-ghost` (2px); all card radii 10px → 2px.
- **YB-07** · accessibility · important · `Status: open` — **No reduced-motion guard** in any yb JS module; content pre-hidden with `opacity:0!important`. Adopt the IntersectionObserver reveal + `prefersReducedMotion()` pattern.
- **YB-08** · responsive · important · `Status: open` — **36px horizontal overflow at 375px** (`docOverflowPx:36`); trace the oversized numeral/row/track and constrain.
- **YB-09** · consistency · minor · `Status: open` — Hardcoded `120px/60px` section + `40px/32px` card padding → `vc-section-padding` + `$space-*`.
- **YB-10** · consistency · minor · `Status: open` — Hardcoded `#222222` CTA background (`_cta.scss:3`) not a token → surface-pair token (also fixes a 4.49:1 red-text contrast on it).
- **YB-11** · consistency · minor · `Status: open` — Surface rhythm wrong: first post-hero section sits on the darker `#121212` pair instead of the lighter `#1E1E1E` pair.
- **YB-12** · design-slop · minor · `Status: open` — Logo-bar `grayscale(0%)` saturation hover (tint-hover ban); remove.
- **YB-13** · accessibility · minor · `Status: open` — Splide pagination dots 24×5 / 40×5px (<44px).
- **YB-14** · copy · minor · `Status: open` — `&mdash;`/`&ldquo;`/`&rdquo;` entities render an em dash in the testimonial cite; replace per style guide.
- **YB-15** · conversion · nice-to-have · `Status: open` — Also receives the shared footer CTA band on top of its own CTA (double conversion moment); see SW-07.

### 3.12 Legal / default template (`page.php`, `/privacy-policy/`, `/cookie-policy/`)

- **LEG-01** · copy · important · `Status: for-Ibrar` — **Broken merge field:** "...Northern Ireland (the), email: creativevulkan@gmail.com" — stray "(the)" placeholder left in.
- **LEG-02** · copy · important · `Status: for-Ibrar` — **Two conflicting addresses** on one page ("Onega House, London DA14 6NE" vs "Onega House, 112 Main Road, London, Sidcup, DA14 6NE") — and **both differ from the footer/Contact address** ("Dawson House, 5 Jewry Street, London, EC3N 2EX"). Three addresses site-wide. Pick one authoritative registered address and use it everywhere.
- **LEG-03** · copy · important · `Status: for-Ibrar` — **DPO contact is a personal Gmail + mobile** (`creativevulkan@gmail.com`, `07804676084`), contradicting the footer's `info@vulkancreative.com` / `020 3576 7525`. Use a branded address + business landline.
- **LEG-04** · design-slop · important · `Status: open` — Section H2s are authored inside `<ol><li>`, so each heading gets a **red decimal marker (1.–8.)** via `.content-area ol li::marker`; sub-lists render "1. Analytics" etc. Restructure to plain `<h2>` + `<ul>`; reserve numbering for real ordered lists.
- **LEG-05** · seo-schema · minor · `Status: open` — No meta description (page is `index,follow`). Add one.
- **LEG-06** · design-slop · minor · `Status: open` — `.content-area` has no `max-width`; prose runs ~90–100ch at 1440 (target 65–75ch). Add a readable measure (the SCSS comment wrongly claims it's constrained).
- **LEG-07** · copy · minor · `Status: for-Ibrar` — Grammar: "third party's" → "third parties"; date format "01-Jun-2025" not the site's shorthand (Jun 2025); doc dated Jun 2025 while today is Jul 2026.
- **LEG-08** · seo-schema · nice-to-have · `Status: open` — Lone "HOME" breadcrumb here too (SW-01).

### 3.13 Blog index (`/blog/`, `home.php`)

- **BI-01** · light-dark · important · `Status: open` — **Dark-mode "Category:" filter label 2.16:1** (`$vc-grey-600` #595959 on `#262626`). The dark override recolours the toggle but not `.insights-filter-toggle-label`. Set it to `$vc-muted-on-dark` in the dark block (SW-06).
- **BI-02** · design-slop · important · `Status: open` — **Imageless cards look unfinished.** ~4 of 12 cards render `.insight-card-media-empty` (flat panel + floating category plate). Give the empty frame a branded treatment (ember glow / clipped wordmark) or enforce featured images. Shared with Author archive (SW covers `_post-grid`).
- **BI-03** · design-slop · important · `Status: open` — **Insight card saturation hover** (`_post-grid.scss:31,46`, `grayscale(0.25)→0`) — tint-hover ban. Shared fix SW-02.
- **BI-04** · copy · minor · `Status: open` — Standfirst uses an em dash (`&mdash;`); replace per style guide (SW-08).
- **BI-05** · seo-schema · nice-to-have · `Status: open` — Title uses `-` separator (SW-03).

### 3.14 Blog single (`single.php` + `template-parts/content-blog.php`)

- **BLOGS-01** · copy · **broken** · `Status: for-Ibrar` — **Content mismatch.** Verified: H1/excerpt/meta/FAQs are "Gemini 3.1 Pro"; every body H2 and 58 mentions are "Claude Sonnet 5". Live and `index,follow`. Reconcile the post (replace the body with real Gemini content **or** retitle to Claude Sonnet 5), and set it to draft/noindex until reconciled. Content/data fix — check `_edit_last`.
- **BLOGS-02** · seo-schema · important · `Status: open` — **Title has no brand suffix** ("Gemini 3.1 Pro: What's New and How to Use It Today", 50 chars, no "| Vulkan Creative"). Add the suffix so it matches every other page (SW-03).
- **BLOGS-03** · copy · minor · `Status: open` — Hero standfirst is verbatim identical to the meta description; differentiate.
- **BLOGS-04** · consistency · minor · `Status: open` — Sidebar TOC omits the FAQ H2 (built only from intro/content headings).
- **BLOGS-05** · accessibility · nice-to-have · `Status: open` — TOC anchors use `sanitize_title()` on raw heading text; duplicate/identical headings collide. De-dupe ids.
- **BLOGS-06** · seo-schema · nice-to-have · `Status: open` — Meta 140 chars; nudge toward 155.

### 3.15 Category archive (`archive.php` + `template-parts/archive-category.php`, `/blog/category/ai/`)

- **CAT-01** · seo-schema · important · `Status: open` — **No meta description** (AI category has no term description). Add a term description ~150–160 chars (it also renders as the hero standfirst).
- **CAT-02** · light-dark · important · `Status: open` — Same dark-mode "Category:" label 2.16:1 as BI-01 (SW-06).
- **CAT-03** · responsive · important · `Status: open` — **Filter inputs 14–15px** (<16px) → iOS auto-zoom on focus. Set `.insights-filter-input` and `.insights-filter-cat-search` to 16px.
- **CAT-04** · consistency · important · `Status: open` — Visible breadcrumb "Home > Blog" (missing the "AI" crumb) **contradicts the JSON-LD** which has it. Instance of SW-01 (scope the filter to single posts).
- **CAT-05** · seo-schema · minor · `Status: open` — Title "AI Posts - Vulkan Creative" is Yoast's thin default; set a proper category title + `|`.
- **CAT-06** · consistency · nice-to-have · `Status: open` — `archive.php` double `get_header()/get_footer()` (delegates to a partial that calls them again); latent dead path, neutralise (SW-09).

### 3.16 Author archive (`author.php`, `/blog/author/ibrarrkhan/`)

- **AUT-01** · design-slop · important · `Status: open` — Insight card saturation hover (SW-02).
- **AUT-02** · consistency · important · `Status: open` — **Standalone red "AUTHOR" eyebrow above the H1** (`author.php:34`) — the only insights-family header still carrying one; the breadcrumb already says it. Remove.
- **AUT-03** · consistency · minor · `Status: open` — **Author name forced ALL CAPS** ("IBRARR KHAN") by global `.insights-title { text-transform:uppercase }`. A person's name shouldn't be uppercased; override casing on the author variant.
- **AUT-04** · design-slop · minor · `Status: open` — Three imageless cards (shared with BI-02).
- **AUT-05** · accessibility · minor · `Status: open` — Footer socials 22×22 + card category plates 37–50×27px (<44px).

### 3.17 Search results (`search.php`, `/?s=…`)

- **SR-01** · conversion · important · `Status: open` — **No search input on the results page.** `home.php`/category include `template-parts/insights-filter.php`; `search.php` doesn't, so users can't refine/correct without editing the URL. Add `get_template_part('template-parts/insights-filter')` above the grid (the archive-blog JS already loads on `is_search()`).
- **SR-02** · conversion · important · `Status: open` — **Dead-end empty state.** "No results… Try a different phrase." with no field to type into and no suggested links. Once SR-01 lands it gives the field; also add 2–3 hub links (Insights, Our Work, Case Studies).
- **SR-03** · design-slop · minor · `Status: open` — One result pins to a narrow `col-lg-4` third with two-thirds blank; handle sparse result counts.
- **SR-04** · seo-schema · minor · `Status: open` — Query is `['post','video']` but every result renders through `content-card.php` (built for posts); video results may render oddly.

### 3.18 404 (`404.php` / `content-404.php`, previewed at `/404-preview/`)

- **E404-01** · seo-schema · important · `Status: open` — **The `/404-preview/` page is indexable** (`index,follow`, self-canonical, returns 200). Google can index a "Page not found" page. Set this specific template to noindex in Yoast (the real `404.php` correctly returns 404 status). *Note: this is separate from the local-only redirect behaviour you flagged as staging-fine.*
- **E404-02** · seo-schema · minor · `Status: open` — Title "404 Preview - Vulkan Creative" leaks the internal "Preview" name.
- **E404-03** · copy · minor · `Status: open` — Sub-heading offers to "tell us what you were looking for" but the page only links home/contact (no search field). Align copy to the actual options.
- **E404-04** · accessibility · minor · `Status: open` — Footer socials 22×22 (SW-05).

---

## 4. Site-wide findings

### 4.1 Shared-component fixes (highest leverage — do these first)

- **SW-01** · important · `Status: open` — **Breadcrumb filter strips the terminal crumb.** `inc/filters.php` (`wpseo_breadcrumb_single_link`, ~L111-119) blanks any crumb containing `breadcrumb_last` unconditionally, intended for single posts but firing everywhere. Result: lone red "HOME" above the H1 on Contact, Case studies archive, Category archive and every legal/default page (reads like the banned eyebrow; also contradicts the JSON-LD on category pages). Scope the blanking to `is_singular('post')`; suppress the breadcrumb block entirely where a single "Home" crumb would remain. Clears CON-02, CSA-01, CAT-04, LEG-08 at once.
- **SW-02** · important · `Status: open` — **Tint/saturation hover (banned) in the shared insight card.** `common/_post-grid.scss:30-33,45-48` (+ dark block) flips `grayscale(0.25)→0` on `.insight-card-img` hover/focus. Remove the filter change (keep the `scale(1.06)` inner zoom + border-heat/shadow/title shift). Clears BI-03, AUT-01 and the single-post "More insights" row. Also remove the homepage trust-logo version (HOME-02).
- **SW-03** · important · `Status: open` — **Title separator + brand suffix drift.** Yoast emits `-` on About, Contact, both archives, case-study single, blog index, category, 404 while the homepage uses `|`; Blog single has no brand token at all. Set the separator to `|` in Yoast Search Appearance (or filter `wpseo_title`) and ensure the `%%title%% | Vulkan Creative` template applies to posts. One config change across 8+ pages.
- **SW-04** · important · `Status: open` — **Reveal failsafe coverage.** Heroes get a CSS `*-reveal-failsafe` (~2.5–2.8s); below-fold sections don't. In normal use they reveal correctly on scroll (verified), so this is **hardening**, not a live break — except WRK-01 which is a genuine threshold bug. Extend the CSS timeout failsafe to all revealed targets and add every below-fold target to `misc/_motion.scss`. Then fix WRK-01's observer specifically.
- **SW-05** · important · `Status: open` — **Tap targets on shared furniture.** Footer social icons 22×22 (`_footer.scss:187-188`); header top-level nav links `padding:8px 12px` on 13px ≈ 31–36px tall (`header/components/_desktop.scss:80`); mobile hamburger 38×38 and theme toggle 32×32. Pad all to ≥44px (sub-menu items already do). Clears the per-page tap-target findings on every page.
- **SW-06** · important · `Status: open` — **Dark-mode "Category:" filter label 2.16:1.** `archive/components/_posts.scss` dark block recolours the toggle but not `.insights-filter-toggle-label`. Add `.insights-filter-toggle-label { color:$vc-muted-on-dark !important; }`. Clears BI-01, CAT-02.
- **SW-07** · minor · `Status: open` — **Footer CTA-hide logic** (`footer.php:12-17`) doesn't exclude your-business, which has its own closing CTA → double CTA band (YB-15). Add it to the hide list.
- **SW-08** · minor · `Status: open` — **Entity/em-dash copy sweep.** `&nbsp;` (Services hub), `&mdash;`/`&ldquo;`/`&rdquo;` (your-business), `&mdash;` (Blog index), rendered "— Emily". Grep templates + ACF-seeded content for the em-dash character and entity codes; replace with plain characters per the style guide.
- **SW-09** · nice-to-have · `Status: open` — **`archive.php` double header/footer.** It calls `get_header()/get_footer()` then delegates to a partial that calls them again; renders cleanly only because CPTs use their own templates. Neutralise so it can't double-render if ever used.
- **SW-10** · nice-to-have · `Status: open` — **Footer code-quality:** legal links use bare relative `/privacy-policy` without `esc_url(home_url())` (the CTA does it correctly); the "Cookie Settings" link points at `/cookie-policy` but consent is a CookieYes JS banner, not that URL (mislabelled). Fix link construction + relabel.
- **SW-11** · nice-to-have · `Status: open` — **Dead body-class logic:** `custom_body_classes` still branches on the `practice_area` taxonomy (`filters.php:79-88`), which isn't registered in this theme. Remove.

### 4.2 Style-consistency section (drift from the design system)

- **Colour:** legacy `#222222` hardcoded (your-business CTA, YB-10); red used as a wash/frame in banned ways (your-business red-bordered cards + top strips YB-03/04; homepage service-card 3px red hover strip HOME-05; About values four giant red words AB-04); several transient red-below-4.5:1 paint states (Contact "what happens next" index, Free Website "Included" stamp) plus one steady-state fail (BI-01/SW-06); your-business white-on-red button 3.55:1 (YB, under the 3.6:1 documented floor).
- **Type/fonts:** your-business is the sole off-forge template (legacy `vc-h1`/`vc-h4`/`vc-tag`/`vc-button-big`) — YB-01/06; author name forced ALL CAPS (AUT-03); blog single missing brand suffix (BLOGS-02/SW-03).
- **Spacing/rhythm:** your-business hardcoded padding + wrong surface-pair order (YB-09/11); legal page no max-width measure (LEG-06); sparse-grid dead space with no odd-count treatment (case studies CSA-02, search SR-03, imageless-card holes BI-02/AUT-04).
- **Buttons/links:** corner-radius drift (your-business 10px vs forge 2px, YB-06); duplicate same-label CTAs (case study single CSS-02); hardcoded non-ACF CTA copy (SVC-04, CSS-02).
- **Heading treatments:** legal H2s numbered by `<ol>` markers (LEG-04); author "AUTHOR" eyebrow (AUT-02); your-business six eyebrow tags (YB-02).
- **Motion:** your-business modules have no `prefersReducedMotion()` guard and pre-hide with `opacity:0!important` (YB-07); reveal-failsafe coverage gap is itself a consistency drift (SW-04).

*Consistent and correct across the site (positives worth preserving):* the surface-pair rhythm on every forge template; `:focus-visible` red rings; reduced-motion parity (except your-business); structured data coverage (Organization, Service, CreativeWork, FAQPage, Article, BreadcrumbList, Person); the shared enquiry-form styling and branded error treatment; sharp 2px corners on forge components.

---

## 5. Quick wins vs larger jobs

**Quick wins (config or a few lines, high value):**
- SW-03 Yoast separator + brand suffix (config).
- SW-01 breadcrumb filter scope (one `if`).
- CON-01, CAT-01, LEG-05 add meta descriptions (Yoast fields).
- SW-06 / BI-01 / CAT-02 dark-mode label colour (one rule).
- SW-02 remove insight-card + trust-logo saturation hover (delete a few lines).
- SW-05 tap-target padding (footer socials, nav links).
- SR-01 add the shared search partial to `search.php` (one `get_template_part`).
- E404-01 noindex the 404-preview (Yoast).
- SW-08 entity/em-dash sweep.

**Larger jobs:**
- WRK-01 rework the work-archive reveal observer (+ SW-04 failsafe across templates).
- YB-* full `/your-business/` migration to the forge system.
- LEG-04 restructure the legal document markup (H2s out of the `<ol>`, sub-lists to `<ul>`).
- BI-02 design the imageless-card empty state.
- HOME-01 shared rolling-word `aria-hidden` handling.

**For Ibrar (content/data — not code, or need your decision):**
- BLOGS-01 Gemini/Claude post reconciliation.
- LEG-01/02/03/07 Privacy Policy content (merge field, addresses, DPO contact, grammar/dates).
- SHUB-02, SVC-01, WKS-01, CSS-03 `[SAMPLE]` content on live/sample pages (replace or keep noindexed).

---

*Local-environment artefacts excluded from findings (verified as staging-fine per your note): the real-404 → homepage redirect; the CookieYes console error rejecting the `.test` domain. The `[SAMPLE]`/noindex state on work and case-study pages is expected until real client content lands and is flagged, not "fixed".*
