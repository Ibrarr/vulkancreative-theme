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

Follow-up round 5 (your-business section redesign, your feedback "still feels disconnected", 6 Jul 2026):

The token migration made yb forge-compatible but its section composition still read as generic. Diagnosed the specific tells against the homepage + About forge references (taste-skill + impeccable + ui-ux-pro-max) and elevated each section. All style-only — no copy, content or ACF data-source changes. Verified live at 1440 + 375 dark (yb is dark-forced), 0px overflow, no new console errors.

- **YB-CAPS** `Status: done` — every forge hero and section head runs all-caps (`.home.page`/`.about` both carry `section .content h2 { text-transform: uppercase }`); yb didn't. Added the rule (hero h1 + section h2) to `_your-business.scss`. This was the single biggest "feels different" tell — the page now reads "READY TO FORGE AHEAD?", "SOUND FAMILIAR?", "THE RESULTS THAT MATTER" etc. like the rest of the site.
- **YB-OUTCOMES-LEDGER** `Status: done` — the outcomes list was a flat stack of 5 rows each with an identical ember `border-top` (the exact "repeated identical mark = AI scaffolding" tell the tick-restraint decision warns about). Rebuilt as the forge ember-rail ledger (the About `how` treatment): one scrubbed grey→red rail binds the rows by proximity, rows are clean title + muted-body. Added the rail markup, the `outcomes.js` matchMedia scrub (mirrors `about/how.js`, removed the dead `.tag`/`.outcome-number` code), and the reduced-motion safety-net entry (`.yb-outcomes .outcomes-progress`).
- **YB-TESTIMONIAL** `Status: done` — the trust testimonial was a boxy 2-up card carousel; the forge house treatment is the single open editorial spotlight. Restyled to one quote per view (Splide `perPage: 1`): oversized red quote mark, large Poppins quote, ember hairline over a quiet normal-case cite (no box, no italic). Keeps the yb ACF data source (`yb_trust_testimonials`), so no content change.
- **YB-SOLUTION-HEAD** `Status: done` — the solution head was the only centre-aligned section head on the whole site (forge is consistently left). Left-aligned it and constrained the sub-heading to a 62ch measure.
- **YB-GLOW** `Status: done` — molten punctuation was only on the hero. Added the sparing forge glow to the solution (#121212 anchor, lower-left bloom) and the CTA (#1E1E1E close, top-right bloom) so the page bookends against the hero glow, matching how the homepage punctuates its dark bands.

Verified-not-an-issue / parked:

- **SW-09** `Status: parked` — checked live: category pages render exactly one header/footer, so the `archive.php` double-`get_header` doesn't manifest. Left untouched to avoid breaking a working page.
- **SW-10b** `Status: for-Ibrar` — "Cookie Settings" links the cookie *policy*, not a consent manager. URL now escaped; the label/target mismatch is a decision for you (relabel to "Cookie Policy", or wire it to the CookieYes banner).
- **Numbered markers** (HOME-04, HOME-08 mobile nav, SHUB-01, CON-04) `Status: for-Ibrar` — awaiting your call (see question below). Sequential Process numerals stay regardless.

Ignored per your instruction: **BLOGS-01** (Gemini/Claude post — local test data).

Still to do: the content/`[SAMPLE]` items left to you (§5). The your-business forge migration (YB-*) and section redesign (YB-CAPS/OUTCOMES-LEDGER/TESTIMONIAL/SOLUTION-HEAD/GLOW) are both done; the only remaining yb legacy item is its single-field `yb_heading()` headings (not yet on the three-part `_start`/`_red`/`_end` system), parked as a non-visual editor-tidiness task.

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

- **HOME-01** · accessibility · important · `Status: done` — **Hero H1 run-on for screen readers.** The four rolling words (`brands`/`websites`/`marketing`/`content`) all stay `visibility:visible` in the DOM, so the accessible H1 reads "We forge brands websites marketing content built to perform." Mark the three inactive words `aria-hidden="true"` (toggling as they rotate) so AT/SEO see "We forge brands built to perform." Keep all words in the DOM. Build the fix into the shared rolling-word module (also used on your-business).
- **HOME-02** · design-slop · important · `Status: open` — **Trust logos saturate on hover (banned).** `_testimonials.scss:60` flips `grayscale(1)→grayscale(0)` on hover of non-interactive `<img>` logos; not gated behind `@media (hover:hover)`. Remove the hover filter; keep logos static. (Shared fix SW-02 covers the card version.)
- **HOME-03** · design-slop · minor · `Status: open` — **Marquee/trust logos opacity hover.** `_hero.scss:221-223` and `_testimonials.scss:89-91` shift opacity on hover of non-interactive logos, ungated for touch. Remove or gate behind `@media (hover:hover)`.
- **HOME-04** · design-slop · nice-to-have · `Status: done` — **Service card `01–06` numerals.** Decorative numbered-marker scaffolding on a non-sequential set. Demote or drop the `.service-index` (keep the numerals on the genuinely sequential Process rail). Also appears on the Services hub (see SHUB-01).
- **HOME-05** · design-slop · nice-to-have · `Status: done` — **Service card 3px red top strip on hover** (`_services.scss:64-75`). A solid red top bar reads closer to the banned top-accent strip than to "border heat". Prefer border-heat/glow only.
- **HOME-06** · performance · nice-to-have · `Status: open` (artefact-corrected) — **No time-based reveal failsafe below the hero.** Counters and section reveals *do* fire correctly on scroll (verified live: `0+`→`120+`, `4.9`, `2.3x`, `10+`). The "stuck at 0/blank" the analysis saw was a screenshot artefact. Remaining point: unlike the hero, below-fold targets have no CSS timeout failsafe if `fonts.ready` ever stalls. Hardening only — fold into SW-04.
- **HOME-07** · seo-schema · minor · `Status: open` — **Title front-loads a soft phrase.** "Turning Creative Sparks Into Powerful Brands | Vulkan Creative" leads with brand poetry, not core service keywords. Consider fronting a service/keyword phrase. (Low urgency — it's the one page with the correct `|` separator.)
- **HOME-08** · design-slop · minor · `Status: done` — **Mobile menu numbers nav items `01–06`.** Verified in the open overlay: each nav link carries a small red numeral. A nav is not an ordered sequence; remove the numerals (numbered-marker slop on a primary surface).

*Positive:* keyboard `:focus-visible` rings (2px red) present; desktop "What We Do" dropdown is legible in light mode (white panel, `#121212` links); mobile menu is a full-height overlay with the CTA reachable.

### 3.2 About (`/about/`, `page-templates/page-about-us.php`)

- **AB-01** · seo-schema · important · `Status: open` — **Bare title + wrong separator.** "About - Vulkan Creative" (23 chars): no keywords/value prop, and `-` vs the house `|`. Set a Yoast title ~50–60 chars with `|`, e.g. "Meet the Founders | Vulkan Creative". (Separator is the site-wide SW-03.)
- **AB-02** · seo-schema · minor · `Status: open` — Meta description 124 chars; extend toward 150–160.
- **AB-03** · performance · nice-to-have · `Status: done` — Founder fallback headshot `avatar-placeholder` is a **1.37MB 1000×1000 PNG** (real portraits are correct 800×800 webp). Replace the fallback with an optimised webp.
- **AB-04** · design-slop · minor · `Status: done` — Values section base/reduced-motion state paints four **giant solid-red words** (a red wash, not an accent). Ensure the no-JS/reduced-motion fallback keeps red as an accent.
- **AB-05** · conversion · nice-to-have · `Status: open` — No in-body enquiry CTA across five sections; only the footer band converts. Consider one mid-page CTA.
- **AB-06** · conversion · nice-to-have · `Status: open` — "Watch the Film" scrolls to `#watch` but doesn't start playback; consider auto-playing on click.

### 3.3 Contact (`/contact/`, `page-templates/page-contact-us.php`)

- **CON-01** · seo-schema · important · `Status: open` — **No meta description at all** (no `<meta name="description">`, no og/twitter description). Add a Yoast description ~150–160 chars using the one-working-day promise. Primary enquiry landing page.
- **CON-02** · design-slop · important · `Status: done` — **Lone "HOME" breadcrumb reads as the banned eyebrow** above the H1 (instance of SW-01). A one-item breadcrumb also gives no wayfinding. Fix via SW-01 (suppress single-crumb breadcrumbs).
- **CON-03** · consistency · minor · `Status: open` (artefact-corrected) — **"What happens next" reveal + lopsided column.** The block sits at the very bottom edge on load (895px of 911px) so it reveals correctly on scroll (not a broken reveal). The real, minor issue is the desktop left column ending early beside the tall form, leaving a void. Balance the column / lower the observer threshold as hardening.
- **CON-04** · design-slop · minor · `Status: open` — "What happens next" `01/02/03` numerals (numbered-marker scaffolding). These *are* a sequence, so lower priority than the service-card case; judge on balance.
- **CON-05** · seo-schema · minor · `Status: open` — Title bare "Contact - Vulkan Creative" (25 chars); add keyword/location and the `|` separator.

*Verified:* Form 5 (multi-step, 4 steps) validation works; branded red per-field errors render; the dark-mode summary banner is legible (white text in a red-bordered box). **But** see SW-06-related CON note: the GF error-summary *list links* are unstyled in dark mode →

- **CON-06** · light-dark · important · `Status: done` — **GF validation summary list links unreadable in dark mode.** `.gform_validation_error_list li a` keep GF/Orbital's ~`#212529` on the `#262626` dark form surface (~1.05:1). Add a dark-mode colour override in `common/_form-errors.scss`. (Trigger a failed step to confirm.)

### 3.4 Services hub (`/services/`, `page-templates/page-services-hub.php`)

- **SHUB-01** · design-slop · important · `Status: done` — **Two stacked `01–0N` numeral systems** (service cards 01–06 + process 01–04). Drop the service-card numerals (the `--related` variant already omits them); keep them only on the sequential process rail.
- **SHUB-02** · conversion · important · `Status: for-Ibrar` — **Indexable hub shows fabricated testimonials + unbacked "4.9" chip.** Names/companies are `[SAMPLE]` seeds; no `AggregateRating` schema backs the 4.9. Either noindex the hub until real quotes land (as work/case-studies already are) or swap in real, attributable quotes and back the rating.
- **SHUB-03** · design-slop · minor · `Status: open` — 6-up equal-column grid of structurally identical cards (icon/numeral + title + 3-line clamp + arrow) risks templated-grid slop; vary rhythm or lean on the varying watermark icons.
- **SHUB-04** · consistency · nice-to-have · `Status: done` — `&nbsp;` entity in the spotlight counter (`page-services-hub.php:206`). House rule: plain characters, never entity codes. (Part of the site-wide entity/em-dash sweep SW-08.)
- **SHUB-05** · seo-schema · minor · `Status: open` — Title 66 chars / meta 164 chars both run slightly long; trim toward 60 / 155.

### 3.5 Service pages (`taxonomy-service.php`, e.g. `/services/web-design-development/`)

- **SVC-01** · copy · important · `Status: for-Ibrar` — **`[SAMPLE]` zeroed stats live** ("0.0x / 0% / 0.0s" with `[SAMPLE]` labels) and `[SAMPLE]` case-study strip copy. Noindexed for now; replace `sv_results_stats` + intro/deliverables copy with real figures before indexing, or clear the sample rows (the results anchor hides when empty).
- **SVC-02** · seo-schema · important · `Status: done` (Aug 2026, closed by the Content & Social work: every service term is now indexable, so Yoast emits the self-canonical; verified on the new child and on Paid Social) — **No canonical emitted** (Yoast suppresses it under noindex). Add a go-live check / `wpseo_canonical` for `is_tax('service')` so each indexable term self-canonicalises (the system supports `?service=` variants elsewhere).
- **SVC-03** · seo-schema · minor · `Status: open` — Meta description 124 chars; extend toward 150–160.
- **SVC-04** · consistency · nice-to-have · `Status: open` — Hardcoded CTA copy not in ACF: deliverables nudge "Scoped to your goals, priced before we start." and the CTA cell label (`taxonomy-service.php:236`). Move to ACF per the "every editable string in ACF" rule.
- *Cross-instance:* branding term spot-checked; same template, same behaviour. No per-instance breakage.

### 3.6 Work archive (`/work/`, `archive-project.php`)

- **WRK-01** · responsive · **broken** · `Status: done` — **Grid invisible on desktop load.** Verified live at 1440×911, scrollY 0: all 10 `.work-card` at `opacity:0` even after 2s; they flip to `1` only after scrolling ~1,100px. Cause: `assets/js/project/reveal.js` observes the 2,408px-tall `[data-work-grid]` container at threshold 0.12, but <12% is in view on load, so the IntersectionObserver never fires. Fix: observe cards individually (the homepage `our-work.js` precedent) or add an immediate-reveal for any card intersecting at `observe()` time. Mobile (375) is fine. **This is the #1 fix.**
- **WRK-02** · seo-schema · minor · `Status: open` — Title "Our Work - Vulkan Creative" uses `-` (SW-03); no canonical (`?service=` variants exist); meta 130 chars (short).
- **WRK-03** · accessibility · minor · `Status: done` — Footer social icons 22×22 (shared, SW-05).
- *Noindexed sample content — flag, don't "fix" the copy.*

### 3.7 Work single (`single-project.php`, e.g. `/work/sample-northbridge-property-group/`)

- **WKS-01** · consistency · important · `Status: for-Ibrar` — **Wrong client's hero image.** The Northbridge (property developer) page shows `aldermere-events-collateral-*.jpg` with alt "Aldermere Events lanyards, wristbands and tickets". Since the sample is the editorial template, fix the seed so the hero image + alt match the client. (Sample data, but it ships as the copy-paste template.)
- **WKS-02** · accessibility · minor · `Status: done` — Service taxonomy links in hero meta + fact ledger ~20px tall on mobile (<44px).
- **WKS-03** · accessibility · minor · `Status: done` — Footer socials 22×22 (SW-05).
- **WKS-04** · seo-schema · nice-to-have · `Status: parked` — CreativeWork `dateCreated` is a bare year ("2025"); a full ISO date is a stronger signal.

### 3.8 Case studies archive (`/case-studies/`, `archive-case_study.php`)

- **CSA-01** · seo-schema · important · `Status: done` — Lone "HOME" breadcrumb (instance of SW-01) — "Home > Case Studies" collapses to "Home".
- **CSA-02** · design-slop · minor · `Status: done` — 3 cards in a 2-up grid leaves ~half the second row blank; no odd-count treatment. Add a fill/centre rule.
- **CSA-03** · conversion · minor · `Status: done` — Filter shows 6 service chips but only 3 map to content; consider hiding empty chips.
- **CSA-04** · seo-schema · nice-to-have · `Status: open` — Title "Case Studies - Vulkan Creative" (30 chars) thin + `-` separator (SW-03).

### 3.9 Case study single (`single-case_study.php`)

- **CSS-01** · seo-schema · minor · `Status: done` (corrected) — **JSON-LD keywords double-encode the ampersand** (`"Web Design &amp; Development"`). Verified: the *visible* on-page labels are correct; only the structured-data keyword string is affected. Fix the JSON encoding in `single-case_study.php` (use `wp_json_encode`, not `esc_html`, for the keywords). Not a DB change.
- **CSS-02** · conversion · minor · `Status: parked` — Duplicate "Start a Project" CTAs (results-band ghost + closing primary) to the same `/contact/`; the results-band label is also hardcoded (not ACF).
- **CSS-03** · seo-schema · minor · `Status: open` — `[SAMPLE]` prefix in `<title>`/og:title/breadcrumb schema; `-` separator; meta 102 chars. (Noindexed for now.)
- **CSS-04** · design-slop · nice-to-have · `Status: open` — `cs_approach_gallery` unseeded → approach section renders copy-only; seed the gallery or the section reads thin.
- **CSS-05** · accessibility · nice-to-have · `Status: open` — Below-fold sections rely on reveal with only the hero failsafe (hardening, SW-04).

### 3.10 Free Website offer (`/free-website/`, `page-templates/page-free-website.php`)

- **FW-01** · seo-schema · minor · `Status: open` — Title 70 chars ("Free Website for Local Businesses | From £49 a Month | Vulkan Creative") — Google truncates; trim toward 60. (Correct `|` separator, though.)
- **FW-02** · accessibility · minor · `Status: done` — "Included" ledger stamp sampled 2.05:1 in dark mode **mid-transition** (transient reveal state); confirm the settled state passes and the transient isn't visible at rest.
- **FW-03** · design-slop · nice-to-have · `Status: open` — Five identical "INCLUDED" stamps down the ledger read eyebrow-ish; the heading already frames it. Consider dropping to one or none.
- **FW-04** · responsive · nice-to-have · `Status: open` — Contrast section: below md the "usual way" head is hidden so the muted claim can read ambiguously; label it.
- *Strong page overall; good conversion structure, FAQ + Service schema present.*

### 3.11 Your Business (`/your-business/`, `page-templates/page-your-business.php`) — legacy, agreed for migration

This whole template is being migrated to the forge system (your decision). The specific drift to fix:

- **YB-01** · consistency · important · `Status: superseded` — Headings use legacy `vc-h1`/`vc-h4` + `vc-tag`; migrate to `vc-display-1/2/3` + `vc-eyebrow`.
- **YB-02** · design-slop · important · `Status: superseded` — **Six `<p class="tag">` eyebrows above every section** (hard house ban). Delete all.
- **YB-03** · design-slop · important · `Status: superseded` — **Full 2px red borders** around problem cards + solution pillars (red-as-wash ban); replace with surface fill + ember hairline.
- **YB-04** · design-slop · important · `Status: superseded` — **3px red top strip + 10px corners** on the bespoke testimonial card (accent-strip ban + diverging component); reuse the shared testimonial-spotlight.
- **YB-05** · design-slop · important · `Status: superseded` — **Giant numbered markers** (pillars 01/02 at 150px, outcomes 01–05); drop, rebuild in a varied forge layout.
- **YB-06** · consistency · important · `Status: superseded` — `vc-button-big` (10px radius) → `vc-button-forge`/`vc-button-ghost` (2px); all card radii 10px → 2px.
- **YB-07** · accessibility · important · `Status: superseded` — **No reduced-motion guard** in any yb JS module; content pre-hidden with `opacity:0!important`. Adopt the IntersectionObserver reveal + `prefersReducedMotion()` pattern.
- **YB-08** · responsive · important · `Status: superseded` — **36px horizontal overflow at 375px** (`docOverflowPx:36`); trace the oversized numeral/row/track and constrain.
- **YB-09** · consistency · minor · `Status: superseded` — Hardcoded `120px/60px` section + `40px/32px` card padding → `vc-section-padding` + `$space-*`.
- **YB-10** · consistency · minor · `Status: superseded` — Hardcoded `#222222` CTA background (`_cta.scss:3`) not a token → surface-pair token (also fixes a 4.49:1 red-text contrast on it).
- **YB-11** · consistency · minor · `Status: superseded` — Surface rhythm wrong: first post-hero section sits on the darker `#121212` pair instead of the lighter `#1E1E1E` pair.
- **YB-12** · design-slop · minor · `Status: superseded` — Logo-bar `grayscale(0%)` saturation hover (tint-hover ban); remove.
- **YB-13** · accessibility · minor · `Status: superseded` — Splide pagination dots 24×5 / 40×5px (<44px).
- **YB-14** · copy · minor · `Status: superseded` — `&mdash;`/`&ldquo;`/`&rdquo;` entities render an em dash in the testimonial cite; replace per style guide.
- **YB-15** · conversion · nice-to-have · `Status: superseded` — Also receives the shared footer CTA band on top of its own CTA (double conversion moment); see SW-07.

### 3.12 Legal / default template (`page.php`, `/privacy-policy/`, `/cookie-policy/`)

- **LEG-01** · copy · important · `Status: for-Ibrar` — **Broken merge field:** "...Northern Ireland (the), email: creativevulkan@gmail.com" — stray "(the)" placeholder left in.
- **LEG-02** · copy · important · `Status: for-Ibrar` — **Two conflicting addresses** on one page ("Onega House, London DA14 6NE" vs "Onega House, 112 Main Road, London, Sidcup, DA14 6NE") — and **both differ from the footer/Contact address** ("Dawson House, 5 Jewry Street, London, EC3N 2EX"). Three addresses site-wide. Pick one authoritative registered address and use it everywhere.
- **LEG-03** · copy · important · `Status: for-Ibrar` — **DPO contact is a personal Gmail + mobile** (`creativevulkan@gmail.com`, `07804676084`), contradicting the footer's `info@vulkancreative.com` / `020 3576 7525`. Use a branded address + business landline.
- **LEG-04** · design-slop · important · `Status: open` — Section H2s are authored inside `<ol><li>`, so each heading gets a **red decimal marker (1.–8.)** via `.content-area ol li::marker`; sub-lists render "1. Analytics" etc. Restructure to plain `<h2>` + `<ul>`; reserve numbering for real ordered lists.
- **LEG-05** · seo-schema · minor · `Status: open` — No meta description (page is `index,follow`). Add one.
- **LEG-06** · design-slop · minor · `Status: done` — `.content-area` has no `max-width`; prose runs ~90–100ch at 1440 (target 65–75ch). Add a readable measure (the SCSS comment wrongly claims it's constrained).
- **LEG-07** · copy · minor · `Status: for-Ibrar` — Grammar: "third party's" → "third parties"; date format "01-Jun-2025" not the site's shorthand (Jun 2025); doc dated Jun 2025 while today is Jul 2026.
- **LEG-08** · seo-schema · nice-to-have · `Status: done` — Lone "HOME" breadcrumb here too (SW-01).

### 3.13 Blog index (`/blog/`, `home.php`)

- **BI-01** · light-dark · important · `Status: done` — **Dark-mode "Category:" filter label 2.16:1** (`$vc-grey-600` #595959 on `#262626`). The dark override recolours the toggle but not `.insights-filter-toggle-label`. Set it to `$vc-muted-on-dark` in the dark block (SW-06).
- **BI-02** · design-slop · important · `Status: done` — **Imageless cards look unfinished.** ~4 of 12 cards render `.insight-card-media-empty` (flat panel + floating category plate). Give the empty frame a branded treatment (ember glow / clipped wordmark) or enforce featured images. Shared with Author archive (SW covers `_post-grid`).
- **BI-03** · design-slop · important · `Status: done` — **Insight card saturation hover** (`_post-grid.scss:31,46`, `grayscale(0.25)→0`) — tint-hover ban. Shared fix SW-02.
- **BI-04** · copy · minor · `Status: done` — Standfirst uses an em dash (`&mdash;`); replace per style guide (SW-08).
- **BI-05** · seo-schema · nice-to-have · `Status: done` — Title uses `-` separator (SW-03).

### 3.14 Blog single (`single.php` + `template-parts/content-blog.php`)

- **BLOGS-01** · copy · **broken** · `Status: done` — **Content mismatch.** Verified: H1/excerpt/meta/FAQs are "Gemini 3.1 Pro"; every body H2 and 58 mentions are "Claude Sonnet 5". Live and `index,follow`. Reconcile the post (replace the body with real Gemini content **or** retitle to Claude Sonnet 5), and set it to draft/noindex until reconciled. Content/data fix — check `_edit_last`.
- **BLOGS-02** · seo-schema · important · `Status: open` — **Title has no brand suffix** ("Gemini 3.1 Pro: What's New and How to Use It Today", 50 chars, no "| Vulkan Creative"). Add the suffix so it matches every other page (SW-03).
- **BLOGS-03** · copy · minor · `Status: done` — Hero standfirst is verbatim identical to the meta description; differentiate.
- **BLOGS-04** · consistency · minor · `Status: done` — Sidebar TOC omits the FAQ H2 (built only from intro/content headings).
- **BLOGS-05** · accessibility · nice-to-have · `Status: done` — TOC anchors use `sanitize_title()` on raw heading text; duplicate/identical headings collide. De-dupe ids.
- **BLOGS-06** · seo-schema · nice-to-have · `Status: done` — Meta 140 chars; nudge toward 155.

### 3.15 Category archive (`archive.php` + `template-parts/archive-category.php`, `/blog/category/ai/`)

- **CAT-01** · seo-schema · important · `Status: done` — **No meta description** (AI category has no term description). Add a term description ~150–160 chars (it also renders as the hero standfirst).
- **CAT-02** · light-dark · important · `Status: done` — Same dark-mode "Category:" label 2.16:1 as BI-01 (SW-06).
- **CAT-03** · responsive · important · `Status: done` — **Filter inputs 14–15px** (<16px) → iOS auto-zoom on focus. Set `.insights-filter-input` and `.insights-filter-cat-search` to 16px.
- **CAT-04** · consistency · important · `Status: done` — Visible breadcrumb "Home > Blog" (missing the "AI" crumb) **contradicts the JSON-LD** which has it. Instance of SW-01 (scope the filter to single posts).
- **CAT-05** · seo-schema · minor · `Status: open` — Title "AI Posts - Vulkan Creative" is Yoast's thin default; set a proper category title + `|`.
- **CAT-06** · consistency · nice-to-have · `Status: open` — `archive.php` double `get_header()/get_footer()` (delegates to a partial that calls them again); latent dead path, neutralise (SW-09).

### 3.16 Author archive (`author.php`, `/blog/author/ibrarrkhan/`)

- **AUT-01** · design-slop · important · `Status: done` — Insight card saturation hover (SW-02).
- **AUT-02** · consistency · important · `Status: done` — **Standalone red "AUTHOR" eyebrow above the H1** (`author.php:34`) — the only insights-family header still carrying one; the breadcrumb already says it. Remove.
- **AUT-03** · consistency · minor · `Status: done` — **Author name forced ALL CAPS** ("IBRARR KHAN") by global `.insights-title { text-transform:uppercase }`. A person's name shouldn't be uppercased; override casing on the author variant.
- **AUT-04** · design-slop · minor · `Status: open` — Three imageless cards (shared with BI-02).
- **AUT-05** · accessibility · minor · `Status: open` — Footer socials 22×22 + card category plates 37–50×27px (<44px).

### 3.17 Search results (`search.php`, `/?s=…`)

- **SR-01** · conversion · important · `Status: done` — **No search input on the results page.** `home.php`/category include `template-parts/insights-filter.php`; `search.php` doesn't, so users can't refine/correct without editing the URL. Add `get_template_part('template-parts/insights-filter')` above the grid (the archive-blog JS already loads on `is_search()`).
- **SR-02** · conversion · important · `Status: done` — **Dead-end empty state.** "No results… Try a different phrase." with no field to type into and no suggested links. Once SR-01 lands it gives the field; also add 2–3 hub links (Insights, Our Work, Case Studies).
- **SR-03** · design-slop · minor · `Status: done` — One result pins to a narrow `col-lg-4` third with two-thirds blank; handle sparse result counts.
- **SR-04** · seo-schema · minor · `Status: done` — Query is `['post','video']` but every result renders through `content-card.php` (built for posts); video results may render oddly.

### 3.18 404 (`404.php` / `content-404.php`, previewed at `/404-preview/`)

- **E404-01** · seo-schema · important · `Status: done` — **The `/404-preview/` page is indexable** (`index,follow`, self-canonical, returns 200). Google can index a "Page not found" page. Set this specific template to noindex in Yoast (the real `404.php` correctly returns 404 status). *Note: this is separate from the local-only redirect behaviour you flagged as staging-fine.*
- **E404-02** · seo-schema · minor · `Status: open` — Title "404 Preview - Vulkan Creative" leaks the internal "Preview" name.
- **E404-03** · copy · minor · `Status: done` — Sub-heading offers to "tell us what you were looking for" but the page only links home/contact (no search field). Align copy to the actual options.
- **E404-04** · accessibility · minor · `Status: done` — Footer socials 22×22 (SW-05).

---

## 4. Site-wide findings

### 4.1 Shared-component fixes (highest leverage — do these first)

- **SW-01** · important · `Status: done` — **Breadcrumb filter strips the terminal crumb.** `inc/filters.php` (`wpseo_breadcrumb_single_link`, ~L111-119) blanks any crumb containing `breadcrumb_last` unconditionally, intended for single posts but firing everywhere. Result: lone red "HOME" above the H1 on Contact, Case studies archive, Category archive and every legal/default page (reads like the banned eyebrow; also contradicts the JSON-LD on category pages). Scope the blanking to `is_singular('post')`; suppress the breadcrumb block entirely where a single "Home" crumb would remain. Clears CON-02, CSA-01, CAT-04, LEG-08 at once.
- **SW-02** · important · `Status: done` — **Tint/saturation hover (banned) in the shared insight card.** `common/_post-grid.scss:30-33,45-48` (+ dark block) flips `grayscale(0.25)→0` on `.insight-card-img` hover/focus. Remove the filter change (keep the `scale(1.06)` inner zoom + border-heat/shadow/title shift). Clears BI-03, AUT-01 and the single-post "More insights" row. Also remove the homepage trust-logo version (HOME-02).
- **SW-03** · important · `Status: done` — **Title separator + brand suffix drift.** Yoast emits `-` on About, Contact, both archives, case-study single, blog index, category, 404 while the homepage uses `|`; Blog single has no brand token at all. Set the separator to `|` in Yoast Search Appearance (or filter `wpseo_title`) and ensure the `%%title%% | Vulkan Creative` template applies to posts. One config change across 8+ pages.
- **SW-04** · important · `Status: done` — **Reveal failsafe coverage.** Heroes get a CSS `*-reveal-failsafe` (~2.5–2.8s); below-fold sections don't. In normal use they reveal correctly on scroll (verified), so this is **hardening**, not a live break — except WRK-01 which is a genuine threshold bug. Extend the CSS timeout failsafe to all revealed targets and add every below-fold target to `misc/_motion.scss`. Then fix WRK-01's observer specifically.
- **SW-05** · important · `Status: done` — **Tap targets on shared furniture.** Footer social icons 22×22 (`_footer.scss:187-188`); header top-level nav links `padding:8px 12px` on 13px ≈ 31–36px tall (`header/components/_desktop.scss:80`); mobile hamburger 38×38 and theme toggle 32×32. Pad all to ≥44px (sub-menu items already do). Clears the per-page tap-target findings on every page.
- **SW-06** · important · `Status: done` — **Dark-mode "Category:" filter label 2.16:1.** `archive/components/_posts.scss` dark block recolours the toggle but not `.insights-filter-toggle-label`. Add `.insights-filter-toggle-label { color:$vc-muted-on-dark !important; }`. Clears BI-01, CAT-02.
- **SW-07** · minor · `Status: done` — **Footer CTA-hide logic** (`footer.php:12-17`) doesn't exclude your-business, which has its own closing CTA → double CTA band (YB-15). Add it to the hide list.
- **SW-08** · minor · `Status: done` — **Entity/em-dash copy sweep.** `&nbsp;` (Services hub), `&mdash;`/`&ldquo;`/`&rdquo;` (your-business), `&mdash;` (Blog index), rendered "— Emily". Grep templates + ACF-seeded content for the em-dash character and entity codes; replace with plain characters per the style guide.
- **SW-09** · nice-to-have · `Status: open` — **`archive.php` double header/footer.** It calls `get_header()/get_footer()` then delegates to a partial that calls them again; renders cleanly only because CPTs use their own templates. Neutralise so it can't double-render if ever used.
- **SW-10** · nice-to-have · `Status: done` — **Footer code-quality:** legal links use bare relative `/privacy-policy` without `esc_url(home_url())` (the CTA does it correctly); the "Cookie Settings" link points at `/cookie-policy` but consent is a CookieYes JS banner, not that URL (mislabelled). Fix link construction + relabel.
- **SW-11** · nice-to-have · `Status: done` — **Dead body-class logic:** `custom_body_classes` still branches on the `practice_area` taxonomy (`filters.php:79-88`), which isn't registered in this theme. Remove.

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

---

# Pass 2 (10 Aug 2026)

**Scope:** full re-audit of every surface, plus the surface built since 6 July that pass 1 never saw: the landing template (`/your-business/`, `/seo-ai-search/`), the pillars-and-children services restructure with the dynamic mega menu (24 term pages), the reworked footer and header, forms 7/8/10, and, for the first time, emails. **Method:** desk sweeps (ledger triage against code, SCSS/JS static sweeps, rendered-HTML copy sweep of 93 URLs saved to the session scratchpad), live Playwright passes at 375/768/1024/1440 in both modes with live reveal-integrity probes (full-page captures are treated as unreliable on this theme, per the pass 1 verification note), reduced-motion emulation, keyboard passes, a full form 5 submission (entry deleted after), and a read-only GFAPI audit of all 7 forms' notifications and confirmations.

**Status reconciliation:** 55 body statuses were brought in line with the session 1 fix log and current code (40 to done, the 15 YB items to superseded: `page-your-business.php` was retired and `/your-business/` rebuilt on the landing template). Newly verified fixed since July: **CSA-03** (empty filter chips now hidden), **CSS-01** (`wp_json_encode` in the case study JSON-LD), **FW-02** (the INCLUDED stamp passes at rest; the July sample was mid-transition). Every other July fix held under regression checks (SW-01/02/03/05/06, HOME-01, WRK-01 re-verified at fresh desktop load: 10 of 10 cards visible at scroll zero).

**Pass 2 result in one line:** zero horizontal overflow anywhere at any width in either mode, console first-party clean sitewide, heading order sound, reveal integrity confirmed live on every family; the new findings below are mostly shared-source polish (hover gating, target sizes, tokens), the never-audited email layer, and a handful of real correctness bugs (multi-word search, root-level 404 redirect, empty fallback templates).

## Delta summary

| Bucket | Count |
|---|---|
| July items closed by reconciliation or verified fixed | 43 |
| July items superseded (YB) | 15 |
| July items re-verified still open | ~35 (statuses unchanged below) |
| New pass 2 findings | 44 |

## New findings

### Site-wide (SW, continued)

- **SW-12** · seo-schema · important · `Status: done` — **og:title separator and brand drift on 60 of 92 URLs.** The `wpseo_title` filter fixed `<title>` only: og:title still reads ` - Vulkan Creative` on 46 pages and omits the brand entirely on 18 (posts + authors). og:description is absent on 30 URLs. Fix at source: set Yoast's separator setting to `|` (making the title filter a safety net) and mirror the brand suffix on `wpseo_opengraph_title`.
- **SW-13** · consistency · important · `Status: done` — **Hover styles ungated for touch at source.** `vc-button-forge` (`_mixins.scss:202`) and `vc-button-ghost` (`:233`) have raw `&:hover`, so every button sitewide leaks hover on touch. Same in header (`_header.scss:57,105,125`, `_desktop.scss:94,393`, `_mobile.scss:70,270,339`), footer (`_footer.scss:136,212`), breadcrumbs, body links, testimonial arrows, enquiry buttons (full file list in the static sweep evidence). One asymmetry: `_enquiry-form.scss` gates the dark prev-button hover (`:801`) but not the light twins (`:512,554`). Wrap at the mixin level plus a sweep.
- **SW-14** · accessibility · important · `Status: done` — **Form control sizes under target.** GF submit buttons render 38px tall on every form (measured on forms 2, 3, 5); the blog sidebar newsletter email input is 14px font (the one sub-16px input left on the site, iOS zoom trigger). Lift button height to 44px minimum and the input to 16px in the shared form SCSS.
- **SW-15** · accessibility · minor · `Status: done` — **Sub-24px text-link targets on shared furniture.** Footer menu/services/contact links 20px, legal links 21px, breadcrumb links 17 to 19px, work single fact-ledger service links 20 to 23px, mega child links 33px, mobile menu top-level anchors measured at 27px live (the `min-height` sits on the `li`, verify the `<a>` box at fix). WCAG 2.2 target minimum is 24px unless spacing exempts; pad the anchors, keep 44px for icon controls.
- **SW-16** · consistency · minor · `Status: done` — **Shadow values untokenised.** The card hover glow `0 16px 44px rgba($vc-primary,.22)` and its dark twin `0 18px 56px rgba($vc-primary,.4)` are copy-pasted across 7 files (post-grid, service-card, case-study-card, work-wheel, project index, case-study band, related); `_mixins.scss:206` uses a third value. Hoist to tokens; the glows themselves are sanctioned hover treatment.
- **SW-17** · consistency · minor · `Status: done` — **Raw hex duplicating tokens in 9 component files** (13 code occurrences: `#FFFFFF` for `$vc-surface-white`, `#0D0D0D` for `$vc-background-deep`), plus `#FFFFFF` inside `vc-button-forge` and a hardcoded `--bs-body-bg: #F5F5F5` in `_variables.scss`. Mechanical swap.
- **SW-18** · accessibility · important · `Status: done` — **Content-bearing images marked decorative.** 445 of 606 images carry `alt=""`, including every blog featured hero and every work single screenshot/gallery image. The captioned-card exemption is house style and stays; singles' heroes and galleries need real alt text (an ACF alt or the attachment alt).
- **SW-19** · consistency · minor · `Status: done` — **Dead code.** `homepage/components/_story.scss` (88 lines, orphaned since the story moved to About), `misc/_preloader.scss` (retired, still on disk), `global/smooth-scrolling.js` (fully commented out yet bundled), mixins `vc-button`/`vc-button-big`/`vc-card` (zero call sites, 10px radii), 5 empty SCSS stubs, the stale saturation comment at `_post-grid.scss:5`, and `archive-author.js`/`archive-blog.js`/`single-blog.js` built from identical sources (one bundle would do).
- **SW-20** · consistency · nice-to-have · `Status: done` — **Repo hygiene.** `assets/css/components/.impeccable/hook.cache.json` is tracked and `.impeccable/` is missing from `.gitignore`; 18 source maps are tracked and shipped in `dist/` (keep or strip: decision for Ibrar).
- **SW-21** · copy · minor · `Status: done` — **Entity codes hardcoded in rendering templates.** `&ldquo;` decorative quote marks in 6 files (all inside `aria-hidden` wrappers), `&nbsp;` in headings/counters (`front-page.php:79,83` where a `str_replace` injects it into an ACF-editable heading, `:503`, `page-free-website.php:278`, `taxonomy-service.php:324`, `page-about-us.php:380`, `blocks/testimonials.php:53`, the wheel counter pattern), `&hellip;` in two placeholders, `&ldquo;/&rdquo;` around the search term in `search.php:19`. House rule is plain characters; use real glyphs or CSS.
- **SW-22** · consistency · nice-to-have · `Status: done` — **Radius drift.** `.cite-avatar` at 8px (`_testimonial-spotlight.scss:169`, neither 2px nor a 10px media panel), the open founder-bio text plate at 10px (`_founders.scss:402-490`, 10px is documented for media panels only), and 50% circles on author avatars (sanctioned convention: document it rather than change it).
- **SW-23** · design-slop · minor · `Status: done` — **The grandfathered wheel saturation hover now ships beyond its grandfather.** The tint hover lives in `common/_work-wheel.scss:294-320` and renders on service term pages and project singles via the shared `work-wheel.php`, not just the homepage Our Work section. See Decisions below; Ibrar's call.

### Header and mega menu (MEGA)

- **MEGA-01** · accessibility · important · `Status: done` — **`aria-expanded` points at nothing.** No panel in the dropdown system has an `id` and no trigger has `aria-controls` (desktop mega from `inc/mega-menu.php:81-105` and the mobile disclosure buttons alike). Add ids + `aria-controls`.
- **MEGA-02** · accessibility · minor · `Status: done` — **Touch behaviour at 1100px and up.** The desktop bar shows from 1100px; a tap on What We Do navigates to `/services/` (a reasonable fallback since the hub lists everything) but first fires `mouseenter`, leaving `aria-expanded="true"` stuck on a closed panel. Sync state on `touchstart`/`click`, or accept the navigate-to-hub behaviour and keep the attribute truthful.
- **MEGA-03** · accessibility · minor · `Status: done` — **Escape is inert while focus sits on the parent link.** `dropdown.js:31` requires `activeElement !== link`, so the reachable state (panel open via `:focus-within`, focus on the trigger) cannot be dismissed with Escape. Drop the guard.

### Forms (FRM) and emails (EM), first audit

Verified working end to end: form 5 all four steps, per-step server validation, branded red field errors with the icon in both modes, the dynamic service picker (6 pillars + Something else, icons from term fields, renamed-slug conditional logic firing, entry meta keys correct: test entry 355 saved `1.1=web-design-development`, then deleted), the house spinner + container dim, dataLayer `enquiry_step` ×4 + `enquiry_submit`, focus moved to the confirmation. Email field validation rejects MX-less domains with a well written UK message. FAQ accordions carry a correct two-way ARIA cycle. reCAPTCHA does not render locally (env note: likely unkeyed on this copy).

- **FRM-01** · consistency · important · `Status: done` — **Service term pages hard-lock to form 2.** `taxonomy-service.php:513` calls `vc_render_form( 2 )` with no ACF id, and `group_vc_form_settings` has no taxonomy location rule, so the editor-selectable-form convention silently fails on all 24 term pages. Add a term location rule + pass the term id, or document the hard default.
- **FRM-02** · consistency · minor · `Status: done` — **`blocks/cta.php` defaults to form 10 while its docblock and CLAUDE.md say the landing default is 2.** Both live pages override via the picker so nothing breaks; align code and docs.
- **EM-01** · email · important · `Status: done` — **No reply-to on any notification** (all 7 forms). Replying to an enquiry email does not reach the enquirer. Set replyTo to the form's email merge tag per form.
- **EM-02** · email · minor · `Status: done` — **Empty from-name on all 7** (mails arrive as a bare address). Set a from-name such as "Vulkan Creative Website".
- **EM-03** · email · important · `Status: done` — **All 7 notifications route to `info@vulkancreative.test`.** The enquiry-form doc says "point at the live inbox at deploy" but nothing enforces it. Confirm the live value and add it to the deploy checklist; decide whether the local copy should mirror it.
- **EM-04** · email · important · `Status: done` — **No submitter autoresponder on any form.** Someone who enquires gets no email at all. Proposal pack (per-form subject + body in the house voice) drafted at fix time; applying it is DB work under the agreed approval.
- **EM-05** · email · minor · `Status: done` — Subject drift: forms 2 and 3 say "New submission from", forms 5 to 10 "New enquiry from". Align.
- **EM-06** · copy · minor · `Status: done` — **Form 2's confirmation is off-voice**: "We're excited to build something powerful together... discuss your vision" against form 5's plain one-working-day promise. Rewrite to match the house voice and promise.
- **EM-07** · email · important · `Status: for-Ibrar` — **Deliverability posture:** WordPress default PHP mail(), no SMTP plugin, no code-level from domain. Recommendation is an SMTP route + SPF/DKIM at the host; adding a plugin is a dependency decision for Ibrar.
- Env notes, decisions needed: ActiveCampaign add-on was already inactive with zero feeds (the instructed removal is just deleting the dormant folder, queued in the fix plan); the HubSpot add-on and leadin are both inactive on this copy although form 2 carries a HubSpot feed, so local submissions reach no CRM; Conversational Forms add-on is active but appears unused.

### Correctness

- **UTIL-01** · correctness · important · `Status: open` — **Root-level unknown URLs 301 to the homepage instead of 404** (`/qwertyasdf-nonexistent/` → `/`, and a missing `/wp-content/nope.jpg` returns the homepage HTML). Nested paths 404 correctly. The theme has no such redirect; this is host/server layer on the local copy. Verify on the live host before changing anything; if live matches, soft-404s at scale.
- **SR-05** · correctness · important · `Status: done` — **Multi-word search always returns zero results** (`design` finds 1, `web design` finds 0, reproduced across terms). Single words work. Trace the query handling in `search.php`/the filter JS.
- **IDX-01** · correctness · minor · `Status: done` — **`index.php` is empty** ("Silence is golden"), so any query that ever falls through renders a blank white page with no header or footer. Give it a minimal safe fallback (header + not-found body + footer).
- **ARC-01** · correctness · minor · `Status: done` — **`archive.php` renders an empty body for every non-category archive** (`if (is_category())` with no else); date archives are noindexed but reachable. Add a generic branch or redirect. (Related to parked SW-09, same file.)

### Content, SEO and copy

- **HOME-09** · copy · minor · `Status: done` — The one live em dash on the site: the homepage latest-insights standfirst "brand, web and marketing — what we're learning...". Fix at the source (ACF value or template fallback).
- **AUT-06** · copy · minor · `Status: done` — An author archive is titled with a raw address: "creativevulkan@gmail.com, Author at Vulkan Creative". Set the user's display name (DB).
- **CAT-07** · copy · minor · `Status: done` — **The "Uncategorized" category is live** (9 visible renders + title), the only US spelling on the site. Rename or retire and reassign (DB).
- **CAT-08** · copy · nice-to-have · `Status: done` — Category "Website performance" is sentence case while every sibling is title case (DB).
- **META-01** · seo-schema · important · `Status: done` — **Missing meta descriptions:** `/contact/` (existing CON-01), `/seo-ai-search/`, both legal pages, all 19 category term pages (the term description doubles as the hero standfirst, so writing them pays twice). og:description absent on the same set.
- **META-02** · seo-schema · nice-to-have · `Status: open` — 8 titles above 60 rendered chars (longest 80); `/blog/page/2/` duplicates the index title + description exactly.
- **SEO-01** · seo-schema · minor · `Status: done` — **`/llms.txt` is stale and part broken:** generated by Yoast v27.3 (site runs v28.2), backslash-escaped markdown throughout, one typo ("Fee Business Website"), old post titles, and thin coverage (5 pages, 5 of 15 posts, no services). Regenerate once the services noindex decision lands.
- **LAUNCH-01** · content · important · `Status: for-Ibrar` — **The single launch-readiness ledger item** (absorbs the standing SHUB-02/SVC-01/WKS-01/CSS-03 instances): all 12 projects, all 3 case studies and all 4 testimonials are `[SAMPLE]` data; unmarked sample quotes run on 5 indexable commercial pages (about, services hub, your-business, free-website, seo-ai-search); the pillar results tiles show `[SAMPLE]`-flagged stats; the projects and case studies share identical titles pairwise (latent duplicate-title issue the day they are indexed). Real content or keep the noindexes; Ibrar owns the call and the copy.

### CTA labels

- **SVC-05** · consistency · minor · `Status: done` — "Start a project" (sentence case) on all 24 service term pages, three instances per pillar, against "Start a Project" everywhere else (73 instances). AMA title case per the house rule.
- **SVC-06** · consistency · nice-to-have · `Status: done` — "All services" vs "All Case Studies": sibling view-all CTAs with different casing.
- **LP-02** · copy · minor · `Status: open` — `/seo-ai-search/` uses two labels for one offer: "Get My Free Visibility Report" (hero) and "Get My Free Report" (3 instances). Pick one.
- **LP-03** · copy · nice-to-have · `Status: open` — `/your-business/` pairs "Book a Free Consultation" with "Book a Call" for the same intent.
- **WKS-05** · accessibility · nice-to-have · `Status: done` — "View Live Site" concatenates its screen-reader suffix without a space ("View Live Site(opens in a new tab)"). Add the space inside the sr-only span.

### Landing template

- **LP-01** · correctness · minor · `Status: done` — **The h1 exists only if an editor adds a hero block.** A landing page built without one ships h2s with no h1. Backfill a visually-hidden h1 from the page title when no hero layout is present.
- **FW-05** · consistency · minor · `Status: done` — **The free-website template misses the `enquiry-form` bundle** the landing template deliberately gets: it silently loses the entrance stagger, the `enquiry_step`/`enquiry_submit` dataLayer events, confirmation focus management, and leaves `.form-container.is-submitting` as dead CSS. Enqueue it for parity.

### Motion

- **MO-01** · motion · nice-to-have · `Status: done` — The hero rolling word paints over the H1 line above it mid-swap (seen in stills at both 1440 and capture states; eyeball live at fix and clip the line box if it shows at real speed).
- **MO-02** · motion · minor · `Status: done` — `header/mobile-menu.js:88` uses jQuery `.animate({scrollTop})`, which ignores reduced motion and sits outside the CSS safety net. Guard it or use native `scrollTo`.
- **SW-04 (extended)** · `Status: open` — Pass 2 mapping: hero bands all carry the 2.5 to 2.8s CSS failsafe; below-fold JS pre-hides have none (homepage reveal/work/why/services/our-work, blog family with no failsafe file at all, project/case-study/service/services-hub/about/free-website/landing reveal modules, contact next-steps). Safety-net gaps for JS-only pre-hides: `.checklist-row/total/note` (the landing port dropped what its free-website sibling has), `.why-cell`, `.work .case-row/.case-stage`, `.services-grid .service-card`, `.services .service-row`, `#work-wheel .wheel-card`. Extend `misc/_motion.scss` + the timeout failsafe pattern.

### Documentation

- **DOC-01** · docs · minor · `Status: done` — CLAUDE.md/docs drift: the free-website slug is now `/free-website-for-small-business/`; pillar term pages carry the full single-service layout plus the children grid (not "children grid + enquiry form" only); the YB section here is superseded; several cited line numbers have shifted; `_post-grid.scss` header comment describes a removed hover.

## Decisions and disagreements

1. **Wheel saturation hover beyond the grandfather (SW-23).** taste-skill and impeccable both read the spread as extending a banned pattern and would remove the hover from the shared component. My recommendation: it is the same grandfathered component travelling to new pages, not a new effect, so keep it and record the wider grandfather here. Ibrar decides; if the ban is absolute, the fix is deleting the hover/focus filter blocks in `common/_work-wheel.scss` (both modes).
2. **Article TOC red left-border on hover** extends the sanctioned active-position survivor to hover states. Recommendation (impeccable concurs): keep the active indicator, drop the hover half.
3. **Circle avatars (50%)** on author bylines: convention, recommend documenting as a sanctioned exception rather than changing.
4. **Source maps shipped in dist/**: harmless on an agency site, mildly leaky. Recommend keeping tracked (debugging value) unless Ibrar prefers stripping at build.
5. **Emil overrides:** none required by the audit itself. Any motion fix in batch B4 follows emil-design-eng's bands (press 100 to 160ms, UI under 300ms, ease-out entries, hover gated, reduced motion honoured); if any of those numbers override a CLAUDE.md example value, it gets listed here at fix time.
6. **taste-skill vs house style:** taste-skill's greenfield rules (icon libraries, React defaults) were treated as out of scope; its slop lens agreed with the house bans everywhere it applied. No conflicts to arbitrate beyond item 1.

## Open questions for Ibrar

1. HubSpot: the add-on and leadin are inactive on this local copy although form 2 has a HubSpot feed. Is live different, and should local mirror live?
2. Conversational Forms add-on is active but unused: deactivate?
3. Deliverability (EM-07): want an SMTP plugin recommendation (a new dependency), or handled at the host?
4. Live notification recipient at deploy, and do you want submitter autoresponders (EM-03/04)?
5. When do the 24 service pages get indexed (they are noindexed pending real stats/copy, and llms.txt regeneration waits on this)?
6. UTIL-01: does the live host also redirect unknown root-level URLs to the homepage?
7. SW-10b stands: relabel "Cookie Settings" to "Cookie Policy", or wire it to the CookieYes banner?
8. Uncategorized category (CAT-07): rename to what, or retire?
9. Source maps in production dist/ (SW-20): keep or strip?

## Pass 2 fix plan (awaiting approval)

Branch: `fix/frontend-audit-pass-2` off `staging` (this commit). Build once per batch (`npm run production`), verify per batch at 375/768/1024/1440 in both modes on affected families plus shared-component consumers, console first-party clean. New findings discovered while fixing get filed here, not chased. Anything bigger than its description stops and reports.

- **B0 rails (pre-approved):** delete the dormant `gravityformsactivecampaign` plugin folder (instructed 10 Aug; outside the theme repo). Baseline screenshots already captured in the session scratchpad.
- **B1 tokens and mixins** (touches design tokens, flagged): shadow tokens (SW-16), hex dedupe (SW-17), hover-gate the two button mixins (SW-13 core), delete dead mixins (SW-19 part). Widest blast radius, widest verification.
- **B2 shared partials, header, footer** (touches shared components, flagged): remaining hover gating sweep (SW-13), GF button height + newsletter input size (SW-14), footer/breadcrumb/fact-link/mobile-menu target padding (SW-15), mega menu ARIA + Escape + touch sync (MEGA-01/02/03), motion safety-net extensions + below-fold failsafes (SW-04, MO-02), entity sweep in templates (SW-21), radius tidy (SW-22), stale comments (SW-19 part).
- **B3 structural correctness:** IDX-01 fallback, ARC-01 branch, SR-04 dead post type, SR-05 multi-word search bug, SR-03 sparse-grid handling, FW-05 bundle parity, FRM-01 picker location rule, FRM-02 default alignment, bundle dedupe + dead JS removal (SW-19 part), gitignore + untrack the impeccable cache (SW-20).
- **B4 motion:** HOME-05 red hover strip removal, MO-01 rolling-word clip check, anything B2 exposed; emil-design-eng sets numbers, GSAP skills implement, review-animations grades the diff.
- **B5 page-family fixes:** SVC-05/06 label casing, LP-01 h1 backfill, LP-02/03 label dedupe, CSS-02 duplicate CTA, CSA-02 odd-count rule, WKS-02 tap sizes, WKS-04 date composition, WKS-05 sr space, AB-03 webp fallback image, AB-04 reduced-motion value state, BLOGS-04/05 TOC completeness + anchor dedupe, E404-02/03 copy alignment, CON-03/07 tidy, SW-18 alt strategy for singles' heroes/galleries.
- **B6 template copy sweep:** HOME-09 if template-side, remaining entity/casing strays, DOC-01 documentation updates.
- **B7 the DB pack (every value listed verbatim for approval before applying):** Yoast separator setting + og filters (SW-12), meta descriptions I draft for META-01 surfaces, notification reply-to/from-name/subjects (EM-01/02/05), autoresponder proposals (EM-04), form 2 confirmation rewrite (EM-06), category fixes (CAT-07/08 + descriptions), author display name (AUT-06), homepage standfirst em dash if ACF-held (HOME-09), llms.txt regeneration (SEO-01). For-Ibrar content items (LAUNCH-01, LEG-01/02/03/07, EM-03) stay his.


## Fix log — session 2 (10 Aug 2026)

Branch `fix/frontend-audit-pass-2` off `staging`, five commits, `npm run production` per batch, verified live with Playwright (dark and light, 375 and 1440 spot checks per change, console first-party clean).

- **B0** — the dormant `gravityformsactivecampaign` plugin folder deleted (instructed; it was inactive with zero feeds, so no DB change existed to make). Test entry from the audit's form 5 submission deleted (entry 355).
- **SW-16/SW-17** `done` — shadow glows and duplicate hex tokenised (`$vc-glow-card`, `$vc-glow-card-dark`, `$vc-glow-button`; 29 shadow swaps, 14 hex swaps); the Bootstrap body-bg override moved below the tokens and reads `$vc-background-white`.
- **SW-13** `done` — forge/ghost button and breadcrumb mixins hover-gated at source; chrome hovers gated across header, mobile menu, footer, spotlight arrows, enquiry next/prev. Body-copy underline hovers were left ungated deliberately (harmless on touch); listed here so the scope is honest.
- **SW-14** `done` — GF buttons floored at 44px via a new shared `common/_gf-buttons.scss` keyed on `.gform_wrapper` (the per-embed submit rules turned out not to reach GF Orbital's themed button; verified 38 to 44px live on the homepage). Newsletter input to 16px.
- **SW-15** `done` — footer menu/contact/legal links padded to 29px boxes, project hero meta links padded, mega child links 40px flex rows, mobile top-level anchors 44px (verified live).
- **SW-19/SW-20** `done` — dead files deleted (`_story.scss` orphan, `_preloader.scss`, `smooth-scrolling.js` and its bundle line, 5 stubs, the two legacy 10px-radius button mixins and `vc-card`); `.impeccable/` ignored and the tracked cache removed; the triplicate insights bundles left as-is (build-level duplication only, parked). Source maps stay pending the open question.
- **SW-21** `done` — typographic entity codes swapped for real characters across 10 templates (`&amp;` kept where it is correct HTML escaping).
- **SW-22** `done` — cite-avatar to 2px. The founder-bio open plate keeps its 10px: it shipped in the reviewed July founders build, so it is recorded as owner-approved rather than changed.
- **MEGA-01/02/03** `done` — panels and disclosure wraps get ids with `aria-controls` on their triggers; hover only reports open on hover-capable devices (no more stuck `aria-expanded` on touch); Escape now closes the panel even with focus on the parent link (`is-escaped` + CSS override; verified live: panel visible on focus, hidden after Escape).
- **SW-04** `partly` — safety net extended with the missing pre-hidden selectors; the insights family gained a 4s reveal failsafe in `blog/reveal.js` (it had none at all). The same failsafe pattern still wants wiring into the other below-fold reveal modules (homepage work/why/services/our-work, project, case-study, service, services-hub, about, free-website, landing): listed as the remaining SW-04 work.
- **MO-02** `done` — mobile menu anchor scroll branches to an instant jump under reduced motion.
- **HOME-05** `done` — the service-card 3px red top strip removed on hover and focus; border heat, background tint, watermark drift and title shift stay.
- **IDX-01/ARC-01** `done` — `index.php` renders the shell plus the not-found body; non-category archives 301 to `/blog/` before output. Note: on this local copy the host layer still swallows `/2026/07/` to the homepage before WordPress sees it (UTIL-01), so the archive redirect shows its worth only where requests reach WP.
- **SR-03/04/05** — sparse search results widen to half-width (`is-sparse`, verified 648px of 1296); the dead `video` post type removed; SR-05 reclassified after a decisive test (`?s=wordpress+2026` matches): multi-word search works and requires all terms, so "web design" legitimately finds nothing in a corpus that says "website". No code defect.
- **FRM-01/02** `done` — the form picker gains a `service` taxonomy location rule and the term pages pass their term id, so every service page's form is now editor-selectable; the landing CTA default is documented as form 10 (code was right, the docs disagreed).
- **FW-05** `done` — free-website now enqueues the enquiry bundle (entrance, dataLayer events, confirmation focus), matching the landing template.
- **SVC-05/06** `done` — "Start a Project" and "All Services" title-cased on the service templates (verified live).
- **LP-01** `done` — landing pages without a hero block emit a visually-hidden h1 from the page title.
- **WKS-05** `done` — the live-link screen-reader suffix gets its missing space.
- **AB-03** `done` — the 1.37MB avatar placeholder is now a 22KB 800px webp across all seven fallback call sites.
- **AB-04** `done` — values words rest in the text colour in both modes (dark override added); the red arrives only through the scrub fill, which is untouched (verified: reduced-motion rest state neutral, scrub clip animating).
- **CSA-02** `done` — the odd last case-study card runs full width (verified 648/648/1296).
- **BLOGS-04/05** `done` — the TOC now lists the FAQs section and duplicate headings get suffixed anchors, applied identically at injection and render (verified live).
- **E404-03 / CON-07 / HOME-09 (template half)** `done` — the 404 copy matches its real options; the contact next-steps heading is an h2; the homepage standfirst fallback loses its em dash (the saved ACF value is in the DB pack below).
- **WKS-04** `parked` — a bare year is valid ISO 8601 for `dateCreated`; padding it to January the 1st would fabricate precision. Revisit if real project dates arrive.
- **CSS-02** `parked` — two Start a Project moments on a long single matches the house long-page pattern (the homepage does the same); the hardcoded label rides with SVC-04's ACF plumbing when that happens.
- **SW-18** `for-Ibrar` — reclassified: the card-grid `alt=""` exemption is correct house style, and the singles' hero/gallery alts come from the media library, where the attachment alt fields are empty. That is content entry (or a one-off script naming each from its project), not template work.
- **SW-12** `open` — the Yoast separator setting and og:title brand suffix are in the DB pack below; the matching `wpseo_opengraph_title` filter lands with the pack so both move together.
- **MO-01** `open` — the rolling-word overlap wants a human eye at real speed before any clip is added; watch the hero once and say the word.
- Awaiting your call from the Decisions section: SW-23 (wheel hover grandfather), the TOC hover rule, source maps (SW-20 note). `/review-animations` over `b76f1a1..HEAD` is ready for you to run; the changes were built against emil-design-eng's bands.

## B7: the DB pack (apply only on your yes)

Every value verbatim; nothing below has been applied. Say yes to all, or strike lines.

1. **Yoast separator** (Search Appearance): set the title separator to `|`. This also fixes og:title's ` - ` on 46 pages; the `wpseo_title` filter stays as a safety net.
2. **og:title brand suffix for posts** (code, ships with the pack): a `wpseo_opengraph_title` filter appending ` | Vulkan Creative` when absent, mirroring the title filter.
3. **Notifications, all 7 forms:** from-name `Vulkan Creative Website`; subject unified to `New enquiry from {form_title}` (forms 2 and 3 currently say "submission"); reply-to set to each form's email field merge tag (form 2 `{Email:4}` pending id check, form 3 `{Email:1}`, form 5 `{Email:11}`, forms 6/7/8/10 their email field ids, read from the saved form JSONs at apply time). Recipient stays `info@vulkancreative.test` locally until you answer the deploy question (EM-03).
4. **Submitter autoresponder** (EM-04), one per enquiry form (not the newsletter), subject `We have your enquiry | Vulkan Creative`, body: "Thanks for getting in touch. Your enquiry has reached the team and we will reply within one working day. If anything is urgent, call us on 020 3576 7525. Vulkan Creative".
5. **Form 2 confirmation** (EM-06) rewritten to: "Thank you. Your enquiry is on its way to the team, and we will reply within one working day. If anything is urgent, call us instead." (matches form 5).
6. **Homepage standfirst** (`hp_latest_subheading`): "Fresh thinking on brand, web and marketing: what we're learning, building and watching."
7. **Categories:** rename `Uncategorized` (you pick the label: "General"?) with a UK slug, or retire it and reassign its posts; recase `Website performance` to `Website Performance`; term descriptions for the 19 categories drafted on your nod (they double as the hero standfirsts).
8. **Author display name:** the `creativevulkan@gmail.com` account gets a human display name (your call what it should read).
9. **Meta descriptions** (Yoast fields): Contact: "Tell us about your project and we will reply within one working day. Call, email or use the enquiry form to talk to the Vulkan Creative team in London." SEO and AI Search page: "Get found on Google and in AI answers. Request a free visibility report and see exactly where your business stands in search today." Privacy and Cookie Policy: one line each stating what the page covers.
10. **llms.txt:** regenerate through Yoast v28.2 after the noindex decision, fixing the escaped markdown, stale titles and the "Fee Business Website" typo.

### Session 2 continuation (11 Aug 2026, after Ibrar's form screenshot)

- **FRM-03** `Status: done` — **Default GF embeds were unstyled** (Ibrar's catch): GF Orbital renders `button.gform-button`/`button.gform_button`, so the legacy `input[type="submit"]` embed rules never matched; the homepage, hub, service and newsletter forms ran Orbital's blue button and its dark-navy placeholder colour (invisible on the dark surfaces; no placeholder attribute was actually missing, it was purely colour). Fixed in `common/_gf-buttons.scss`: forge button (both class spellings, enquiry family excluded) and house placeholder colours in both modes. Verified live on homepage (both modes), hub and the newsletter: red Poppins 2px buttons, `#595959` light / `rgba(181,181,181,.8)` dark placeholders.
- **SW-04** `Status: done` — the reveal failsafe now covers every pre-hiding module: a shared `components/reveal-failsafe.js` wired into all 31 pre-hide sites across 18 modules (the work wheel restores opacity only, so its arc transforms stay untouched). Verified behaviourally: 4.9s after load with zero scrolling, no below-fold target stays hidden on the homepage or free-website page.
- **DB pack applied** (your "continue the rest", 11 Aug): separator to `|` (og:title now matches the SERP title via the new shared `vc_normalise_brand_title()` on both Yoast pipes); all 7 admin notifications carry from-name "Vulkan Creative Website", a labelled reply-to (`{Email:7}`/`{Email:1}`/`{Email:11}`/`{Email:3}`) and unified subjects; Submitter Confirmation autoresponders added to forms 2/5/6/7/8/10; form 2's confirmation matches form 5's promise; the homepage standfirst ACF value lost its em dash; `Website Performance` recased; meta descriptions set on Contact (151), SEO and AI Search (131), Privacy (100) and Cookie (85). Two deviations from the drafted pack, both semantic: form 3's subject is "New subscriber from {form_title}" (it is a newsletter, not an enquiry), and form 8's autoresponder promises "a few working days" to match its own confirmation.
- **FRM-03 addendum** — the same GF 2.9 markup shift reached one layer deeper: the single-step enquiry forms (landing form 8, free-website form 7) render `button.gform-button` too, which the enquiry family's own `input[type="submit"]` selector missed, so their submits also fell back to Orbital blue. The enquiry partial's button selector group now covers both button spellings. Verified live: forms 2, 5, 7 and 8 all render the forge button (red, Poppins, 58px) on their host pages.
- **Still yours:** the Uncategorized rename (CAT-07), the author display name (AUT-06), the notification recipient at deploy (EM-03), the service-page noindex call (SEO-01) and the llms.txt regeneration that follows it, category descriptions on your nod, MO-01's rolling-word eyeball, and the Decisions items.

### Session 3 (11 Aug 2026): live-content reimport and the delegated remainder

Ibrar delegated the remaining "yours" items and supplied the live-site WXR export. All applied and verified:

- **Blog reimport.** The 15 local posts were backed up to the session scratchpad (WXR) and deleted; the 18 live posts imported with authors mapped (creativevulkan / ibrarrkhan / flynn), attachments fetched, ACF post meta remapped to the local host serialisation-safe, and the one-shot wordpress-importer removed afterwards. Verified: 18 posts, every featured image loads, zero broken in-content images after full lazy-load, zero live-domain refs, TOC and bylines correct. This supersedes the July content findings on posts (the Gemini/Claude mismatch, standfirst duplication, imageless cards: all gone with the real content).
- **Categories.** The reimport brought the real 18-category set; categories left empty were pruned; `Uncategorized` renamed to **General** (default bucket, slug `general`); `Website Performance` recased; all 18 real categories carry a written description that renders as the hero standfirst and now feeds the meta description via the new `%%term_description%%` category template. Verified rendering and meta on `/blog/category/seo/`.
- **Author account.** The legacy `creativevulkan@gmail.com` display name is now **Vulkan Creative** (change it in Users if you want a person instead).
- **EM-03.** All seven notifications now send to `info@vulkancreative.com`. Locally nothing delivers (no transport), so test submissions stay harmless here.
- **SEO-01.** The remaining `[SAMPLE]` stat rows were cleared from the service terms (the section hides when empty), the `service` taxonomy is now **indexable**, and the 18 child terms carry per-term noindex so only the six pillars enter the index. *(Superseded Aug 2026: Ibrar opened every child page to the index as part of the Content & Social / Social Media Management build; sample data is his to clear before launch. See `docs/services-system.md`.)* Yoast's term indexables were purged so the cache rebuilds. Verified: pillar `index, follow` with canonical and no visible [SAMPLE]; child `noindex, follow`.
- **MO-01 closed, not an issue.** `.dynamic-text` already clips (`overflow: hidden`); the measured word travel outside the box is unpainted geometry, so the brief two-word overlap inside the slot during a swap is the intended slot effect. No change made.
- **TOC hover** now follows the audit recommendation: the red left rule marks the active position and keyboard focus only; hover keeps the colour shift.
- **Source maps** no longer build or ship in production (`mix.sourceMaps()` is dev-only; the 18 tracked maps are removed).
- **SW-23 closed as kept:** the work-tile saturation hover stays grandfathered per the standing decision.
- **llms.txt:** the "Fee Business Website" typo was a real page title, now fixed at source (page 880 renamed "Free Business Website"); the file regenerates on Yoast's own schedule from corrected sources, locally and on live.
- **Still open, genuinely:** `/review-animations` over the branch (the skill only accepts your invocation), the `[placeholder]` figures on the two landing pages (LP-02/03: real numbers only you have), and the live-site deploy questions (SMTP transport, HubSpot add-on state).

- **/review-animations verdict (11 Aug): Approve.** One finding: the GF button press transform ran on the default ease; now `transform .12s ease-out` per the press-feedback standard. Everything else passed: the strip deletion follows the remedial hierarchy, the failsafe is GPU-only and converges with live reveals, dismissals snap, reduced motion is honoured.

### Session 4 (11 Aug 2026): hero statue optimisation

- The hero was already plain three.js (CLAUDE.md said Spline; corrected). `statue-marble.glb` (7.14MB raw export, geometry 4.7MB of it) replaced by `statue-marble-2.glb` (2.18MB): legs cut at mid-thigh in Blender from `statue-marble-new-pose.blend` (local-space bisect, capped and triangulated, rig and node names preserved; 5,571 vertices removed), textures resized to 1024, geometry quantised + meshopt (decoder wired in `statue-scene.js`, which also gained the Hammer_Rig null guard). Versioned filename so caches cannot serve the old model. Dead weight removed: `scene.splinecode` (15.9MB), `spline-viewer.js`, `@splinetool/viewer`, two orphaned posters. Verified live: 2,129KB transferred (was 7,138KB, 70% down), scene boots and reveals, parallax + hammer grip intact, framing unchanged, poster paths below 992px and reduced motion still serve. Working files kept in `~/Downloads/Blender Statue/optimised/` (no-legs .blend + glb, final glb, renders); originals untouched.
- **Pose correction (Ibrar catch):** the first export shipped the armature REST position (Blender glTF default `export_rest_position_armature=True`), dropping the arms and beaching the hammer; re-exported with the pose position kept and proved numerically: UpperArm.L/R, LowerArm.R, Spine2 and Hammer_Rig rotations byte-match the original glb. Visual parity confirmed at 1440. Same 2.18MB. Lesson recorded: pose parity is part of the export contract, verify bone transforms against the previous file, not just node names.
