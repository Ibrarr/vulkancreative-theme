# Work system (July 2026)

The Work section: the archive at `/work/` and a showcase single per project at
`/work/{slug}/`, built on the existing `project` CPT (admin menu "Our Work").
Read this before touching `archive-project.php`, `single-project.php`, the
`template-parts/work-*` parts, the `wk_`/`pj_` ACF fields or the project SCSS/JS.
Work and Case Studies are deliberately separate (Ibrar, July 2026): these are
showcase pages, not case studies; the `case_study` CPT stays homepage-only until
its own round, and `pj_case_study` on each project holds the future cross-link
(it renders nothing until `case_study` is publicly viewable).

## Routing and registration

- `inc/custom-post-types.php`: `project` is public with `has_archive => 'work'`
  and `rewrite => [ 'slug' => 'work', 'with_front' => false ]` (`with_front` is
  mandatory: the permalink base is `/blog/%postname%/`). `exclude_from_search`
  stays true (projects support title only; search-result cards would render as
  empty shells). Flush permalinks after any rewrite change.
- **The `?service=` collision shim** (`inc/filters.php`): `service` is the
  service taxonomy's query var, so `/work/?service=x` would set
  `is_tax('service')` on the main query and drag in the service term-page
  behaviours (the 3-post cap, `service.js`, the `tax-service` body class, the
  footer CTA hide, the breadcrumb splice). A `request` filter moves it into the
  private `vc_work_service` var when `post_type=project`; the archive template
  reads that var for server-rendered filter state, and Yoast canonicalises
  filtered URLs to `/work/`. `vc_service_archive_query()` carries a
  belt-and-braces bail on `post_type === 'project'`.
- `vc_work_archive_query()` (`inc/actions.php`): the archive main query is
  uncapped (`posts_per_page -1`, `no_found_rows`), newest first. No pagination;
  revisit a load-more past roughly 18 projects.
- `inc/styles-scripts.php`: the single-blog bundle is gated on
  `is_singular('post')` (NOT `is_single()`, which is true for CPT singles);
  `js/project.js` loads on `is_post_type_archive('project') || is_singular('project')`.
- `vc_schema_type()` branches for project pages sit BEFORE the `is_single()`
  Article branch: archive → `CollectionPage`, single → `WebPage` (plus a
  `CreativeWork` JSON-LD node in the template).
- Yoast: breadcrumbs emit `Home > Our Work > {name}` natively from
  `has_archive`. Search Appearance holds the title templates and, while the
  `[SAMPLE]` content is live, **noindex on both the singles and the archive**
  (`wpseo_titles`: `noindex-project`, `noindex-ptarchive-project`). Flip both
  when real client work replaces the samples.
- Menus: "Our Work" sits after "What We Do" in both `main-menu` and the footer
  Explore menu (custom items pointing at `/work/`).

## Archive (`archive-project.php`, body class `.post-type-archive-project`)

Hero dark band (shared `template-parts/page-hero.php`, alias `work-hero`,
heading from `wk_hero_heading_*` options) → one `surface-white` section
(`.work-index`) → footer CTA band (kept; the archive has no conversion moment
of its own).

- **No visible section h2**: the hero h1 carries the message; a
  `visually-hidden` h2 ("All projects") keeps screen-reader structure (the
  standing decision recorded in CLAUDE.md's Type section).
- **Filter** (`template-parts/work-filter.php` + `assets/js/project/filter.js`):
  an "All Work" chip plus one per service term with imaged published projects,
  ordered by the term `order` field. Chips are real links (`/work/` and
  `/work/?service={slug}`); PHP renders ALL cards each load and marks
  non-matching ones `hidden`, so no-JS filtered URLs are correct at first
  paint. JS filters in place (fade out 0.22s, staggered fade-in, instant under
  reduced motion), syncs the URL via pushState/popstate and announces counts
  through the visually-hidden `[data-work-status]` live region. Below `md` the
  chips collapse behind a dropdown toggle (44px rows in a bordered panel;
  Escape/outside-click/selection close it, focus returns to the toggle);
  without JS the toggle hides and the chips stay visible.
- **Grid** (`template-parts/work-card.php`): editorial 7/5 then 5/7 rhythm
  computed over the VISIBLE subset per filter (`i%4` in `{0,3}` → wide
  `col-lg-7` 16/10, else `col-lg-5` 4/3; below `lg` everything is 4/3;
  `filter.js` mirrors the same pattern maths). Cards mirror the wheel tile
  anatomy (scrim, primary-service plate, caption-anchored gradient, glued
  internal `→` arrow) but link internally and never change tint on hover (the
  wheel's saturation hover is grandfathered to the wheel only).
- ItemList JSON-LD of the project URLs at the template foot.

## Single (`single-project.php`, body class `.single-project`)

Sections, with surfaces alternating via a `$pj_surface()` closure and every
optional field hiding its section:

1. **Project-led hero** (`.page-hero.project-hero`, bespoke markup composing
   the shared page-hero classes): breadcrumbs, `pj_client_name` h1, hairline-
   separated meta row (sector · year · service term links; services take their
   own line below `md`), ghost "View Live Site" ↗ when `pj_link` is set (and a
   "Read the Case Study" ghost that activates only when `case_study` becomes
   public), then the `pj_image` panel (21/9 desktop, 16/10 down, 10px corners,
   eager + fetchpriority high: it is the LCP image).
2. **Overview** (`.project-overview`): shared `wk_overview_heading_*` h2 with
   the statement stacked beneath it (no split-header), the ember-ruled fact
   ledger right (Client/Sector/Year/Services/Live site), and the muted
   "What we delivered" label over a two-column ruled list from
   `pj_deliverables`. The statement falls back to `pj_description`.
3. **Gallery** (`.project-gallery`): a static editorial grid, no carousel and
   no lightbox (the related wheel below is the page's one drag surface).
   Rhythm per group of three: full-width 21/9 panel then a 7/5 pair; captions
   sit in Poppins italic 10px under their own image with a 48px gutter before
   the next item (proximity binds them; no tick, per the tick-restraint rule).
   Caption present → `alt=""`; else the attachment alt.
4. **Related work** (`.project-related`): the shared work wheel, projects
   sharing ANY of this project's service terms, current excluded, imageless
   skipped, newest first, max 8, service plates ON, tiles linking to the
   projects' own pages. Heading `wk_related_heading_*` ("Related work.").
   Renders only with ≥1 item. `#work-wheel` appears once per page.
5. **CTA** (`.project-cta`): the service-cta recipe with the outlined
   client-name wordmark, forge "Start a Project" → `/contact/`, ghost
   "Back to Our Work" → `/work/`. No form (the services-spoke rule);
   `footer.php` hides the footer CTA band on `is_singular('project')`.

CreativeWork JSON-LD at the foot (name, description, url, image, dateCreated
from `pj_year`, keywords from service names, `provider` → `/#organization`).

## Sitewide wheel change (July 2026)

Homepage and service-page wheel tiles pass `get_permalink()` instead of
`pj_link`: every tile is clickable and internal. `template-parts/work-wheel.php`
distinguishes internal links (in-tab, `→` arrow) from external (`↗`, new tab,
visually-hidden note); the live-site link lives in each project page's hero.

## ACF

- **`acf-json/work-page-settings.json` + `work-page.json`**: the "Work Page
  Settings" options sub-page under the Our Work admin menu (`wk_` prefix):
  hero heading parts + subheading, "All Work" chip label, and the shared
  single-page strings (overview/related/CTA heading parts, CTA subheading).
  Values are saved (seeded), not just defaults.
- **`acf-json/project-details.json`** ("Project Details", `pj_`, seamless,
  under the base group): `pj_year`, `pj_overview_statement`,
  `pj_overview_support`, `pj_deliverables` (repeater, `label`), `pj_gallery`
  (repeater, `image` + optional `caption`), `pj_case_study` (post_object →
  `case_study`, dormant until that CPT goes public). Every field optional.
- The base group (`project.json`: `pj_client_name/sector/description/image/link`)
  is unchanged and still feeds the wheels and archive cards.

## Seeder and sample content

`inc/project-seed.php` (Settings > Seed Work, the service-seed pattern):
slug-matched, fills empty fields only (overwrite checkbox), seeds year,
overview, deliverables and per-page Yoast meta descriptions, and carries the
canonical gallery caption copy. The `fix` entries repair known-incoherent
sample base data and always apply. All nine projects carry `[SAMPLE]` titles
and `_vc_sample_content` meta; hero and gallery imagery was generated with the
Higgsfield MCP (never stock-scraped) and attached per environment, with
`pj_image` set to each project's wide 21:9 shot so tiles and heroes share it.

## SCSS / JS map

- SCSS: `assets/css/components/project/` — `_project.scss` (scope for both
  body classes: uppercase h2 + red span, `surface-white/grey` pairs with
  chained `body.dark-mode` overrides, `html.js` pre-hide + failsafe, the
  `.project-related` wheel-host rules) and `components/{_index,_filter,_hero,
  _overview,_gallery,_cta}.scss`. Imported in `app.scss` after the service
  family. New pre-hidden selectors live in `misc/_motion.scss`.
- JS bundle `js/project.js` = `project/reveal.js` (services-hub reveal pattern
  with the work/project selector lists) + `project/filter.js` +
  `homepage/our-work.js` (the related wheel; no-ops on the archive).
