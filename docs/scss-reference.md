# SCSS reference

Full SCSS architecture: folder structure, complete variable/colour tables, mixin catalogue, and pattern examples. Moved out of the main CLAUDE.md on 2026-07-03 as a long lookup catalogue: see CLAUDE.md's "SCSS Architecture" section for the condensed version (core colours, naming conventions, dark-mode gotcha, contrast rules) that stays loaded by default.

## Folder structure

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
    ├── about-us/
    │   ├── _about-us.scss                # page scope + the html.js-gated reveal pre-hide/failsafe
    │   └── components/
    │       ├── _founders.scss            # incl. the merged section head + intro-lead
    │       ├── _how.scss
    │       ├── _proof.scss
    │       ├── _story.scss
    │       └── _values.scss
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
    │   ├── _contact-channels.scss        # shared icon channel rows (Contact page + About founder plates)
    │   ├── _intro-lead.scss              # shared red-ruled intro lead (services hub head)
    │   ├── _page-hero.scss               # shared page-hero band (Contact, About, services hub, service pages)
    │   ├── _pagination.scss
    │   ├── _post-grid.scss
    │   ├── _process.scss                 # shared process band (homepage, services hub, service pages)
    │   ├── _results.scss                 # shared results band (homepage + the service results anchor base)
    │   ├── _service-card.scss            # shared service card (hub grid + service pages' related strip)
    │   ├── _testimonial-spotlight.scss   # shared spotlight (homepage testimonials + About proof)
    │   └── _work-wheel.scss              # shared work wheel (homepage Our Work + service pages' recent work)
    ├── contact-us/
    │   ├── _contact-us.scss
    │   └── components/
    │       └── _main.scss                # the hero band is the shared page-hero component
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
    │       ├── _latest-insights.scss
    │       ├── _our-work.scss            # section chrome only; the wheel lives in common/_work-wheel.scss
    │       ├── _process.scss
    │       ├── _results.scss
    │       ├── _services.scss
    │       ├── _story.scss               # legacy, no longer imported (the story section moved to the About page)
    │       ├── _testimonials.scss        # section head + trust logos; the spotlight AND rating chip live in common/_testimonial-spotlight.scss
    │       ├── _text-marquee.scss
    │       ├── _why.scss
    │       └── _work.scss
    ├── misc/
    │   ├── _motion.scss              # prefers-reduced-motion safety net
    │   └── _preloader.scss           # legacy, no longer imported
    ├── search/
    │   └── _search.scss
    ├── service/
    │   ├── _service.scss                 # .tax-service scope + surface classes + hero reveal failsafe
    │   └── components/
    │       ├── _cta.scss
    │       ├── _deliverables.scss        # the welded lattice
    │       ├── _insights.scss
    │       ├── _journey.scss
    │       ├── _related.scss
    │       ├── _results-anchor.scss
    │       └── _work.scss                # section chrome for the shared work wheel
    ├── services-hub/
    │   ├── _services-hub.scss
    │   └── components/
    │       ├── _cta.scss                 # third keep-in-sync copy of the GF overrides
    │       ├── _grid.scss
    │       └── _proof.scss
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

## Import order (`app.scss`)

1. External libraries: video.js + city theme, theme-toggles, Splide core, Bootstrap SCSS
2. Core theme: `_fonts.scss`, `_variables.scss`, `_mixins.scss`
3. Global styles: CSS custom properties (`--app-height`), base resets, dark mode body transition, a global `:focus-visible` outline ring for keyboard users
4. Components: misc/motion, header, the shared common partials (page-hero, testimonial-spotlight), homepage, your-business, default-page, 404, contact-us, about-us, the insights partials (common post-grid + pagination, archive headings and grid, post content), footer

## Variables (`_variables.scss`)

### Colours

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

### Supporting neutrals and accents (added with the 2026 redesign)

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

### Typography

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

### Transitions

| Variable | Value |
|---|---|
| `$vc-transition-all` | `all .3s` |

### Spacing and z-index scales

- Spacing: 8px base. `$space-1` (8px), `$space-2` (16px), `$space-3` (24px), `$space-4` (32px), `$space-5` (40px), `$space-6` (48px), `$space-8` (64px), `$space-10` (80px), `$space-15` (120px, the desktop section rhythm).
- Z-index: `$z-base` (1), `$z-raised` (10), `$z-sticky` (40), `$z-header` (100), `$z-overlay` (1000). Use these instead of ad-hoc values.

### Breakpoints

All breakpoints come from Bootstrap. The theme uses:

```scss
@include media-breakpoint-down(sm)   // <= 575px
@include media-breakpoint-down(md)   // <= 767px
@include media-breakpoint-down(lg)   // <= 991px
@include media-breakpoint-up(lg)     // >= 992px
```

## Mixins (`_mixins.scss`)

### Button mixins

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
Solid `$vc-primary` background with a WHITE label (3.6:1, Ibrar's explicit standing decision from review; do not "fix" it to dark-on-red). Poppins bold, 16px. Padding: 14px 30px. Border-radius: 2px. Hover: `$vc-secondary` background with a soft red glow shadow.

```scss
.button { @include vc-button-forge; }
```

**`vc-button-ghost($color)`**
Hairline secondary action. Transparent background, 1px border at 45% opacity of `$color`, border-radius 2px. Hover: text and border turn `$vc-primary`.

```scss
.button-ghost { @include vc-button-ghost($vc-text-light); }
```

### Typography mixins

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

### Display type mixins (Archivo)

Added with the 2026 redesign. All use `$vc-display-font` with fluid `clamp()` sizes, so there is no separate tablet value.

| Mixin | Size | Weight / width | Use for |
|---|---|---|---|
| `vc-display-1($color)` | `clamp(2.75rem, 6.5vw + 0.5rem, 7rem)` | black (900), 125% stretch | Hero headlines |
| `vc-display-2($color)` | `clamp(2rem, 3.25vw + 0.75rem, 4.25rem)` | black (900), 125% stretch | Section headings |
| `vc-display-3($color)` | `clamp(1.375rem, 1.25vw + 0.875rem, 2rem)` | bold (700), 110% stretch | Card titles, sub-headings |

### Label, layout and card mixins

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

## Font setup (`_fonts.scss`)

Two self-hosted families (woff2, `font-display: swap`, font path `../../assets/fonts/`):

- **Poppins** (body and UI): 8 static `@font-face` declarations: Regular (400), Italic (400i), Medium (500), Medium Italic (500i), SemiBold (600), SemiBold Italic (600i), Bold (700), Bold Italic (700i).
- **Archivo** (display headings): variable font, weight range 100-900, `font-stretch` 62%-125%. Two declarations split by `unicode-range`: `Archivo-Variable.woff2` (latin) and `Archivo-Variable-ext.woff2` (latin-ext).

`header.php` preloads `Archivo-Variable.woff2` and Poppins Regular/SemiBold/Bold.

## Page-specific SCSS pattern

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

## Dark mode SCSS pattern (full example)

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

## Gravity Forms SCSS overrides

The enquiry form (Gravity Form id 2) is the same component on the homepage (`_contact.scss` `.contact .form`) and the Contact page (`contact-us/components/_main.scss` `.contact-main .form`); keep the two blocks in sync. Key selectors:
- `input[type="text"]`, `input[type="email"]`, `textarea` -- underline only (`border-bottom`), `box-shadow: none !important`, and `transition: border-color .3s` (NOT `all`: animating `all` made a focus box flicker between fields).
- `input:focus`, `textarea:focus` -- `border-bottom` turns `$vc-primary`; `box-shadow`/`outline` forced off so no GF focus box shows.
- `.gfield:focus-within .gfield_label` / `:focus-within > label` -- the active field's label turns `$vc-primary` (pure CSS; the old jQuery focus handler was removed).
- `input[type="submit"]` -- uses button mixins.
- `.gform_confirmation_message` -- custom typography.

## Special components

- **Motion safety net** (`misc/_motion.scss`): a `prefers-reduced-motion: reduce` block that flattens all animations and transitions and forces GSAP-revealed elements (split text, hero content, rolling words) to stay visible even if a script is slow or blocked. JS modules branch on the same query via the shared `prefersReducedMotion()` helper.
- The custom cursor, preloader and firework effect from the previous design were removed in the 2026 redesign (no `loading` body class either). `misc/_preloader.scss` still sits on disk but is not imported; do not use it.
