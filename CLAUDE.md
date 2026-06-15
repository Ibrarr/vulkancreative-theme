# CLAUDE.md

> **Read this file first.** Before making any changes in this theme folder, read this document in full. It contains the conventions, patterns, file paths, variable names, and step-by-step instructions needed to work correctly in this codebase.

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository. The custom theme lives at `wp-content/themes/vulkancreative-theme/`.

---

## Workflow conventions (read before any design work)

These apply to every section, element, page or component you design or redesign in this theme.

### Use the installed skill libraries

Two skill libraries are installed and should inform design and copy decisions:

- **ui-ux-pro-max** (`~/.claude/skills/ui-ux-pro-max`, searchable via `python3 scripts/search.py "<query>" --domain <domain>` and `--design-system`) for layout, style, interaction, accessibility and motion.
- **The marketing skills library** (individual skills such as `copywriting`, `cro`, `content-strategy`, `product-marketing`, `marketing-ideas`, invoked via the Skill tool) for copy, positioning and conversion decisions.

When building a new section or element, crawl both libraries yourself and decide which skills genuinely help this specific task — they are not prescribed. Apply the chosen skills actively in both the plan and the build, and state in the plan which you picked and why. If you cannot see where the libraries are installed, ask.

### Always verify with the Playwright MCP

Test every change against the local site (`https://vulkancreative.test/`) with the Playwright MCP — never assume a change works from the code alone. Check the affected pages in both light and dark mode and across the 375 / 768 / 1024 / 1440 breakpoints, confirm no horizontal scroll or layout shift, alpha-composite contrast where text sits over imagery, and capture screenshots to share. This is the verification standard for the whole theme, not just new pages.

### Plan first

Investigate, then present a written plan (post type/fields if any, layout, chosen skills, accessibility, future-proofing) and wait at an explicit approval gate before writing code.

---

## How to Add a New Page

Follow these steps end to end. Every step references the exact conventions used across the rest of the site.

### 1. Create the page template

Create a PHP file in `wp-content/themes/vulkancreative-theme/page-templates/`:

```php
<?php
/* Template Name: Your Page Name */

get_header();
?>

<section class="your-page-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <!-- Section content here -->
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
```

- File naming: `page-your-page-name.php` (lowercase, hyphens).
- The `/* Template Name: ... */` comment registers it in the WordPress page editor.
- Templates are also auto-registered by `inc/template-functions.php` on the `init` hook.
- WordPress generates a body class `page-template-page-your-page-name` automatically. Use this as the top-level SCSS selector.

### 2. Set up ACF fields

Create a new ACF field group in the WordPress admin (Custom Fields > Add New):

- **Group title:** Use the page name (e.g. "Your Page Name").
- **Location rule:** Page Template is equal to `page-templates/page-your-page-name.php`.
- **Field naming:** Prefix all fields with a short abbreviation, e.g. `yp_hero_heading`, `yp_hero_subheading`. Use underscores, lowercase.
- **Common field types:** Text, Textarea, WYSIWYG, Repeater (for lists of items), Image (return format: array or URL).
- **Save:** ACF JSON syncs automatically to `acf-json/`. The filename is generated from the group title (spaces/underscores become hyphens, lowercased, `.json` extension). Commit the JSON file.
- **Editor-first copy:** set every field's `default_value` to the launch copy, and seed the page's saved values with the same copy once the page exists (one-off `php` script against `wp-load.php`; WP-CLI is not on PATH) — including repeater rows, or editors see empty repeaters while code placeholder arrays render the section. Keep the template's `?:` fallbacks as a blank-field safety net only; the admin screen is the source of truth.

Reference fields in templates:

```php
<?php
// Simple field
$heading = get_field('yp_hero_heading');

// Repeater
if ( have_rows('yp_items') ) :
    while ( have_rows('yp_items') ) : the_row();
        $title = get_sub_field('title');
        $description = get_sub_field('description');
    endwhile;
endif;

// Taxonomy field (for service taxonomy etc.)
$icon = get_field('icon', 'service_' . $term->term_id);
?>
```

### 3. Create the SCSS

Create a folder and files matching the existing pattern:

```
assets/css/components/your-page-name/
├── _your-page-name.scss          (main file, imports components)
└── components/
    ├── _hero.scss
    ├── _section-two.scss
    └── _section-three.scss
```

**Main file** (`_your-page-name.scss`):

```scss
.page-template-page-your-page-name {
  @import "components/hero";
  @import "components/section-two";
  @import "components/section-three";
}
```

**Component file** (e.g. `_hero.scss`), using the redesign patterns (see the Design Language section):

```scss
.your-page-hero {
  @include vc-section-padding;
  background: $vc-surface-white;
  transition: background 0.3s;

  .content {
    h2 {
      @include vc-display-2($vc-text-dark);
      transition: color 0.3s;

      span {
        color: $vc-primary;
      }
    }

    .sub-heading {
      @include vc-p($vc-grey-600);
      max-width: 56ch;
      margin: $space-2 0 0;
      transition: color 0.3s;
    }
  }

  .button {
    @include vc-button-forge;
  }
}

@at-root body.dark-mode .your-page-hero {
  background: $dark-vc-background-dark !important;

  .content h2 {
    color: $vc-text-light !important;
  }

  .content .sub-heading {
    color: $vc-muted-on-dark !important;
  }
}
```

Pair backgrounds across modes: `$vc-surface-white` (#FFFFFF) sections go `$dark-vc-background-dark` (#1E1E1E) in dark mode, `$vc-background-white` (#F5F5F5) sections go `$dark-vc-background-dark-alt` (#121212), and adjacent sections alternate the pair. The `!important` on dark-mode overrides is required wherever the light rules are nested inside a page scope.

**Import into `app.scss`** by adding a line in the component imports section (around line 50-68):

```scss
@import "components/your-page-name/your-page-name";
```

### 4. Create the JS

Create a folder for the page modules:

```
assets/js/your-page-name/
├── hero.js
├── section-two.js
└── section-three.js
```

**Typical module** (`hero.js`) — new sections use the IntersectionObserver reveal with the reduced-motion guard, so content can never be left hidden (ScrollTrigger/SplitText remain available for scrubbed or split-text work; see the GSAP section):

```js
import gsap from 'gsap';
import { prefersReducedMotion } from '../components/reduced-motion';

document.addEventListener('DOMContentLoaded', () => {
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) return;

    const targets = gsap.utils.toArray('.your-page-hero .content');
    if (!targets.length) return;

    gsap.set(targets, { opacity: 0, y: 24 });

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            gsap.to(entry.target, { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out', clearProps: 'transform' });
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -5% 0px' });

    targets.forEach((el) => observer.observe(el));
});
```

**Add the bundle to `webpack.mix.js`:**

```js
mix.js([
    'assets/js/your-page-name/hero.js',
    'assets/js/your-page-name/section-two.js',
    'assets/js/your-page-name/section-three.js',
], 'js/your-page-name.js');
```

**Enqueue in `inc/styles-scripts.php`** inside the `add_custom_scripts()` function, before the global scripts block:

```php
if ( is_page_template( 'page-templates/page-your-page-name.php' ) ) {
    wp_enqueue_script( 'your-page-name', VC_TEMPLATE_URI . mix('/js/your-page-name.js'), [ 'jquery' ], null, true );
}
```

### 5. Build

From the theme directory (`wp-content/themes/vulkancreative-theme/`):

```bash
npm run production    # Minified build, updates dist/ and mix-manifest.json
npm run watch         # Dev build with file watching
```

### 6. Assign the template

In the WordPress admin, create or edit a page and select the template from the Page Attributes > Template dropdown.

---

## Design Language

The June 2026 "forge" redesign sets the visual language. New pages and redesigned existing pages must read as siblings of the homepage; match these rules before inventing new ones.

### Redesign status (what is on the forge language vs not)

The redesign has rolled out page by page. On the forge language: `front-page.php` (homepage), `header.php`, `footer.php`, the 404 (`404.php` + `template-parts/content-404.php` + the `page-404-preview.php` template), and `page.php` (the default template, used by Privacy Policy and Cookie Policy). Still on pre-redesign patterns (legacy mixins like `vc-button-big`, `vc-h1`, `vc-tag`, `.tag` eyebrows, hard-coded padding) and awaiting the same treatment: `page-your-business.php`, the blog single (`template-parts/content-blog.php`), the archive family (`home.php`, `archive.php`, `template-parts/archive-category.php`, `author.php`), `search.php`, `page-contact-us.php`, and the shared `common/` post grid. A quick tell when auditing a partial: forge components use `vc-display-*`, `vc-section-padding` and the surface tokens; pre-redesign ones use `vc-h1`/`vc-tag`/`vc-button-big`.

### Surfaces and rhythm

- Sections alternate two light surfaces with fixed dark-mode pairings: `$vc-surface-white` (#FFFFFF) pairs with `$dark-vc-background-dark` (#1E1E1E), and `$vc-background-white` (#F5F5F5) pairs with `$dark-vc-background-dark-alt` (#121212). Adjacent sections alternate the pair.
- Full-dark anchor bands (`$vc-background-dark`, #121212 in both modes) punctuate the page: the hero and the why section on the homepage. Where two #121212 sections meet in dark mode, mark the seam with a 1px `$vc-ember-line` rule (see `.our-work`). The footer sits on #0D0D0D.
- Homepage sequence for reference (light mode): hero dark, results #FFF, services #F5F5F5, work #FFF, our-work #F5F5F5, why dark, story #F5F5F5, process #FFF, testimonials #F5F5F5, contact #FFF.
- Section rhythm always via `@include vc-section-padding`; spacing from the 8px `$space-*` scale; z-index from the `$z-*` scale.
- Corners are sharp: 2px on buttons, cards, tiles, plates and arrow buttons; 10px only on large media panels (case stage, portraits). Never rounded pills, anywhere.

### Type

- Display headings are Archivo: `vc-display-1` (hero h1), `vc-display-2` (section h2), `vc-display-3` (card titles). Homepage section h2s render all-caps via `.home.page section .content h2`; carry that treatment to new page sections.
- The red highlight in a heading is a plain `<span>` styled `color: $vc-primary` (see the `hp_heading()` highlight-map pattern in `front-page.php` for editable defaults).
- Body and UI text is Poppins (`vc-p`, `vc-body`); muted copy uses `$vc-grey-600`/`$vc-grey-700` on light and `$vc-muted-on-dark` on dark.
- Labels use `vc-eyebrow`; on light surfaces use `vc-eyebrow-tick($vc-grey-600)` so the tick carries the red (small red text is banned on light surfaces). Standalone eyebrow tags above section headings were removed sitewide; do not reintroduce them.

### Colour and accents

- Red is an accent, never a wash: ticks, metrics, highlighted words, the live-site ↗ arrows, interactive states. Primary CTA is `vc-button-forge` (white on red, the standing exception); secondary is `vc-button-ghost`.
- Ember hairlines (`$vc-ember-line` on dark, `$vc-ember-line-light` on light) are the house rule/seam treatment; red glows (`$vc-glow`, `$vc-glow-soft`) stay radial, soft and sparing.

### Imagery

- Images rest slightly desaturated (`filter: grayscale(0.25)`) and saturate to full colour on hover/focus of their interactive container. Portraits take the duotone treatment (grayscale + brand-red overlay; see testimonials).
- Text over imagery needs two layers: a light fixed depth scrim on the image plus a caption-anchored gradient on the caption block itself, so any caption height keeps 4.5:1 over a worst-case bright image (full pattern and verification method in the Contrast conventions section).
- Badges stamped on imagery are solid plates: `rgba(13,13,13,.85)`, 2px corners, white eyebrow type, no border or tick (see `.tile-service`).
- Tile/card images whose caption announces the content carry `alt=""` so screen readers hear each item once.

### Motion and interaction

- Section reveals use IntersectionObserver + GSAP with nothing pre-hidden without JS or under reduced motion (the `reveal.js` pattern); add new section h2s to `reveal.js`'s selector lists for the standard SplitText line reveal.
- Every animation module imports `prefersReducedMotion()` and degrades to a static, fully functional state; `misc/_motion.scss` is the CSS safety net. State changes (filtering, carousel position) stay functional under reduced motion, just instant.
- Nothing non-interactive ever gets a hover state. Interactive hover feedback is colour/surface/inner-media only (an image zooming inside an overflow-hidden card is fine); the container itself never moves on hover. Content is never gated behind hover; hover drives decorative layers.
- Gate hover styles behind `@media (hover: hover)` (including the dark-mode hover overrides, or they leak on touch) and mirror every hover affordance on `:focus-visible`.
- Controls: 44px minimum targets, square 2px-radius arrow buttons with hairline borders (see the wheel/testimonial arrows), disabled state at 0.35 opacity.
- For custom drag surfaces, follow the work-wheel rules in the JS Architecture section (threshold-deferred pointer capture, `dragstart` suppression, `inert` for off-screen items, keyboard-only recentring, `touch-action: pan-y`).

### Content and editability

- Every editable string lives in ACF with its `default_value` mirroring the live copy, and the page's saved values seeded to match (repeaters included) — the edit screen must always show real content. Template `?:` fallbacks are a safety net, not the source of truth.
- Homepage-fed CPTs (`case_study`, `testimonial`, `project`) stay admin-only until their own pages ship; Our Work curation lives in the homepage `hp_our_work_projects` relationship field.
- Sample content is `[SAMPLE]`-prefixed with `_vc_sample_content` meta so it is easy to find and replace.

---

## Project Overview

This is the WordPress site for Vulkan Creative. The custom theme lives at `wp-content/themes/vulkancreative-theme/`.

## Build Commands

All commands run from the theme directory (`wp-content/themes/vulkancreative-theme/`):

```bash
npm run development    # One-off dev build (Laravel Mix)
npm run watch          # Dev build with file watching
npm run hot            # Dev build with hot module replacement
npm run production     # Minified production build
```

Composer is used in the theme directory to manage WordPress plugin dependencies (installed to `wp-content/plugins/` via wpackagist).

---

## Architecture

### Theme Constants

Defined in `functions.php`:

| Constant | Value |
|---|---|
| `VC_THEME_PREFIX` | `'vc'` |
| `VC_TEMPLATE_URI` | `get_template_directory_uri()` |
| `VC_TEMPLATE_DIR` | `get_template_directory()` |
| `VC_INC_PATH` | `VC_TEMPLATE_DIR . '/inc'` |
| `DISALLOW_FILE_EDIT` | `true` |

### Theme Structure

- **`functions.php`** -- Entry point. Defines the constants above and requires all includes from `inc/`.
- **`inc/`** -- Modular PHP includes:

  - **`actions.php`** -- `vc_setup()` registers theme supports (title-tag, post-thumbnails, responsive-embeds, html5, woocommerce), a custom image size `header-image` (1920x1080), content width 1920, and nav menus. `vc_enqueue()` loads the stylesheet and jQuery. `vc_footer()` injects browser/device detection classes (ios, android, mobile, ie, chrome, firefox, safari, opera) onto the HTML element. Also handles favicon output (custom SVG/PNG suite), disables the content editor on the default `post` type, and registers footer menus.

  - **`filters.php`** -- Sets the document title separator to `|`. Sanitises empty post titles to `'...'`. Adds `itemprop="url"` to nav menu links for schema.org. Disables intermediate image sizes (`medium_large`, `1536x1536`, `2048x2048`). Overrides the post link for `partner_content` CPT to use a `link_to_content` meta field. Adds body classes: `'dark-mode'` always (dark-first default; an inline script in `header.php` removes it for light-mode visitors, see Dark Mode), and `'practice-area-parent'`/`'practice-area-child'` for the `practice_area` taxonomy. Removes the last (duplicate) breadcrumb item from Yoast breadcrumbs.

  - **`remove.php`** -- Unregisters `post_tag` taxonomy from posts. Removes comments and trackbacks from all post types, hides comments from the admin bar and admin menu.

  - **`styles-scripts.php`** -- Enqueues page-specific JS bundles and CSS. Contains the `mix()` helper that reads `dist/mix-manifest.json` for cache-busted asset paths. Full details in the Asset Enqueuing section below.

  - **`acf.php`** -- ACF JSON sync config (saves/loads field groups to `acf-json/`). Sanitises JSON filenames (spaces/underscores to hyphens, lowercased). On `acf/save_post`, updates the post author from an ACF user field named `'author'`.

  - **`template-functions.php`** -- `vc_schema_type()` outputs schema.org `itemscope`/`itemtype` based on page type (Article, ProfilePage, SearchResultsPage, WebPage). `register_custom_page_templates()` auto-discovers and registers page templates from `page-templates/*.php` on `init`.

  - **`custom-taxonomies.php`** -- Registers the `service` taxonomy (hierarchical, on the `post` and `project` types, rewrite slug `'service'`, visible in REST/UI/nav menus). ACF fields on this taxonomy: `icon` (text) and `order` (number).

  - **`custom-post-types.php`** -- Registers the `case_study`, `testimonial` and `project` CPTs. All three are admin-only content: `public` false, `publicly_queryable` false, `show_ui` true, `show_in_rest` true, no archive, no rewrite, `supports` title only. Case studies feed the homepage work section; testimonials feed the homepage testimonials carousel; projects (admin menu "Our Work", the lighter portfolio tier) feed the homepage Our Work section and carry the `service` taxonomy. None has single pages yet; the `project` key is stable so archive/single pages can be added later by flipping the visibility flags and flushing permalinks.

  - **`shortcodes.php`**, **`ajax-calls.php`** -- Empty stubs for future use.

### Template Hierarchy

#### Root templates

| File | Purpose |
|---|---|
| `front-page.php` | Homepage (June 2026 "forge" redesign). 10 sections, each an anchor target: `.hero` `#top` (full-bleed dark hero in both modes, kinetic Archivo headline with rotating `.dynamic-text` words, Spline statue layer in `.graphic`, logo marquee `#logo-splide` at the hero base fed by the `worked_with_logos` options repeater), `.results` `#results` (count-up stats from `hp_results_stats`), `.services` `#services` (service cards from the `service` taxonomy: horizontal scrubbed track on desktop, stacked vertical card list below lg with the same card design; the service icon renders as a large faded watermark in each card's bottom-right corner, direct child of the row; hover styles are gated behind `@media (hover: hover)`, `:focus-visible` carries the same affordance), `.work` `#work` (work index from 3 `case_study` posts where `cs_featured` = 1: case rows + crossfading `.case-stage` on desktop, stacked image cards below lg whose captions sit on a caption-anchored gradient over a lighter depth scrim — same pattern as the Our Work cards, knee held at 70% because these captions run taller — with a full-opacity sector eyebrow so text keeps 4.5:1 over bright uploads; all text always visible, hover only swaps the decorative stage; ends with the `.work-outro` conversion line), `.our-work` `#our-work` (the lighter portfolio tier: the WORK WHEEL `#work-wheel`, a custom GSAP arc carousel of up to 8 projects curated and ordered by the homepage `hp_our_work_projects` relationship field, posts without an image skipped; cards (480/360/300px by breakpoint, aspect 4/3, inside a fixed-height `.wheel-stage` of 560/480/420px so nothing shifts) stand on the rim of a huge invisible wheel — our-work.js derives every card's x/y/rotation/scale/z from one progress value (radius 1800/1400/1150, arc spacing 540/408/336, tangent rotation damped ×0.6) — dragging anywhere spins it with velocity carry and elastic ends, 44px arrows beside the heading (with a "Drag to spin" hint) step it with a `back.out` settle, and on first view the deck fans open from a centre stack; the template orders the cards centre-out (the first pick takes the middle slot, later picks alternate right then left) and the wheel starts on that middle card; the spin range is the full [0, count−1] so every card including the first and last can come to centre (the earlier flank-clamp left edge cards unreachable, especially on mobile) — at the ends one side of the arc is naturally emptier; the centred card carries an `is-front` depth shadow on the list item (not the tile, so the hover glow composes instead of losing the specificity fight); linked tiles get a layered hover (image saturates and scales 1.06 inside the card, an ember sheen sweeps via `::after` at `$z-base` beneath the caption and pill at `$z-raised`, the border heats and a red glow shadow lifts the card; the card itself never moves), mirrored on `:focus-visible`; cards more than ~2 slots out get `inert` + `aria-hidden`; keyboard focus (`:focus-visible` only — mouse focus must not, or clicks fight a recentre tween) recentres the wheel on the focused card; a plain click on any card just opens its link, pointer capture starts only past the 6px drag threshold (capturing on pointerdown retargets the click to the stage and kills the card links), native link drag is suppressed via `dragstart` preventDefault + `user-select: none` (otherwise a spin starting on a card becomes an HTML5 link-drag and `pointercancel` kills it) and a real drag kills any running tween, suppresses the click behind it and blurs any focused card; each card carries a `.tile-service` plate (Yoast primary `service` term via `yoast_get_primary_term_id`, else the first assigned term; a clean solid plate — `rgba(13,13,13,.85)`, 2px corners, eyebrow type, no tick or border) plus sector eyebrow, h3 client name and a 2-line-clamped one-liner on a caption-anchored gradient (the caption block carries its own backdrop sized to itself, so any caption height keeps 4.5:1 over bright uploads, on top of a lighter decorative scrim); tiles with `pj_link` render as external links (new tab, `rel="noopener"`, always-visible red ↗ kept on the last word by a `white-space: nowrap` wrap) while tiles without a link are plain divs with no hover affordance and no place in the tab order; tile images carry `alt=""` because the caption announces the project; under reduced motion the geometry still applies but every change is instant and the entrance is skipped; without JS (`html:not(.js)`, or before the `is-wheel` class lands) the stage is a native horizontal scroller with the controls hidden; in dark mode the section ends with a `$vc-ember-line` hairline to mark the seam against the equally-dark why band), `.why` `#why` (credibility bento: 3 differentiator cells from the `hp_why_items` repeater plus stat/note/CTA cells; static cells, no hover affordance, only the CTA button is interactive), `.story` `#story` (Video.js player `#our-story`), `.process` `#process` (numbered steps from `hp_process_steps`; scrubbed ember timeline, horizontal on desktop and a vertical left rail below lg), `.testimonials` `#testimonials` (spotlight: crossfading `.spotlight-photo` duotone portrait (400px col lg+, 10px radius, grayscale + brand-red soft-light overlay) beside the Splide fade quote, a small `.cite-avatar` duotone headshot beside the attribution below lg, aggregate `.rating-chip`, trust logos, autoplay progress + counter + arrows; quote heights are equalised to the tallest blockquote by testimonials.js (re-measured on resize and fonts.ready); `tm_photo` falls back to `assets/images/testimonials/avatar-placeholder.png`), `.contact` `#contact` (text col-lg-6 / form col-lg-5 offset-lg-1; the heading's `you&nbsp;want` and `to&nbsp;discuss` keep those pairs together once the SplitText reveal reverts — note nbsp only holds after the revert, not during the split; Gravity Form id=2). Copy comes from `hp_` ACF fields; every field's ACF `default_value` mirrors the live copy and the front page's saved values (including the `hp_results_stats`/`hp_process_steps`/`hp_why_items` repeater rows) are seeded with it, so the edit screen always shows the real content — the inline `?:` fallbacks in the template remain only as a blank-field safety net. An `hp_heading()` helper swaps default headings for `<span>`-highlighted versions. Section eyebrow tags were removed in the June 2026 polish (the `hp_*_tag` fields remain in ACF but are not rendered) and section h2s render all-caps (`.home.page section .content h2 { text-transform: uppercase }` in `_homepage.scss`). Below lg the hero uses a different poster entirely: `assets/images/hero/statue-forward.webp`, a forward-facing transparent cut-out (no blend mask, opacity 0.4, `object-position: 15% top`), chosen by viewport in `spline-viewer.js`; desktop keeps `statue-poster.webp` as the Spline fallback behind a long left fade (`mask-image` to 65%). |
| `page.php` | Default page template, used by long-form utility/legal pages (Privacy Policy, Cookie Policy). Forge layout in `.default-page`: a dark `.page-header` title band (Archivo `vc-display-2`, uppercase; its `<div>` — not `<header>`, which would inherit the global fixed nav's `position: fixed` — and `$space-15` top padding clear the fixed site header) over a light `.page-body` with the prose constrained to `col-lg-8`. `.content-area` styles `the_content()` output: Archivo `h2`, Poppins `h3`, near-black body (`$vc-grey-700`), brand-red `::marker` and links, plus table/blockquote styles; full light/dark pairing. |
| `single.php` | Single posts. Calls `get_template_part('template-parts/content', 'blog')`. |
| `home.php` | Blog listing. Custom `WP_Query` with 12 posts per page, Yoast breadcrumbs, post card grid (col-lg-4, col-md-6, col-12), pagination. |
| `author.php` | Author archive. Author avatar, display name, bio, ACF job title (`'user_' . $author_id`), same post grid layout filtered by author. |
| `archive.php` | Checks `is_category()` and calls `get_template_part('template-parts/archive', 'category')`. |
| `search.php` | Minimal. Queries posts and videos by search term, 8 per page. |
| `404.php` | Error response. Loads `get_template_part('template-parts/content', '404')` — the forge 404 design (full-dark band both modes, giant Archivo `.error-code` with a red zero over a radial `$vc-glow`, the `statue-forward.webp` backdrop masked in on desktop, forge + ghost CTAs). Real 404s are canonical-redirected to the homepage on this site, so the design is also reachable for review via the "404 Preview" page template below. |
| `index.php` | Silent fallback (empty). |
| `sidebar.php` | Loads `dynamic_sidebar('default')` if active. |

#### Page templates (`page-templates/`)

| File | Purpose |
|---|---|
| `page-contact-us.php` | Minimal contact page template. |
| `page-404-preview.php` | Renders the shared `template-parts/content-404.php` at a stable URL (`/404-preview/`) so the 404 design can be reviewed and tested, since real 404s redirect to the homepage. The `.error-section` styles key off the section class (not a body class), so they apply on both this template and `404.php`. |
| `page-your-business.php` | Major landing page. Dark mode forced. 6 sections: Hero (Gravity Form id=2 + Splide logo carousel), Problem (repeater grid), Solution (2-pillar layout), Outcomes (numbered repeater), Trust (partner logos + testimonial Splide carousel), CTA (Gravity Form id=2). All sections pull from ACF fields with `yb_` prefix and inline fallback defaults. |

#### Template parts (`template-parts/`)

| File | Called from | Purpose |
|---|---|---|
| `content-blog.php` | `single.php` | Full blog post layout: hero with featured image, breadcrumbs, title, excerpt, category badges, read time; main content from ACF `intro_key_takeaways` + `content` fields with auto-generated heading IDs; FAQs repeater with schema.org JSON-LD; sidebar with auto-generated TOC, author info, newsletter form (Gravity Form id=3). |
| `archive-category.php` | `archive.php` | Category archive: heading with breadcrumbs, 12 posts per page filtered by current category, post card grid, pagination. |
| `content-404.php` | `404.php`, `page-404-preview.php` | The forge 404 section (`.error-section`): error code, heading, copy and the forge/ghost CTA pair. Shared so the real error response and the preview page render identically. |

#### `get_template_part()` pattern

```php
get_template_part('template-parts/content', 'blog');
// Loads template-parts/content-blog.php
```

### Header and Footer

**`header.php`:**
- Dark-first inline script straight after the opening `<body>` tag: removes the `dark-mode` body class (rendered by `filters.php`) before first paint when `localStorage.getItem('darkMode') === 'disabled'`. The script is skipped on the your-business template, which stays forced dark.
- Preloads Archivo-Variable plus Poppins Regular/SemiBold/Bold woff2 files.
- Loads the CookieYes banner and the Meta Pixel.
- Logo: inline SVG via `file_get_contents()`.
- Fixed header bar. Gets class `hero-active` on `is_front_page()`, where it sits transparent over the hero. `header.js` adds `header--scrolled`/`header--hidden` as the visitor scrolls.
- Navigation and theme toggle are **hidden** on the your-business template (`is_page_template('page-templates/page-your-business.php')`).
- Conditional menu: `main-menu-home` on front page, `main-menu-other` elsewhere. `scrollspy.js` highlights the in-view section's item with `.current-anchor`.
- Theme toggle: `<button class="theme-toggle">` with an inline sun SVG (styled by the theme-toggles "classic" CSS), present in the desktop bar and inside the mobile menu.
- Mobile menu: full-screen overlay (`.mobile-menu`) holding the same conditional menu plus a theme toggle and social links. The toggle button carries `aria-expanded`/`aria-controls` and open/close SVG icons.
- Schema.org: `<nav>` has `itemscope itemtype="https://schema.org/SiteNavigationElement"`.

**`footer.php`:**
- CTA row (`.footer-cta`): "Next step" eyebrow, heading and a Start a project button linking to `/#contact`.
- Main row (`.footer-main`), Bootstrap grid: logo + newsletter form (Gravity Form id=3); "Explore" menu column (conditional `footer-menu-home`/`footer-menu-other` based on `is_front_page()`); socials column (LinkedIn, TikTok, Instagram, YouTube, inline SVGs with labels); bottom bar with copyright year + legal links (Privacy Policy, Cookie Settings).
- `.footer-wordmark` (aria-hidden): oversized Archivo "Vulkan" wordmark at 5% opacity, cropped by the footer's `overflow: hidden` as a closing brand moment.

### Menus

4 registered menu locations:

| Location slug | Purpose | Registered in |
|---|---|---|
| `main-menu-home` | Header nav on front page | `actions.php` |
| `main-menu-other` | Header nav on all other pages | `actions.php` |
| `footer-menu-home` | Footer nav on front page | `actions.php` |
| `footer-menu-other` | Footer nav on all other pages | `actions.php` |

Display logic: `is_front_page()` selects the `*-home` variant; all other pages use `*-other`.

### Dark Mode

Dark mode is the **default** for every page (dark-first). Light mode is the opt-out, persisted in `localStorage`. Four layers work together:

1. **PHP (filters.php):** Adds the `'dark-mode'` body class on every page for every visitor, so the server renders dark and there is no flash for the majority case.

2. **PHP (header.php):** Inline `<script>` straight after the opening `<body>` tag removes the class before first paint when `localStorage.getItem('darkMode') === 'disabled'`. The script is skipped on the your-business template, so that page is always dark.

3. **JS (`assets/js/global/dark-mode.js`):** On load, only syncs all `.theme-toggle` buttons with the resolved state. On click, toggles `dark-mode` on `<body>` and persists `'enabled'`/`'disabled'` to `localStorage`. On the your-business template (detected by the `page-template-page-your-business` body class), dark mode is forced and cannot be toggled.

4. **SCSS:** Uses the `@at-root body.dark-mode` selector pattern inside each component file. Dark mode variables: `$dark-vc-background-dark: #1E1E1E`, `$dark-vc-background-dark-alt: #121212`. Inside the `.home.page` scope the dark-mode overrides need `!important` to win on specificity. See the SCSS Architecture section for the full pattern.

### Gravity Forms

Two forms are used:

| Form ID | Purpose | Locations |
|---|---|---|
| 2 | Contact/enquiry form | `front-page.php` (contact section), `page-your-business.php` (hero + CTA sections) |
| 3 | Newsletter subscription | `footer.php`, `template-parts/content-blog.php` (sidebar) |

Embedding pattern:

```php
<?php echo do_shortcode( '[gravityform id="2" title="false" description="false" ajax="true"]' ); ?>
```

All forms use `ajax="true"` for no-page-reload submission. Title and description are hidden.

### Custom Taxonomies

**`service`** (registered in `custom-taxonomies.php`):
- Hierarchical, applied to the `post` and `project` types.
- Rewrite slug: `'service'` (front stripped).
- Shows in UI, nav menus, REST API.
- ACF fields: `icon` (text, filename of service icon), `order` (number, for sorting).
- Used on the homepage services section: a `.service-rail` of `.service-row` anchor rows (index, icon, title, description, arrow), sorted by the `order` field, each linking to `#contact`. Icons loaded from `assets/images/icons/services/`.
- ACF field retrieval for taxonomy terms: `get_field('icon', 'service_' . $term->term_id)`.

**Removed:** `post_tag` taxonomy is unregistered from posts.

---

## SCSS Architecture

### Folder structure

All SCSS lives in `assets/css/`:

```
assets/css/
├── app.scss                          # Main entry point
├── _fonts.scss                       # @font-face declarations
├── _variables.scss                   # All variables
├── _mixins.scss                      # All mixins
└── components/
    ├── 404/
    │   └── _404.scss
    ├── archive/
    │   ├── author/
    │   │   ├── _author.scss
    │   │   └── components/
    │   │       └── _heading.scss
    │   ├── blog/
    │   │   └── _blog.scss
    │   ├── category-term/
    │   │   └── _category-term.scss
    │   └── components/
    │       ├── _heading.scss
    │       ├── _pagination.scss
    │       └── _posts.scss
    ├── common/
    │   ├── _pagination.scss
    │   └── _post-grid.scss
    ├── contact-us/
    │   └── _contact-us.scss
    ├── content/
    │   └── post/
    │       ├── _post.scss
    │       └── components/
    │           ├── _content.scss
    │           ├── _faqs.scss
    │           ├── _heading.scss
    │           └── _sidebar.scss
    ├── default-page/
    │   └── _default-page.scss
    ├── footer/
    │   └── _footer.scss
    ├── header/
    │   ├── _header.scss
    │   └── components/
    │       ├── _desktop.scss
    │       └── _mobile.scss
    ├── homepage/
    │   ├── _homepage.scss
    │   └── components/
    │       ├── _contact.scss
    │       ├── _hero.scss
    │       ├── _our-work.scss
    │       ├── _process.scss
    │       ├── _results.scss
    │       ├── _services.scss
    │       ├── _story.scss
    │       ├── _testimonials.scss
    │       ├── _text-marquee.scss
    │       ├── _why.scss
    │       └── _work.scss
    ├── misc/
    │   ├── _motion.scss              # prefers-reduced-motion safety net
    │   └── _preloader.scss           # legacy, no longer imported
    ├── search/
    │   └── _search.scss
    └── your-business/
        ├── _your-business.scss
        └── components/
            ├── _cta.scss
            ├── _hero.scss
            ├── _logo-bar.scss
            ├── _outcomes.scss
            ├── _problem.scss
            ├── _solution.scss
            └── _trust.scss
```

### Import order (`app.scss`)

1. External libraries: video.js + city theme, theme-toggles, Splide core, Bootstrap SCSS
2. Core theme: `_fonts.scss`, `_variables.scss`, `_mixins.scss`
3. Global styles: CSS custom properties (`--app-height`), base resets, dark mode body transition, a global `:focus-visible` outline ring for keyboard users
4. Components: misc/motion, header, homepage, your-business, default-page, 404, contact-us, post content, archive pages, footer

### Variables (`_variables.scss`)

#### Colours

| Variable | Hex | Usage |
|---|---|---|
| `$vc-primary` | `#FF3B30` | Brand red. Buttons, links, borders, accents, hover states. |
| `$vc-secondary` | `#FF4500` | Orange-red. Forge button hover state, ember hairlines. |
| `$vc-background-white` | `#F5F5F5` | Default page background (light mode). |
| `$vc-background-dark` | `#121212` | Near-black background. |
| `$vc-text-light` | `#F5F5F5` | Text on dark backgrounds. |
| `$vc-text-dark` | `#121212` | Primary text colour (light mode). |
| `$vc-disabled` | `#c7c7c7` | Disabled/greyed-out state. |
| `$dark-vc-background-dark` | `#1E1E1E` | Dark mode primary background. |
| `$dark-vc-background-dark-alt` | `#121212` | Dark mode secondary/alternate background. |

Bootstrap override: `--bs-body-bg: #F5F5F5`.

#### Supporting neutrals and accents (added with the 2026 redesign)

| Variable | Value | Usage |
|---|---|---|
| `$vc-grey-100` | `#ECECEC` | Hairline borders, subtle fills on light backgrounds. |
| `$vc-grey-200` | `#E0E0E0` | Dividers on light backgrounds. |
| `$vc-grey-400` | `#9A9A9A` | Meta text on light backgrounds. Large text only. |
| `$vc-grey-600` | `#595959` | Muted body text on light (~6.9:1 on `#F5F5F5`). |
| `$vc-grey-700` | `#404040` | Strong muted text on light (~9.7:1). |
| `$vc-surface-white` | `#FFFFFF` | Raised card surface on light backgrounds. |
| `$vc-grey-dark-surface` | `#262626` | Raised card surface on dark backgrounds. |
| `$vc-grey-dark-border` | `rgba(245,245,245,.10)` | Hairline borders on dark. |
| `$vc-muted-on-dark` | `#B5B5B5` | Muted text on `#1E1E1E` (~7.4:1). |
| `$vc-primary-tint` / `$vc-primary-line` | brand red at .08 / .20 | Subtle red fills and lines. |
| `$vc-glow` / `$vc-glow-soft` | brand red at .22 / .10 | Radial "molten" glow centres (forge glow system). |
| `$vc-ember-line` / `$vc-ember-line-light` | `$vc-secondary` at .35 / .25 | Hot hairline rules on dark / light. |

#### Typography

| Variable | Value |
|---|---|
| `$vc-font-family` | `"Poppins", serif` (body and UI text) |
| `$vc-display-font` | `"Archivo", "Poppins", sans-serif` (display headings; variable font, wght 100-900, wdth 62-125%) |
| `$vc-display-stretch` | `125%` (expanded width for display headings) |
| `$vc-display-stretch-tight` | `110%` (sub-display sizes) |
| `$vc-light` | `300` |
| `$vc-normal` | `400` |
| `$vc-medium` | `500` |
| `$vc-semi-bold` | `600` |
| `$vc-bold` | `700` |
| `$vc-black` | `900` (display headings) |

#### Transitions

| Variable | Value |
|---|---|
| `$vc-transition-all` | `all .3s` |

#### Spacing and z-index scales

- Spacing: 8px base. `$space-1` (8px), `$space-2` (16px), `$space-3` (24px), `$space-4` (32px), `$space-5` (40px), `$space-6` (48px), `$space-8` (64px), `$space-10` (80px), `$space-15` (120px, the desktop section rhythm).
- Z-index: `$z-base` (1), `$z-raised` (10), `$z-sticky` (40), `$z-header` (100), `$z-overlay` (1000). Use these instead of ad-hoc values.

#### Breakpoints

All breakpoints come from Bootstrap. The theme uses:

```scss
@include media-breakpoint-down(sm)   // <= 575px
@include media-breakpoint-down(md)   // <= 767px
@include media-breakpoint-down(lg)   // <= 991px
@include media-breakpoint-up(lg)     // >= 992px
```

### Mixins (`_mixins.scss`)

#### Button mixins

**`vc-button($color, $hover-color-text: $color)`**
Standard button. Poppins bold, 16px (14px on lg down). Padding: 10px 23px. Border: 2px solid. Border-radius: 10px. Text: `$vc-text-light`. Hover: transparent background, text changes to `$hover-color-text`.

```scss
.button { @include vc-button($vc-primary); }
```

**`vc-button-big($color, $hover-color-text: $color)`**
Same as `vc-button` but padding: 15px 35px. Legacy primary, still used on the pre-redesign `your-business` template; new work uses `vc-button-forge`.

```scss
.button { @include vc-button-big($vc-primary); }
```

**`vc-button-forge`** (redesign primary; takes no arguments)
Solid `$vc-primary` background with a WHITE label (3.6:1 — Ibrar's explicit standing decision from review; do not "fix" it to dark-on-red). Poppins bold, 16px. Padding: 14px 30px. Border-radius: 2px. Hover: `$vc-secondary` background with a soft red glow shadow.

```scss
.button { @include vc-button-forge; }
```

**`vc-button-ghost($color)`**
Hairline secondary action. Transparent background, 1px border at 45% opacity of `$color`, border-radius 2px. Hover: text and border turn `$vc-primary`.

```scss
.button-ghost { @include vc-button-ghost($vc-text-light); }
```

#### Typography mixins

All accept a `$color` parameter. Desktop size listed first, then tablet (lg down) in brackets.

| Mixin | Desktop | Tablet (lg) | Weight | Use for |
|---|---|---|---|---|
| `vc-h1($color)` | 56px | 45px | bold | Section headings, page titles |
| `vc-h3($color)` | 48px | 42px | bold | Subsection headings |
| `vc-h4($color)` | 28px | 24px | bold | Card headings |
| `vc-p($color)` | 18px | 16px | -- | Paragraphs, descriptions |
| `vc-body($color)` | 16px | 14px | -- | Body text, general content |
| `vc-tag($color)` | 14px | 12px | semi-bold (600) | Section tags/labels |
| `vc-general-h1($color)` | 3.5rem | 2.5rem (sm: 2rem) | bold | Archive page headings |
| `vc-general-p($color)` | 16px | 14px | -- | General paragraphs |

#### Display type mixins (Archivo)

Added with the 2026 redesign. All use `$vc-display-font` with fluid `clamp()` sizes, so there is no separate tablet value.

| Mixin | Size | Weight / width | Use for |
|---|---|---|---|
| `vc-display-1($color)` | `clamp(2.75rem, 6.5vw + 0.5rem, 7rem)` | black (900), 125% stretch | Hero headlines |
| `vc-display-2($color)` | `clamp(2rem, 3.25vw + 0.75rem, 4.25rem)` | black (900), 125% stretch | Section headings |
| `vc-display-3($color)` | `clamp(1.375rem, 1.25vw + 0.875rem, 2rem)` | bold (700), 110% stretch | Card titles, sub-headings |

#### Label, layout and card mixins

**`vc-eyebrow($color: $vc-primary)`**
Uppercase, letter-spaced label built on `vc-tag`.

**`vc-eyebrow-tick($color)`**
`vc-eyebrow` with a 20x2px red tick before the text. Pass a neutral `$color` (for example `$vc-grey-600`) on light surfaces, because small red text fails contrast there; the tick carries the brand accent instead.

**`vc-section-padding`**
Consistent section rhythm: `$space-15` (120px) top/bottom on desktop, `$space-8` (64px) on lg down.

**`vc-card($bg, $border: $vc-grey-200)`**
Neutral-led card: hairline border, soft surface, 10px radius, full height. Red stays reserved for accents.

**`vc-breadcrumbs($color, $span)`**
Styles breadcrumb navigation. `$color` for links, `$span` for separators/text. Links have no underline; underline on hover.

```scss
.breadcrumbs { @include vc-breadcrumbs($vc-text-dark, $vc-text-dark); }
```

### Font setup (`_fonts.scss`)

Two self-hosted families (woff2, `font-display: swap`, font path `../../assets/fonts/`):

- **Poppins** (body and UI): 8 static `@font-face` declarations: Regular (400), Italic (400i), Medium (500), Medium Italic (500i), SemiBold (600), SemiBold Italic (600i), Bold (700), Bold Italic (700i).
- **Archivo** (display headings): variable font, weight range 100-900, `font-stretch` 62%-125%. Two declarations split by `unicode-range`: `Archivo-Variable.woff2` (latin) and `Archivo-Variable-ext.woff2` (latin-ext).

`header.php` preloads `Archivo-Variable.woff2` and Poppins Regular/SemiBold/Bold.

### Naming conventions

- CSS classes use hyphens: `.yb-problem`, `.post-title`, `.hero`.
- Page-specific styles are scoped under the WordPress body class: `.page-template-page-your-business`.
- Homepage styles are scoped under `.home.page`.
- Archive styles: `.blog`, `.archive.author`, `.archive.category`.
- Single post: `.single-post`.
- Dark mode: `@at-root body.dark-mode .selector { ... }`.
- No strict BEM. Classes are descriptive and hyphenated.

### Page-specific SCSS pattern

Each page has a main file that imports its component partials:

```scss
// _your-business.scss
.page-template-page-your-business {
  @import "components/hero";
  @import "components/problem";
  @import "components/solution";
  // ...
}
```

### Dark mode SCSS pattern

Every component that needs dark mode support uses this pattern:

```scss
.section-name {
  background: $vc-background-white;

  h1 { @include vc-h1($vc-text-dark); }
  p { @include vc-body($vc-text-dark); }
}

@at-root body.dark-mode .section-name {
  background: $dark-vc-background-dark-alt !important;

  h1, p { color: $vc-text-light !important; }

  .button:hover {
    color: $vc-text-light !important;
  }
}
```

On the homepage every dark-mode override sits inside the `.home.page` scope (`@at-root body.dark-mode` within each partial) and **must** carry `!important`, otherwise the more specific nested light-mode rules win.

### Contrast conventions

- **No small red text on light surfaces.** `$vc-primary` on `#F5F5F5` is roughly 3.25:1, which fails for body-size text. Use `vc-eyebrow-tick($vc-grey-600)` so the red tick carries the accent instead. Red text is fine on the dark surfaces.
- **Buttons are white-on-red by standing decision.** `vc-button-forge` puts white on `$vc-primary` (3.6:1). Ibrar reviewed and chose this over dark-on-red; treat it as the one deliberate exception to the 4.5:1 floor and do not extend it to non-button text.
- **Text over imagery uses the caption-anchored gradient pattern.** A light fixed depth scrim plus a gradient on the caption block itself (sized to itself, so any caption height stays covered). Verify by alpha-compositing both gradients at each text element's position over a pure-white image; tune the gradient knee to the caption height (`.tile-caption` in `_our-work.scss` uses 60%, the taller `.case-overlay` in `_work.scss` needs 70%).
- **Badges and labels on imagery** are solid plates: `rgba(13,13,13,.85)`, 2px corners, white eyebrow type, no pills, no borders, no ticks.
- **Muted text** uses `$vc-grey-600`/`$vc-grey-700` on light and `$vc-muted-on-dark` on dark.

### Gravity Forms SCSS overrides

Form styling is overridden in each component where a form appears. Key selectors:
- `input[type="text"]`, `input[type="email"]`, `textarea` -- custom borders, no shadows.
- `input:focus`, `textarea:focus` -- `border-color` changes to `$vc-primary`.
- `input[type="submit"]` -- uses button mixins.
- `.gform_confirmation_message` -- custom typography.

### Special components

- **Motion safety net** (`misc/_motion.scss`): a `prefers-reduced-motion: reduce` block that flattens all animations and transitions and forces GSAP-revealed elements (split text, hero content, rolling words) to stay visible even if a script is slow or blocked. JS modules branch on the same query via the shared `prefersReducedMotion()` helper.
- The custom cursor, preloader and firework effect from the previous design were removed in the 2026 redesign (no `loading` body class either). `misc/_preloader.scss` still sits on disk but is not imported; do not use it.

---

## JS Architecture

### Folder structure

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
├── homepage/                   # Front page only
│   ├── hero.js
│   ├── marquee.js
│   ├── why.js
│   ├── story.js
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
├── your-business/              # Your-business template only
│   ├── hero.js
│   ├── logo-bar.js
│   ├── problem.js
│   ├── solution.js
│   ├── outcomes.js
│   ├── testimonials.js
│   └── cta.js
├── single-blog/                # Single posts only
│   └── loading.js
├── archive-blog/               # Blog archive + categories
│   └── loading.js
├── archive-author/             # Author archive
│   └── loading.js
└── components/                 # Shared utility modules
    └── reduced-motion.js       # prefersReducedMotion() helper
```

### Bundles (`webpack.mix.js`)

8 bundles, each concatenating source files:

| Bundle | Source files | Condition |
|---|---|---|
| `global.js` | dark-mode, load-at-top, smooth-scrolling, remove-anchor-from-url | Always |
| `header.js` | header, mobile-menu, scrollspy | Always |
| `footer.js` | footer | Always |
| `homepage.js` | spline-viewer, hero, marquee, why, story, services, work, our-work, process, testimonials, reveal, counter, contact | `is_front_page()` |
| `single-blog.js` | loading | `is_single()` |
| `archive-blog.js` | loading | `is_home() \|\| is_category()` |
| `archive-author.js` | loading | `is_author()` |
| `your-business.js` | hero, logo-bar, problem, solution, outcomes, testimonials, cta | `is_page_template('page-templates/page-your-business.php')` |

### GSAP setup

GSAP v3.12.5 is imported as an npm module. Plugins used:

| Plugin | Registered in | Purpose |
|---|---|---|
| `ScrollTrigger` | Homepage hero, story, services, contact; archive and single-blog modules | Scroll-based animation triggers |
| `SplitText` | Homepage hero, story, contact; archive and single-blog modules | Text line/word splitting for reveal animations |
| `DrawSVGPlugin` | `header/header.js` | SVG path draw animation for the logo |

The newer homepage modules (`reveal.js`, `work.js`, `our-work.js`, `why.js`, `counter.js`) deliberately use `IntersectionObserver` instead of ScrollTrigger, so content can never be left stuck hidden under fast scrolling. Every animation module imports `prefersReducedMotion()` from `assets/js/components/reduced-motion.js` and falls back to a static, fully visible state.

Registration pattern:

```js
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);
```

### Common animation patterns

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

### Shared components

**`reduced-motion.js`** -- Exports `prefersReducedMotion()`, a `matchMedia('(prefers-reduced-motion: reduce)')` check. Every animation module calls it and falls back to a static, fully visible state. Paired with the CSS safety net in `misc/_motion.scss`. (The previous shared modules, `firework-button-effect.js` and `shadow-cursor.js`, were removed along with the custom cursor.)

### Other JS modules

- **`load-at-top.js`**: Sets `history.scrollRestoration = 'manual'`.
- **`remove-anchor-from-url.js`**: Intercepts `#` links, smooth scrolls to target, removes hash from URL via `history.replaceState()`.
- **`header/header.js`**: Class-based fixed header states: `header--scrolled` (gains a surface after 24px) and `header--hidden` (hides on scroll down past 160px, reappears on scroll up). Static on the your-business template. Also runs the DrawSVG logo draw animation, with a reduced-motion fallback that shows the finished logo immediately.
- **`header/scrollspy.js`**: One-page nav highlight. Marks the nav `<li>` whose anchor target is in view with `.current-anchor` (last section whose top sits above 40% of the viewport, rAF-throttled). No-ops on pages whose nav has no same-page anchors.
- **`header/mobile-menu.js`**: Full-screen overlay menu (jQuery). Toggles `mobile-menu-active` on the header and `no-scroll` on the body, updates `aria-expanded`/`aria-label`, moves focus into the menu once the overlay transition finishes, closes on Escape, traps Tab focus while open, and closes then animates scroll for in-menu anchor links.
- **`footer/footer.js`**: Empty stub. The custom cursor and mouse-follow glow were removed; hover/focus states live in CSS.
- **`spline/spline-viewer.js`**: Prepends the poster (`assets/images/hero/statue-poster.webp`) into `.hero .graphic` as an instant visual. Then, only on viewports >= 992px without reduced motion, lazy-loads `@splinetool/viewer` via dynamic `import()` (code-split chunk; `__webpack_public_path__` is set from the inline `window.__vc_public_path`) once fonts are ready, on `requestIdleCallback`, and appends a `<spline-viewer>` for `assets/spline/scene.splinecode`. On mobile and under reduced motion the poster stays as a faint backdrop (`.graphic` at 0.28 opacity, masked).
- **Splide carousels**: homepage `marquee.js` (hero logo marquee `#logo-splide`: loop, free drag, AutoScroll at speed 0.8, 6/4/3 logos per page; static but draggable under reduced motion), homepage `testimonials.js` (`#testimonial-splide`), plus the your-business `logo-bar.js` and `testimonials.js`.
- **`homepage/our-work.js`**: The work wheel, a fully custom GSAP arc carousel (no Splide). One `progress` value drives everything: each card's transform derives from its angle on a large wheel (`x = sin·R`, `y = (1−cos)·R`, tangent rotation damped), so drag, momentum, arrow steps and the fan-open entrance are all tweens of `progress` (or `spread` for the entrance). It starts on the middle card (the template orders cards centre-out) and toggles `is-front` on whichever card holds the centre slot; the hover layers themselves are pure CSS. The drag input pipeline is smoothed: pointer moves only record a target and a rAF loop eases progress toward it (input-rate jitter never reaches the cards), the wheel does not move at all below the 6px threshold (clicks stay still), the start is re-based at the threshold so crossing it never jumps, a mostly-vertical touch gesture hands back to page scrolling, and the release velocity is an exponential moving average so equal flicks throw equally. Gotchas baked in: pointer capture is taken only after the 6px drag threshold (capturing on pointerdown makes the browser retarget the subsequent click to the stage, killing card links); a real drag suppresses the click behind it (capture-phase listener) and blurs any focused card; the spin range clamps to [1, count−2] so the stage never shows a dead half; far cards get `inert` + `aria-hidden`; `touch-action: pan-y` keeps page scroll alive on touch. Reduced motion keeps the wheel fully working with direct pointer tracking, instant steps and no entrance.
- **`homepage/counter.js`**: Counts the `.results` stat numbers up from zero on first view (requestAnimationFrame + IntersectionObserver; keeps prefixes, suffixes and decimals; instant under reduced motion).
- **Video.js**: `story.js` initialises a Video.js player on element `#our-story` (city theme; the videojs-youtube plugin is bundled).

---

## ACF Setup

### Field groups

9 field groups plus an ACF options page definition, stored as JSON in `acf-json/`:

| File | Group | Applies to | Fields |
|---|---|---|---|
| `homepage.json` | Homepage | Page type = Front Page | 41 fields with `hp_` prefix: tag/heading/description text for the sections (including `hp_our_work_heading`/`hp_our_work_subheading`), the `hp_our_work_projects` relationship (post type `project`, max 8, drag-ordered; sets both the selection and the order of the Our Work shelf), plus repeaters `hp_results_stats` (`value`, `label`), `hp_process_steps` (`title`, `description`), `hp_why_items` (`title`, `description`, `proof`, max 3) and `hp_testimonials_logos` (`logo`, `alt_text`), plus the why-section extras `hp_why_stat_value/label`, `hp_why_note_title/text`, `hp_why_cta_text/label` and the testimonials rating chip `hp_testimonials_rating_value/label`. An `hp_testimonials_items` repeater also exists in the group, but the template reads the `testimonial` CPT instead. |
| `case-study.json` | Case Study | Post type = `case_study` | `cs_client_name`, `cs_sector`, `cs_metric_value`, `cs_metric_label` (text), `cs_summary` (textarea), `cs_image` (image, array), `cs_featured` (true/false; the homepage work section queries `cs_featured` = 1) |
| `project.json` | Project | Post type = `project` | The lighter Our Work tier: `pj_client_name` (text, required), `pj_sector` (text), `pj_description` (textarea, required, one sentence), `pj_image` (image, array, required), `pj_link` (url, optional live site; linkless tiles are not clickable). No featured/order fields: curation lives on the homepage in `hp_our_work_projects` |
| `testimonial.json` | Testimonial | Post type = `testimonial` | `tm_quote` (textarea), `tm_name`, `tm_role`, `tm_company` (text), `tm_photo` (image, array; falls back to the theme placeholder headshot) |
| `global-fields.json` | Global Fields | Options page `global-settings` | `worked_with_logos` (repeater: `logo` image). Feeds the hero logo marquee. |
| `global-settings.json` | Global Settings | -- | Not a field group: the ACF UI options page definition ("Global Settings" admin page, slug `global-settings`, data stored in options). |
| `blog.json` | Blog | Post type = `post` | `intro_key_takeaways` (WYSIWYG), `content` (WYSIWYG), `faqs` (repeater: `question` text + `answer` WYSIWYG) |
| `your-business.json` | Your Business | Page template = `page-your-business.php` | 25 fields with `yb_` prefix covering hero, problem, solution, outcomes, trust, CTA sections. Mix of text, textarea, and repeaters. |
| `user.json` | User | All user forms | `job_title` (text) |
| `services.json` | Services | Taxonomy = `service` | `icon` (text), `order` (number) |

### Field naming conventions

- **Homepage fields:** Prefixed `hp_` (e.g. `hp_hero_eyebrow`, `hp_results_stats`).
- **Case study fields:** Prefixed `cs_` (e.g. `cs_client_name`, `cs_featured`).
- **Project (Our Work) fields:** Prefixed `pj_` (e.g. `pj_client_name`, `pj_featured`).
- **Testimonial fields:** Prefixed `tm_` (e.g. `tm_quote`, `tm_company`).
- **Your-business fields:** Prefixed `yb_` (e.g. `yb_hero_heading`, `yb_problem_points`).
- **Global/options fields:** No prefix (e.g. `worked_with_logos`). Referenced with the `'options'` post id.
- **Blog fields:** No prefix (e.g. `intro_key_takeaways`, `content`, `faqs`).
- **User fields:** No prefix (e.g. `job_title`). Referenced as `'user_' . $user_id` in templates.
- **Taxonomy fields:** No prefix (e.g. `icon`, `order`). Referenced as `get_field('icon', 'service_' . $term->term_id)`.
- **Sub-fields (repeaters):** Short, descriptive (e.g. `title`, `description`, `question`, `answer`, `quote`, `name`, `company`, `logo`, `alt_text`).
- **Separators:** Underscores between words. All lowercase.

### JSON sync

- Save path: `VC_TEMPLATE_DIR . '/acf-json'` (configured in `inc/acf.php`).
- Load path: same directory.
- Filename sanitisation: title is lowercased, spaces and underscores become hyphens, `.json` extension appended.
- Field groups sync automatically. Commit the JSON files to version control.

### Common ACF patterns in templates

```php
// Simple field
$heading = get_field('yb_hero_heading');

// With default fallback (your-business pattern)
$heading = get_field('yb_hero_heading') ?: 'Default heading text';

// Repeater loop
if ( have_rows('yb_problem_points') ) :
    while ( have_rows('yb_problem_points') ) : the_row();
        $title = get_sub_field('title');
        $desc  = get_sub_field('description');
    endwhile;
endif;

// Taxonomy term field
$icon  = get_field('icon', 'service_' . $service->term_id);
$order = get_field('order', 'service_' . $service->term_id);

// User field (author pages, blog sidebar)
$job_title = get_field('job_title', 'user_' . $author_id);

// Image field (repeater sub-field, return format: array)
$logo = get_sub_field('logo');
echo '<img src="' . esc_url($logo['url']) . '" alt="' . esc_attr($logo['alt']) . '">';

// Options page repeater (Global Settings, e.g. hero logo marquee)
while ( have_rows('worked_with_logos', 'options') ) : the_row();
    $logo = get_sub_field('logo');
endwhile;

// CPT fields inside a WP_Query loop (homepage work/testimonials sections)
$cs_client = get_field('cs_client_name') ?: get_the_title();
```

---

## Frontend Build (Laravel Mix)

`webpack.mix.js` compiles assets into `dist/`:

- **JS bundles** are page-specific (see JS Architecture above). Each bundle concatenates multiple source files from `assets/js/<section>/` using `mix.js([...sources], 'js/output.js')`.
- **CSS** compiles from `assets/css/app.scss` to `dist/css/app.css`. Option: `processCssUrls: false` (prevents URL rewriting in CSS).
- **PostCSS:** Autoprefixer targeting `last 3 versions`, cascade disabled.
- **Output directory:** `dist/` (set via `mix.setPublicPath('dist')`).
- **Source maps:** Enabled via `mix.sourceMaps()`.
- **Versioning:** `mix.version()` generates `dist/mix-manifest.json` with cache-busting query strings.
- **Notifications:** Disabled via `mix.disableNotifications()`.

### The `mix()` PHP helper

Defined in `inc/styles-scripts.php`. Reads `dist/mix-manifest.json` and returns the versioned path:

```php
function mix( string $path ) {
    $manifestPath = VC_TEMPLATE_DIR . '/dist/mix-manifest.json';
    if ( file_exists( $manifestPath ) ) {
        $manifest = json_decode( file_get_contents( $manifestPath ), true );
        if ( isset( $manifest[ $path ] ) ) {
            return '/dist' . $manifest[ $path ];
        }
    }
    return '/dist' . $path;
}
```

Example: `mix('/js/global.js')` returns `/dist/js/global.js?id=d08e22e825ffe974605abb44e3c65d88`.

## Key Frontend Libraries

All libraries are npm-installed and bundled (no CDN scripts):

| Library | Version | Purpose |
|---|---|---|
| Bootstrap | 5.3.1 | Grid, utilities, components, responsive breakpoints |
| GSAP | 3.12.5 | Animations (with ScrollTrigger, SplitText, DrawSVGPlugin plugins) |
| SplitType | 0.3.4 | Installed but unused; GSAP SplitText does the text splitting |
| Splide | 4.1.4 | Carousels: hero logo marquee, testimonials (with AutoScroll 0.5.3 extension) |
| Spline Viewer | 1.9.98 | 3D statue scene (web component, lazy-loaded via dynamic import on desktop) |
| Video.js | 8.21.0 | Video player (with YouTube plugin 3.0.1, city theme) |
| Theme Toggles | 4.10.1 | Dark mode toggle component |
| jQuery | WP core | Used as script dependency; several modules use jQuery syntax |

## Plugins

- Advanced Custom Fields Pro (field groups synced as JSON in `acf-json/`)
- Gravity Forms
- Yoast SEO (with ACF integration)
- LiteSpeed Cache
- Query Monitor (dev, installed via Composer)

## Asset Enqueuing

All enqueuing happens in `inc/styles-scripts.php` via the `add_custom_scripts()` function on `wp_enqueue_scripts`.

**Deregistered:** `jquery-ui`.

**Global (always loaded):**

| Handle | File | Dependencies |
|---|---|---|
| `site` (style) | `mix('/css/app.css')` | none |
| `global` | `mix('/js/global.js')` | `jquery` |
| `header` | `mix('/js/header.js')` | `jquery` |
| `footer` | `mix('/js/footer.js')` | `jquery` |

**Conditional (page-specific):**

| Handle | File | Condition | Dependencies |
|---|---|---|---|
| `homepage` | `mix('/js/homepage.js')` | `is_front_page()` | `jquery` |
| `single-blog` | `mix('/js/single-blog.js')` | `is_single()` | `jquery` |
| `archive-blog` | `mix('/js/archive-blog.js')` | `is_home() \|\| is_category()` | `jquery` |
| `archive-author` | `mix('/js/archive-author.js')` | `is_author()` | `jquery` |
| `your-business` | `mix('/js/your-business.js')` | `is_page_template('page-templates/page-your-business.php')` | `jquery` |

All scripts load in the footer (`true` as the last parameter). All depend on `['jquery']`.

The `homepage` handle also gets an inline `window.__vc_public_path` variable (via `wp_add_inline_script`) so webpack's dynamically imported chunks -- the Spline viewer -- resolve under the theme's `dist/` directory instead of the site root.

### Adding a new page-specific bundle

1. Create source files in `assets/js/your-page-name/`.
2. Add a `mix.js([...], 'js/your-page-name.js')` entry to `webpack.mix.js`.
3. Add a conditional `wp_enqueue_script()` block in `inc/styles-scripts.php`:
   ```php
   if ( is_page_template( 'page-templates/page-your-page-name.php' ) ) {
       wp_enqueue_script( 'your-page-name', VC_TEMPLATE_URI . mix('/js/your-page-name.js'), [ 'jquery' ], null, true );
   }
   ```
4. Run `npm run production` to build.

---

## Image and Asset Handling

- **Image sizes:** Custom `header-image` (1920x1080). Intermediate sizes `medium_large`, `1536x1536`, `2048x2048` are disabled.
- **SVG logos:** Inlined via `file_get_contents()` in header and footer.
- **Service icons:** Stored in `assets/images/icons/services/`. Filename stored in ACF `icon` field.
- **Animated icons:** WebP format in `assets/images/animated-icons/`. No longer used on the homepage (the why section dropped them); kept on disk.
- **Social icons:** Inline SVGs in footer.
- **Fonts:** Self-hosted Poppins and Archivo (variable) woff2 files in `assets/fonts/`. Archivo-Variable and key Poppins weights are preloaded in `header.php`.
- **Hero statue posters:** `assets/images/hero/statue-poster.webp` (desktop stand-in for the Spline scene) and `statue-forward.webp` (the below-lg poster, a forward-facing transparent cut-out), chosen by viewport in `spline-viewer.js`.
- **Logo marquee images:** uploaded to the media library and selected in the Global Settings options page (`worked_with_logos` repeater).
- **Spline 3D scene:** `assets/spline/scene.splinecode`.
- **Lazy loading:** `loading="lazy"` attribute used on images.

## Code Style

- **PHP indentation:** Tabs, 1 tab per level.
- **JS indentation:** 4 spaces.
- **SCSS indentation:** 2 spaces.
- **PHP string quoting:** Single quotes preferred.
- **JS modules:** ES6 import/export syntax. Bundled with Laravel Mix (webpack).
- **Comments:** Minimal. Template Name comments in page templates. No PHPDoc blocks on theme functions.
- **Post types:** Classic editor only (block editor disabled). Comments removed entirely.
