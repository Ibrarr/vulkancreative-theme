# Case Studies system (July 2026)

The proof tier above Our Work: the archive at `/case-studies/` (`archive-case_study.php`) and a narrative single per case study at `/case-studies/{slug}/` (`single-case_study.php`), built on the existing `case_study` post type and cross-linked both ways with the Work system (`docs/work-system.md`) and the Services system (`docs/services-system.md`). Read this before touching anything under the two templates, `template-parts/case-study-card.php`, the `cs_`/`csp_` ACF fields or `inc/case-study-seed.php`.

## The relationship model

This is the point of the build; do not replace it with a parallel structure.

- **Case study to project:** `pj_case_study` (post object on each project, Project Details group) is the single source of truth for the pairing. The case study finds its project by reverse meta query (`single-case_study.php`, `get_posts` with `meta_key pj_case_study = (string) $cs_id`, first match). There is deliberately NO mirror field on the case study; add one and the pairing has two owners.
- **Case study to services:** the `service` taxonomy is registered on `case_study` (`inc/custom-taxonomies.php`), the same model as projects. Terms are assigned in the normal sidebar box and drive the linked service names, the archive filter and the service pages' strip.
- **Where the links surface:**
  - Project single hero: ghost "Read the Case Study" button (shipped dormant with the work system, live since this build).
  - Project single: the `.project-case-study-band` full-story band between the gallery and the related wheel (metric, summary, read action; one band-wide link).
  - Case study single: hero ghost "View the Project", the fact ledger's Project row, and the `.cs-related` feature panel.
  - Service term pages: the `.service-case-studies` strip between the recent-work wheel and insights (3 newest imaged case studies carrying the term, shared cards).
  - Homepage work section: the `.case-row` cards link to the case studies' own pages (relinked from `#contact` in this build; the outro keeps the contact route).
  - Menus: "Case Studies" sits after "Our Work" in `main-menu` and the footer Explore menu.

## URL, registration and queries

- `inc/custom-post-types.php`: `case_study` is public with `has_archive => 'case-studies'` and rewrite slug `case-studies` (`with_front` false). **`exclude_from_search => true` is load-bearing twice over**: title-only search cards, and it is what keeps case studies out of the service term pages' main archive query. Flush permalinks after any rewrite change.
- `inc/actions.php`: `vc_case_study_archive_query()` uncaps the archive newest-first (the `vc_work_archive_query` mirror). `vc_service_archive_query()` now also bails on `case_study` and pins the service term main query to `post_type => 'post'`, so the insights grid can never inherit CPT leakage.
- `inc/filters.php`: the `?service=` chip URLs reuse the SAME private query var as /work/ (`vc_work_service`); `vc_work_service_request()` matches both post types. Yoast omits canonicals while the section is noindexed (the /work/ behaviour).
- `inc/template-functions.php`: `vc_schema_type()` maps the single to `WebPage` and the archive to `CollectionPage` (branches must precede `is_single()`).
- `footer.php`: singles hide the footer CTA band (they end on their own CTA); the archive keeps it.

## Archive (`archive-case_study.php`)

Shared `page-hero` band (heading/subheading from the `csp_` options; the positioning line lives in the hero sub-line; no visible section h2, a visually-hidden one keeps structure), then one `surface-white` section: filter chips + the two-up card grid + empty states + `ItemList` JSON-LD.

- **Filter:** `template-parts/work-filter.php` is shared with /work/ via additive args (`base`, `all_label`, `aria_label`); passing nothing keeps /work/ byte-identical. Chips render only for services with imaged published case studies, ordered by the term `order` field. `assets/js/case-study/filter.js` is the project filter minus the column-pattern maths (uniform grid); server renders the filtered state (`hidden` attrs) so no-JS filtering works.
- **Card:** `template-parts/case-study-card.php` + `common/_case-study-card.scss`, shared with the service strip (pass `class` for the column set). Anatomy: media panel (16/10, sector plate, depth scrim) above a bordered body led by the headline metric on an ember rule, then client + glued arrow + one-line summary. **Hover is inner image zoom + colour shifts only**: the work wheel's saturation hover is grandfathered and must not spread here, and tint never changes.

## Single (`single-case_study.php`)

Section order and surfaces (first after the hero is the lighter pair; conditional sections hand their slot down): hero (dark band) → overview `surface-white` → challenge `surface-grey` → approach `surface-white` → **results (full-dark anchor, consumes no slot)** → testimonial `surface-grey` → related `surface-white` → CTA `surface-grey`. Every optional field hides its section.

1. **Hero** `.page-hero.cs-hero`: breadcrumbs, client h1, `cs_summary` standfirst, meta row (sector, `cs_year`, linked service terms), `.hero-metric` (the headline `cs_metric_value`/`cs_metric_label` at hero scale; the number is the page's reason to exist), then the `cs_image` media (eager + fetchpriority, the LCP). Deliberately no hero action (Ibrar, July 2026): the project link lives in the fact ledger row and the full-story panel below, so the band stays lean.
2. **Overview**: `csp_overview_heading` h2, overview-lead, fact ledger (Client / Sector / Year / Services linked / Project linked).
3. **Challenge / Approach**: the h2 heads its own row; the `.narrative-row` beneath carries the `wpautop` body at a comfortable measure (col-lg-7) with a filled right rail (col-lg-4 offset-1): the challenge pulls its bold statement right (above the body on mobile via column order), the approach carries the paired project's `pj_deliverables` as the ruled "What we delivered" list (hides without a project or rows). The approach then carries the editorial gallery in the work-single rhythm (full 21/9 then 7/5 pair, italic proximity-bound captions, caption present means `alt=""`).
4. **Results** `.cs-results.results`: the service results-anchor recipe (#121212 both modes, dark mode steps to #0D0D0D, ember seam, low-left glow). Markup keeps `.results`/`.stat-number` so the shared `homepage/counter.js` counts up unchanged; values are server-rendered exact for no-JS and reduced motion. Narrative line + ghost CTA under the stats.
5. **Testimonial**: the shared spotlight in its static variant: `.testimonial-spotlight.spotlight-static` (plus `--no-photo` when the testimonial has no portrait), fed by the `cs_testimonial` post object into the `testimonial` CPT. No Splide anywhere on these pages. The static styles ride the carousel partial via `&.spotlight-static blockquote` selector extensions in `common/_testimonial-spotlight.scss`.
6. **Related** `.cs-related`: the linked project as a text-led feature panel (media joins when the project has imagery) + up to 3 shared `service-card` parts (`related` variant, no index). The cards fill the row: three terms sit `col-lg-4`, one or two widen to `col-lg-6` so no empty column is left.
7. **CTA** `.cs-cta`: outlined client wordmark, `csp_cta_heading`/`csp_cta_subheading`, forge Start a Project to `/contact/`, ghost All Case Studies.
8. **JSON-LD**: `CreativeWork` (`@id {permalink}#casestudy`) attached to `/#organization`, with `about` welding it to the project's `#creativework` node when paired.

## SCSS and JS

- Family `assets/css/components/case-study/` (`_case-study.scss` scope + `components/{_index,_filter,_hero,_overview,_narrative,_results,_testimonial,_related,_cta}.scss`), imported in `app.scss` after the project family. Recipes are mirrored from the work family (the `.project-cta` mirroring precedent) so /work/ ships untouched; the card lives in `common/` because two families consume it.
- Page scope pre-hides the hero reveal targets behind `html.js` with the 2.5s `vc-cs-reveal-failsafe`; all reveal targets are in the `misc/_motion.scss` safety list.
- Bundle `js/case-study.js` = `case-study/reveal.js` + `case-study/filter.js` + shared `homepage/counter.js`, enqueued on `is_post_type_archive('case_study') || is_singular('case_study')`. The service pages' strip reveals through `service/reveal.js` (strip h2 + `.cs-card-item` group); the project band fades through `project/reveal.js`.

## ACF

- **Base group** (`acf-json/case-study.json`, unchanged): `cs_client_name`, `cs_sector`, `cs_summary`, `cs_metric_value`, `cs_metric_label`, `cs_image`, `cs_featured` (still picks the homepage three).
- **Case Study Details** (`acf-json/case-study-details.json`, keys `field_cs_01xx`): `cs_year`, `cs_overview_statement/_support`, `cs_challenge_statement/_body`, `cs_approach_body`, `cs_approach_gallery` (image + caption repeater), `cs_results_stats` (value + label repeater, max 4), `cs_results_narrative`, `cs_testimonial` (post object, return id).
- **Case Studies Page options** (`acf-json/case-studies-page-settings.json` + `case-studies-page.json`, prefix `csp_`, sub-page under the Case Studies menu): archive hero heading parts + subheading, filter "All" label, three-part headings for overview/challenge/approach/results/testimonial/related/CTA plus the CTA subheading. All consumed via `vc_heading_parts( 'csp_…', 'options', $fallback )`.
- **Service Page group**: gained `sv_case_studies_heading_start/_red/_end` (fallback "Case <span>studies</span>") for the term pages' strip.

## Seeding, noindex and sample state

- **Settings > Seed Case Studies** (`inc/case-study-seed.php`): slug-matched, non-destructive by default, overwrite checkbox, `_vc_sample_content = 1` on everything touched. Per case study it fills the details fields, assigns service terms, resolves `cs_testimonial` by matching `tm_company` to the client name, sets the Yoast metadesc, AND creates/fills the matching sample project (`wp_insert_post` by slug if missing, `pj_` fields, terms, metadesc, **`pj_case_study` link**). It also seeds the `csp_` options copy. Keep its content arrays in sync with approved copy changes.
- The three sample case studies (Northbridge Property Group, Halcyon Events, Fenwick & Frost) pair with three matching `[SAMPLE]` projects created by this build; testimonials 419/420/421 belong to the same fictional clients. Project imagery is attached separately per environment; imageless projects are skipped by wheels and cards by design.
- **Yoast noindexes both tiers while `[SAMPLE]` content is live**: `noindex-case_study` + `noindex-ptarchive-case_study` in `wpseo_titles` (set alongside the title templates and archive metadesc). Flip both when real client case studies replace the samples, the same switch as the work pages.
