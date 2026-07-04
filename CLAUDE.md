# CLAUDE.md

> **Read this file first.** Before making any changes in this theme folder, read this document in full. It contains the conventions, patterns, file paths, variable names, and step-by-step instructions needed to work correctly in this codebase. My global rules (`~/.claude/CLAUDE.md`) already cover git/commit discipline, plan-first workflow, skill auto-loading, browser QA method and breakpoints, and design bans: this file only adds what's specific to this theme.

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository. The custom theme lives at `wp-content/themes/vulkancreative-theme/`.

Long reference catalogues have been moved out of this file into `docs/` next to it, so this file stays fast to load. Each is linked from the relevant section below:
- `docs/template-reference.md`: full file-by-file template/template-part/header/footer/menu/taxonomy walkthrough.
- `docs/scss-reference.md`: full SCSS folder tree, colour/typography/spacing variable tables, full mixin catalogue.
- `docs/js-reference.md`: full JS folder tree, bundle map, GSAP setup, animation pattern catalogue.
- `docs/acf-reference.md`: full ACF field group tables and code patterns.
- `docs/services-system.md`: full build notes for the services hub-and-spoke feature (July 2026).

---

## Project-specific workflow notes

These add to (never replace) the global workflow rules.

- **Local URL for browser QA:** `https://vulkancreative.test/`. Check affected pages in both light and dark mode, alpha-composite contrast where text sits over imagery, and capture screenshots to share.
- **Skill libraries used in this theme:** `ui-ux-pro-max` (`~/.claude/skills/ui-ux-pro-max`, searchable via `python3 scripts/search.py "<query>" --domain <domain>` and `--design-system`) for layout/style/interaction/accessibility/motion; the marketing skills library (`copywriting`, `cro`, `content-strategy`, `product-marketing`, `marketing-ideas`, via the Skill tool) for copy, positioning and conversion. Crawl both yourself per task, decide which genuinely help, and state which you picked and why in the plan.

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
- **Field naming:** Prefix all fields with a short abbreviation, e.g. `yp_hero_heading`, `yp_hero_subheading`. Use underscores, lowercase. (Full prefix map for existing content types is in `docs/acf-reference.md`.)
- **Common field types:** Text, Textarea, WYSIWYG, Repeater (for lists of items), Image (return format: array or URL).
- **Save:** ACF JSON syncs automatically to `acf-json/`. The filename is generated from the group title (spaces/underscores become hyphens, lowercased, `.json` extension). Commit the JSON file.
- **Editor-first copy:** set every field's `default_value` to the launch copy, and seed the page's saved values with the same copy once the page exists (one-off `php` script against `wp-load.php`; WP-CLI is not on PATH), including repeater rows, or editors see empty repeaters while code placeholder arrays render the section. Keep the template's `?:` fallbacks as a blank-field safety net only; the admin screen is the source of truth.

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

**Typical module** (`hero.js`): new sections use the IntersectionObserver reveal with the reduced-motion guard, so content can never be left hidden (ScrollTrigger/SplitText remain available for scrubbed or split-text work; full pattern catalogue in `docs/js-reference.md`):

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

The redesign has rolled out page by page. On the forge language: `front-page.php` (homepage), `header.php`, `footer.php`, the 404 (`404.php` + `template-parts/content-404.php` + the `page-404-preview.php` template), `page.php` (the default template, used by Privacy Policy and Cookie Policy), `page-contact-us.php` (the Contact page), `page-about-us.php` (the About page), the shared `template-parts/page-hero.php` title band with `common/_page-hero.scss`, the shared `common/_testimonial-spotlight.scss` component, and the blog/insights family (redesigned June 2026): the blog single (`template-parts/content-blog.php`), the archive family (`home.php`, `archive.php`, `template-parts/archive-category.php`, `author.php`), `search.php` and the shared `common/` post grid (`.insight-card`), and the services system (July 2026): the services hub (`page-templates/page-services-hub.php`, page 300 at `/services/`) and the service pages (`taxonomy-service.php`, one per `service` term at `/services/{slug}/`); full build notes in `docs/services-system.md`. Still on pre-redesign patterns (legacy mixins like `vc-button-big`, `vc-h1`, `vc-tag`, `.tag` eyebrows, hard-coded padding) and awaiting the same treatment: `page-your-business.php` only. A quick tell when auditing a partial: forge components use `vc-display-*`, `vc-section-padding` and the surface tokens; pre-redesign ones use `vc-h1`/`vc-tag`/`vc-button-big`.

### Surfaces and rhythm

- Sections alternate two light surfaces with fixed dark-mode pairings: `$vc-surface-white` (#FFFFFF) pairs with `$dark-vc-background-dark` (#1E1E1E), and `$vc-background-white` (#F5F5F5) pairs with `$dark-vc-background-dark-alt` (#121212). Adjacent sections alternate the pair.
- **The first section after a page's hero band is always the lighter pair**, `$vc-surface-white` (#FFF) light / `$dark-vc-background-dark` (#1E1E1E) dark, and the alternation proceeds from there (Ibrar's standing rule, July 2026; every template follows it: homepage results, contact main, default-page body, About founders).
- Full-dark anchor bands (`$vc-background-dark`, #121212 in both modes) punctuate the page: the hero and the why section on the homepage. Where two #121212 sections meet in dark mode, mark the seam with a 1px `$vc-ember-line` rule (see `.our-work`). The footer sits on #0D0D0D.
- Homepage sequence for reference (light mode): hero dark, results #FFF, services #F5F5F5, work #FFF, our-work #F5F5F5, why dark, process #FFF, testimonials #F5F5F5, contact #FFF, latest-insights #F5F5F5. (The story section moved to the About page, whose sequence is hero dark, founders #FFF, story #F5F5F5, values #FFF, how #F5F5F5, proof #FFF.)
- Section rhythm always via `@include vc-section-padding`; spacing from the 8px `$space-*` scale; z-index from the `$z-*` scale.
- Corners are sharp: 2px on buttons, cards, tiles, plates and arrow buttons; 10px only on large media panels (case stage, portraits). Never rounded pills, anywhere.

### Type

- Display headings are Archivo: `vc-display-1` (hero h1), `vc-display-2` (section h2), `vc-display-3` (card titles). Homepage section h2s render all-caps via `.home.page section .content h2`; carry that treatment to new page sections.
- The red highlight in a heading is a plain `<span>` styled `color: $vc-primary` (see the `hp_heading()` highlight-map pattern in `front-page.php` for editable defaults).
- Body and UI text is Poppins (`vc-p`, `vc-body`); muted copy uses `$vc-grey-600`/`$vc-grey-700` on light and `$vc-muted-on-dark` on dark.
- Labels use `vc-eyebrow`; on light surfaces use `vc-eyebrow-tick($vc-grey-600)` so the tick carries the red (small red text is banned on light surfaces). Standalone eyebrow tags above section headings were removed sitewide; do not reintroduce them.
- **Not every section needs a visible h2 (standing decision, July 2026, work-archive review).** The display-h2 + intro-lead split head is overused on the site; where the page h1 and the content itself already say what a section is (a listing straight after its hero, for example), skip the visible head and keep a `visually-hidden` h2 for screen-reader structure instead (the `home.php` "Latest insights" precedent; the work archive does the same). Weigh user/SEO value before adding another display h2.

### Colour and accents

- Red is an accent, never a wash: ticks, metrics, highlighted words, the live-site ↗ arrows, interactive states. Primary CTA is `vc-button-forge` (white on red, the standing exception); secondary is `vc-button-ghost`.
- Ember hairlines (`$vc-ember-line` on dark, `$vc-ember-line-light` on light) are the house rule/seam treatment; red glows (`$vc-glow`, `$vc-glow-soft`) stay radial, soft and sparing.
- Core hex values: brand red `$vc-primary` #FF3B30, orange-red `$vc-secondary` #FF4500. Full colour table (all neutrals/accents) is in `docs/scss-reference.md`.

### Imagery

- Images rest slightly desaturated (`filter: grayscale(0.25)`); the pre-ban our-work/work tiles saturate to full colour on hover/focus of their interactive container and stand as the grandfathered exception to the tint-hover ban below. Portraits take the duotone treatment (grayscale + brand-red overlay; see testimonials) and it never changes on hover.
- Text over imagery needs two layers: a light fixed depth scrim on the image plus a caption-anchored gradient on the caption block itself, so any caption height keeps 4.5:1 over a worst-case bright image (full pattern and verification method in the Contrast conventions section below).
- Badges stamped on imagery are solid plates: `rgba(13,13,13,.85)`, 2px corners, white eyebrow type, no border or tick (see `.tile-service`).
- Tile/card images whose caption announces the content carry `alt=""` so screen readers hear each item once.

### Motion and interaction

- Section reveals use IntersectionObserver + GSAP with nothing pre-hidden without JS or under reduced motion (the `reveal.js` pattern); add new section h2s to `reveal.js`'s selector lists for the standard SplitText line reveal.
- Every animation module imports `prefersReducedMotion()` and degrades to a static, fully functional state; `misc/_motion.scss` is the CSS safety net. State changes (filtering, carousel position) stay functional under reduced motion, just instant.
- Nothing non-interactive ever gets a hover state. Interactive hover feedback is colour/surface/inner-media only (an image zooming inside an overflow-hidden card is fine); the container itself never moves on hover. Content is never gated behind hover; hover drives decorative layers.
- **The tint hover is banned outright** (Ibrar's standing decision, July 2026, from the About founders review: "ban the tint hover effect forever"). Hover must never shift an image's tint, duotone overlay or saturation, partially or fully. Duotone imagery stays static until a real state change (an opened or selected panel may saturate). The pre-ban our-work/work tile saturation hover is the one grandfathered exception; do not add new ones or extend it.
- **Gradient sheen sweeps are banned outright** (Ibrar's standing decision, July 2026, from the services review: the moving "shine" on hover). No element may sweep a light/gradient band across itself on hover or any other trigger. The service cards' hover keeps border heat, glow shadow, index fill and the watermark drift only.
- Gate hover styles behind `@media (hover: hover)` (including the dark-mode hover overrides, or they leak on touch) and mirror every hover affordance on `:focus-visible`.
- Controls: 44px minimum targets, square 2px-radius arrow buttons with hairline borders (see the wheel/testimonial arrows), disabled state at 0.35 opacity.
- For custom drag surfaces, follow the work-wheel rules in `docs/js-reference.md` (threshold-deferred pointer capture, `dragstart` suppression, `inert` for off-screen items, keyboard-only recentring, `touch-action: pan-y`).

### Content and editability

- Every editable string lives in ACF with its `default_value` mirroring the live copy, and the page's saved values seeded to match (repeaters included): the edit screen must always show real content. Template `?:` fallbacks are a safety net, not the source of truth.
- Homepage-fed CPTs (`case_study`, `testimonial`, `project`) stay admin-only until their own pages ship; Our Work curation lives in the homepage `hp_our_work_projects` relationship field.
- Sample content is `[SAMPLE]`-prefixed with `_vc_sample_content` meta so it is easy to find and replace.
- **Three-part headings (standing rule, July 2026).** Every main section heading is three ACF text fields, `{name}_start`, `{name}_red`, `{name}_end`, composed by `vc_heading_parts()` in `inc/template-functions.php` (punctuation-aware join; the red part renders as the standard `<span>` highlight and is required: "always make red the important words in the main headings"). When all three parts are blank the template's highlighted fallback string applies. Converted across the homepage (`hp_*`), Contact (`ct_*`), About (`ab_*`), the services hub (`sh_*`) and the service terms (`sv_*`, including `sv_work_heading_*`/`sv_insights_heading_*`/`sv_related_heading_*`; the work and insights fallbacks are the short generics "Recent work"/"Related insights", not per-service names); the old single heading fields were removed and their values migrated into parts. Statements and intro-leads stay single fields. Not yet converted (hard-coded): the footer CTA line, the blog/archive h1s, the 404 and `page-your-business.php`. Give any NEW section heading the same three fields.

---

## Services system (July 2026)

The hub-and-spoke services section at `/services/` and `/services/{slug}/`, built on the existing `service` taxonomy. Full forge language; both page types verified at 375/768/1024/1440 in both modes. Full build detail (URL routing/redirects, hub and service page section-by-section breakdown, the shared WORK WHEEL component, header dropdown, ACF groups, the content seeder) is in `docs/services-system.md`: read that before touching anything under `page-services-hub.php`, `taxonomy-service.php`, or the `sv_`/`sh_` ACF fields.

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

  - **`filters.php`** -- Sets the document title separator to `|`. Sanitises empty post titles to `'...'`. Adds `itemprop="url"` to nav menu links for schema.org. Disables intermediate image sizes (`medium_large`, `1536x1536`, `2048x2048`). Overrides the post link for `partner_content` CPT to use a `link_to_content` meta field. Adds body classes: `'dark-mode'` always (dark-first default; an inline script in `header.php` removes it for light-mode visitors, see Dark Mode), and `'practice-area-parent'`/`'practice-area-child'` for the `practice_area` taxonomy. Removes the last (duplicate) breadcrumb item from Yoast breadcrumbs. A `wpseo_schema_organization` filter enriches Yoast's Organization node (`@id` `/#organization`) with the `company_*` contact details from Global Settings and the four social profile URLs, so the knowledge graph matches the visible site (the About page's Person nodes attach to the same `@id`).

  - **`remove.php`** -- Unregisters `post_tag` taxonomy from posts. Removes comments and trackbacks from all post types, hides comments from the admin bar and admin menu.

  - **`styles-scripts.php`** -- Enqueues page-specific JS bundles and CSS. Contains the `mix()` helper that reads `dist/mix-manifest.json` for cache-busted asset paths. Full details in the Asset Enqueuing section below.

  - **`acf.php`** -- ACF JSON sync config (saves/loads field groups to `acf-json/`). Sanitises JSON filenames (spaces/underscores to hyphens, lowercased). On `acf/save_post`, updates the post author from an ACF user field named `'author'`.

  - **`template-functions.php`** -- `vc_schema_type()` outputs schema.org `itemscope`/`itemtype` based on page type (Article, ProfilePage, SearchResultsPage, AboutPage for the About template, WebPage). `register_custom_page_templates()` auto-discovers and registers page templates from `page-templates/*.php` on `init`.

  - **`custom-taxonomies.php`** -- Registers the `service` taxonomy (hierarchical, on the `post` and `project` types, rewrite slug `'services'` since July 2026 so terms live under the hub at /services/{slug}/, visible in REST/UI/nav menus). ACF fields on this taxonomy: `icon` (text) and `order` (number), plus the Service Page content group (`sv_` prefix, see `docs/services-system.md`).

  - **`custom-post-types.php`** -- Registers the `case_study`, `testimonial` and `project` CPTs. All three are admin-only content: `public` false, `publicly_queryable` false, `show_ui` true, `show_in_rest` true, no archive, no rewrite, `supports` title only. Case studies feed the homepage work section; testimonials feed the homepage testimonials carousel; projects (admin menu "Our Work", the lighter portfolio tier) feed the homepage Our Work section and carry the `service` taxonomy. None has single pages yet; the `project` key is stable so archive/single pages can be added later by flipping the visibility flags and flushing permalinks.

  - **`shortcodes.php`**, **`ajax-calls.php`** -- Empty stubs for future use.

### Template Hierarchy

Full file-by-file walkthrough of every root template, page template and template part is in `docs/template-reference.md`. Quick map:

- **Root templates:** `front-page.php` (homepage, 10 anchor sections), `page.php` (default/legal pages), `single.php`, `home.php` (blog listing), `author.php`, `archive.php`, `search.php`, `404.php`, `index.php` (empty fallback), `sidebar.php`.
- **Page templates:** `page-contact-us.php`, `page-about-us.php`, `page-404-preview.php`, `page-your-business.php` (legacy landing page, still pre-redesign), `page-services-hub.php` (see Services system).
- **Template parts:** `content-blog.php`, `archive-category.php`, `content-404.php`, `page-hero.php` (shared dark hero band used by Contact/About/Services hub/service pages), `work-wheel.php` (shared GSAP arc carousel used by the homepage Our Work section and the service pages' recent-work section).

### Header and Footer

- **Dark mode / hero-active:** every nav-showing page opens on a dark forge band, so the header is transparent over it then surfaces (frosted `backdrop-filter: blur(16px)`) once scrolled, a standing decision from July 2026 ("make the menu bar blurred so you can't just read text behind it"), with solid-colour fallback inside `@supports`. `header.js` adds `header--scrolled`/`header--hidden`. The your-business template hides nav/toggle entirely and keeps its own static transparent header.
- **Services dropdown:** "What We Do" points at `/services/` in both menus; the Main Menu carries six taxonomy child items as a desktop hover/`:focus-within` panel and a mobile overlay accordion. Keep any new header bar-state colour rule scoped to `nav .menu > li > a` (top level only): a rule that reaches `.sub-menu` descendants will wash out the dropdown labels in light mode. Full detail in `docs/services-system.md`.
- Logo is inlined via `file_get_contents()`. Archivo-Variable + key Poppins weights are preloaded.
- Footer carries a CTA row (hidden on Contact), a `.footer-contact` column reading the same Global Settings `company_*` fields as the Contact page, the Explore menu, socials, and an oversized 5%-opacity "Vulkan" wordmark clipped by `overflow: hidden`.
- Full detail (schema.org markup, mobile menu overlay behaviour, exact footer grid) is in `docs/template-reference.md`.

### Menus

2 registered menu locations: `main-menu` (header, every page) and `footer-menu` (footer "Explore", every page), both registered in `actions.php`. One menu serves the whole site per location. Section-anchor items are stored as absolute URLs (`/#why`, home is `/#top`) and a filter in `inc/filters.php` flattens them to in-page anchors on the front page only, so `scrollspy.js` keeps working there. Full detail in `docs/template-reference.md`.

### Dark Mode

Dark mode is the **default** for every page (dark-first). Light mode is the opt-out, persisted in `localStorage`. Four layers work together:

1. **PHP (filters.php):** Adds the `'dark-mode'` body class on every page for every visitor, so the server renders dark and there is no flash for the majority case.
2. **PHP (header.php):** Inline `<script>` straight after the opening `<body>` tag removes the class before first paint when `localStorage.getItem('darkMode') === 'disabled'`. The script is skipped on the your-business template, so that page is always dark.
3. **JS (`assets/js/global/dark-mode.js`):** On load, only syncs all `.theme-toggle` buttons with the resolved state. On click, toggles `dark-mode` on `<body>` and persists `'enabled'`/`'disabled'` to `localStorage`. On the your-business template (detected by the `page-template-page-your-business` body class), dark mode is forced and cannot be toggled.
4. **SCSS:** Uses the `@at-root body.dark-mode` selector pattern inside each component file. Dark mode variables: `$dark-vc-background-dark: #1E1E1E`, `$dark-vc-background-dark-alt: #121212`. Inside the `.home.page` scope the dark-mode overrides need `!important` to win on specificity. Full pattern example in `docs/scss-reference.md`.

### Gravity Forms

Two forms are used: **id 2** (Contact/enquiry) on `front-page.php`, `page-contact-us.php`, `page-your-business.php`; **id 3** (Newsletter) on `footer.php`, `template-parts/content-blog.php` sidebar.

```php
<?php echo do_shortcode( '[gravityform id="2" title="false" description="false" ajax="true"]' ); ?>
```

All forms use `ajax="true"`. Title and description are hidden. SCSS override details in `docs/scss-reference.md`.

### Custom Taxonomies

**`service`** (registered in `custom-taxonomies.php`): hierarchical, on `post` and `project` types. Rewrite slug `'services'` (changed from `'service'` in July 2026; old URLs 301 via the shim in `inc/actions.php`; flush rewrite rules after any change). ACF fields: `icon` (text), `order` (number). Feeds the homepage `.service-rail`, each term's own page (`taxonomy-service.php`), and a card on the services hub. Field retrieval: `get_field('icon', 'service_' . $term->term_id)`.

**Removed:** `post_tag` taxonomy is unregistered from posts.

---

## SCSS Architecture

Full folder tree, complete colour/typography/spacing variable tables and the full mixin catalogue are in `docs/scss-reference.md`. Core facts that apply to every new section:

- Entry point `assets/css/app.scss` imports `_fonts.scss`, `_variables.scss`, `_mixins.scss`, then components in a fixed order (motion safety net, header, shared common partials, homepage, your-business, default-page, 404, contact-us, about-us, insights partials, footer).
- Core brand hex: `$vc-primary` #FF3B30 (accent only, never a wash), `$vc-secondary` #FF4500 (hover/ember). Light surfaces `$vc-surface-white` #FFFFFF and `$vc-background-white` #F5F5F5; dark pairings `$dark-vc-background-dark` #1E1E1E and `$dark-vc-background-dark-alt` #121212; footer #0D0D0D.
- Spacing is an 8px scale (`$space-1` to `$space-15`); z-index is a fixed scale (`$z-base` 1 to `$z-overlay` 1000). Use these, never ad-hoc values.
- Naming: hyphenated classes (`.yb-problem`, `.hero`), no strict BEM. Page-specific styles scoped under the WordPress body class (`.page-template-page-your-business`); homepage under `.home.page`; dark mode via `@at-root body.dark-mode .selector`.
- Several partials are now explicitly shared across page families and live in `common/`: `_page-hero.scss`, `_process.scss`, `_results.scss`, `_service-card.scss`, `_testimonial-spotlight.scss`, `_intro-lead.scss`, `_work-wheel.scss`. When editing one, check `docs/scss-reference.md` for every page family that consumes it before assuming a change is page-local.
- **Dark-mode gotcha:** every dark-mode override inside the `.home.page` scope (or any nested page scope) **must** carry `!important`, or the more specific nested light-mode rule wins. Full before/after example in `docs/scss-reference.md`.

### Contrast conventions

- **No small red text on light surfaces.** `$vc-primary` on `#F5F5F5` is roughly 3.25:1, which fails for body-size text. Use `vc-eyebrow-tick($vc-grey-600)` so the red tick carries the accent instead. Red text is fine on the dark surfaces.
- **Buttons are white-on-red by standing decision.** `vc-button-forge` puts white on `$vc-primary` (3.6:1). Ibrar reviewed and chose this over dark-on-red; treat it as the one deliberate exception to the 4.5:1 floor and do not extend it to non-button text.
- **Text over imagery uses the caption-anchored gradient pattern.** A light fixed depth scrim plus a gradient on the caption block itself (sized to itself, so any caption height stays covered). Verify by alpha-compositing both gradients at each text element's position over a pure-white image; tune the gradient knee to the caption height (`.tile-caption` in `_our-work.scss` uses 60%, the taller `.case-overlay` in `_work.scss` needs 70%, and the very tall `.founder-caption` in the About `_founders.scss` brings the knee forward to 16% with a `$space-8` top fade).
- **Badges and labels on imagery** are solid plates: `rgba(13,13,13,.85)`, 2px corners, white eyebrow type, no pills, no borders, no ticks.
- **Muted text** uses `$vc-grey-600`/`$vc-grey-700` on light and `$vc-muted-on-dark` on dark.

### Special components

- **Motion safety net** (`misc/_motion.scss`): a `prefers-reduced-motion: reduce` block that flattens all animations and transitions and forces GSAP-revealed elements (split text, hero content, rolling words) to stay visible even if a script is slow or blocked. JS modules branch on the same query via the shared `prefersReducedMotion()` helper.
- The custom cursor, preloader and firework effect from the previous design were removed in the 2026 redesign (no `loading` body class either). `misc/_preloader.scss` still sits on disk but is not imported; do not use it.

---

## JS Architecture

Full folder tree, bundle map, GSAP plugin registry and the complete animation-pattern catalogue are in `docs/js-reference.md`. Core facts:

- GSAP v3.12.5 (npm), with `ScrollTrigger`, `SplitText` and `DrawSVGPlugin` (logo only). Newer homepage modules (`reveal.js`, `work.js`, `our-work.js`, `why.js`, `counter.js`) deliberately use `IntersectionObserver` instead of ScrollTrigger, so content can never be left stuck hidden under fast scrolling; **this is the preferred pattern for new sections** (see the IntersectionObserver example in "How to Add a New Page" above).
- Every animation module must branch on the reduced-motion query, via the shared `prefersReducedMotion()` helper (`assets/js/components/reduced-motion.js`) or a `gsap.matchMedia('(prefers-reduced-motion: no-preference)')` context, and fall back to a static, fully visible state.
- 10 webpack bundles, each page-condition-gated (`is_front_page()`, `is_page_template(...)`, etc.); full bundle-to-source map in `docs/js-reference.md`.
- The work wheel (`homepage/our-work.js`, now shared with the service pages via `template-parts/work-wheel.php`) and other bespoke drag/carousel work carry specific gotchas (pointer-capture timing, click-suppression, touch-action) documented in full in `docs/js-reference.md`: read it before touching any custom drag surface.

---

## ACF Setup

Full field group tables (all 11 groups + options page) and code patterns are in `docs/acf-reference.md`. Field naming conventions (apply these to any new field group):

- **Homepage:** `hp_`. **Case study:** `cs_`. **Project/Our Work:** `pj_`. **Testimonial:** `tm_`. **Your-business:** `yb_`. **Contact page:** `ct_`. **About page:** `ab_`.
- **Global/options fields:** no prefix (e.g. `worked_with_logos`, `company_email`/`company_phone`/`company_location`/`company_map_url`), referenced with the `'options'` post id.
- **Blog fields:** no prefix (`intro_key_takeaways`, `content`, `faqs`). **User fields:** no prefix (`job_title`), referenced as `'user_' . $user_id`. **Taxonomy fields:** no prefix (`icon`, `order`), referenced as `get_field('icon', 'service_' . $term->term_id)`.
- **Sub-fields (repeaters):** short and descriptive (`title`, `description`, `question`, `answer`, `quote`, `name`, `company`, `logo`, `alt_text`). Separators are underscores, all lowercase.
- JSON sync: save/load path `VC_TEMPLATE_DIR . '/acf-json'` (`inc/acf.php`); filenames auto-sanitised from the group title; commit the JSON files.

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

**Global (always loaded):** `site` (style, `mix('/css/app.css')`), `global`/`header`/`footer` (JS, all depend on `jquery`).

**Conditional (page-specific):** `homepage` (`is_front_page()`), `single-blog` (`is_single()`), `archive-blog` (`is_home() || is_category()`), `archive-author` (`is_author()`), `your-business`/`contact`/`about` (matching `is_page_template(...)`). All load in the footer, all depend on `jquery`.

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
