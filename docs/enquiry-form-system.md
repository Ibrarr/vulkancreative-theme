# Enquiry form system (July 2026)

The multi-step enquiry Gravity Form: four steps built for completion, with the service picker driving conditional logic. Live on the Contact page (`/contact/`) for testing; form 2 still serves the homepage, your-business and services-hub embeds. Read this before touching either enquiry form, `common/_enquiry-form.scss`, `assets/js/enquiry-form/`, or `inc/gravity-forms.php`.

## The simple variant (form 6)

**Form id 6, "Enquiry Form - Simple", cssClass `enquiry-form enquiry-form--simple`.** Single step: First Name* / Last Name* (half-width pair), Email*, Phone (optional), "Which service do you need?"* (select, "Select" placeholder, the six service slugs + `something-else`), Send Enquiry, plus the same hidden UTM fields, honeypot, asterisk indicator, notification and confirmation as form 5. Not embedded anywhere yet; Ibrar places it.

It inherits ALL of the multi-step styling by class composition (the `enquiry-form` token makes the wrapper match `.gform_wrapper.enquiry-form_wrapper`); the `--simple` token exists for any variant-specific overrides later (none currently). No JS bundle is needed: it is a plain single-page GF ajax form, and the `gform_confirmation_anchor` filter is pagination-gated so this form keeps GF's native confirmation scroll on pages without the enquiry bundle. To place it: embed `[gravityform id="6" title="false" description="false" ajax="true"]` inside a `.form > .form-container` panel. Verified in both modes at 1440 (rendered, per-field validation, full submission) via a temporary contact-page swap on 5 Jul 2026, then reverted; test entries deleted.

## The free-website variant (form 7)

**Form id 7, "Enquiry Form - Free Website", cssClass `enquiry-form enquiry-form--simple enquiry-form--free-website`.** A GFAPI clone of form 6 minus the service select (5 Jul 2026, for the `/free-website/` offer page): First Name* / Last Name* (half-width pair), Email*, Phone (optional), submit "Send My Enquiry", plus the same hidden UTM fields, honeypot (`honeypotAction: spam`), asterisk indicator, notification and confirmation as form 6. Embedded once, via `vc_render_form( 7 )` in `page-templates/page-free-website.php` (the id lives only there), and the template is registered in the Form Settings picker locations so the page's sidebar `vc_page_form` select can override the default without code. Styling and hooks arrive wholesale through the `enquiry-form`/`--simple` tokens; the `--free-website` token exists for variant overrides (none currently). The page bundle pushes a `free_website_submit` dataLayer event on `gform_confirmation_loaded`. The notification "to" is the cloned `info@vulkancreative.test`; repoint on production alongside forms 5/6. Verified 5 Jul 2026 in both modes (full submission → entry stored + inline confirmation; empty submission → plate + per-field errors); test entry can be deleted from GF > Entries. Full page build notes: `docs/free-website-page.md`.

## The form

- **Form id 5, "Enquiry Form - Contact", cssClass `enquiry-form`.** The id appears in exactly one place in the theme: the shortcode in `page-templates/page-contact-us.php`. Every other hook, style and script keys on the cssClass / the `enquiry-form_wrapper` wrapper class, so the form can be re-created or duplicated without code changes.
- **Steps (GF pagination type `steps`):** Services · Project · About You · Contact.
  1. **Services** — checkbox field 1 (`service-picker` cssClass), multi-select, required. Choices are populated **live from the service taxonomy** (the ordered pillars via `vc_ordered_services()`) + `something-else`, not stored on the form — see "Dynamic service picker (step 1)" below. Hidden UTM fields 13–16 (source/campaign/term/content, prepopulated) sit on this page.
  2. **Project** — textarea 2 (required); website field 3 (optional, shown if any of `web-design-development` / `seo-ai-search` / `strategy-analytics` / `paid-media` is ticked); radio 4 new-build-vs-redesign (optional, web only, `web-design-development`); radio 5 timeframe (optional). The stored form still carries the pre-restructure slugs (`digital-marketing`/`paid-search-ppc`) on fields 3/4; they are remapped to the live pillar slugs at runtime by the same picker filter.
  3. **About You** — HTML note 18 ("Everything on this step is optional."), text 6 company, radio 7 budget bands (GBP), select 8 how-did-you-hear (placeholder "Select").
  4. **Contact** — text 9/10 First/Last Name (half-width pair via shared `layoutGroupId`), email 11, phone 12 (optional, international format). Submit "Send Enquiry". (A trust-line HTML field and a phone helper line were removed in Ibrar's 5 Jul review; do not reintroduce them.)
- Page fields 20/21/22 carry the per-step Continue/Back button text; the form's `lastPageButton` is the final Back. Buttons: Continue / Back / Send Enquiry.
- **Validation** is GF-native per step; custom `errorMessage` strings on fields 1, 2, 9, 10, 11. `validationSummary` false (plate heading only, no link list). Unlike the single-step forms, this form SHOWS required state (Ibrar's 5 Jul review): `requiredIndicator` is `asterisk`, rendered brand red snug against the label text (owner-approved marker; `!important` beats Orbital's rust danger colour, and the label is forced off Orbital's flex or the asterisk detaches as a stretched flex item). Each field error message leads with Ibrar's supplied circle-exclamation icon (`assets/images/icons/error.svg`, inlined red as a data URI) plus semibold text, so it can never be misread as a description.
- **Spam posture identical to form 2:** honeypot on (`honeypotAction: spam`, renders as `input_23`), reCAPTCHA v3 Enterprise applies invisibly (verifies on the page-1 advance and the final submit), Akismet global. On the local `.test` domain the reCAPTCHA add-on records `disconnected` on entries and fails open — it scores normally on the registered live domain.
- **Notifications (two since Aug 2026):** the admin one to `info@vulkancreative.test` (point at the live inbox at deploy), subject "New enquiry from {form_title}", body `{all_fields}`, from-name "Vulkan Creative Website", reply-to the entry's email field; plus a "Submitter Confirmation" autoresponder to the submitter with the one-working-day promise. **Confirmation:** inline message with the same promise.
- The contact page's `ct_form_note` ACF text field ("It takes about a minute.") renders under the form heading; added to `acf-json/contact-us.json` (`field_ct107`) with the value seeded on page 513.

## Dynamic service picker (step 1)

The step-1 "What do you need help with?" choices are **not stored on the form** — they are injected at runtime from the service taxonomy, the same source the mega menu, footer and homepage rail use, so the picker never drifts when services change. `vc_enquiry_populate_service_picker()` (`inc/gravity-forms.php`) hooks `gform_pre_render`, `gform_pre_validation`, `gform_pre_submission_filter` and `gform_admin_pre_render`, finds the checkbox field by its `service-picker` cssClass (never a form id), and rebuilds its `choices` + `inputs` from `vc_ordered_services()` (the ordered pillars) plus a final `something-else`. Added July 2026, after the flat-6 → pillars-and-children restructure left the frozen choices pointing at three renamed, now-dead slugs.

- **Pillars only**, by decision: the 6 pillars, not the 18 children — a 24-option step-1 hurts completion, and the specifics are captured by the project textarea and the discovery call. To expose children later, extend the loop with `vc_service_children()` (and mind the checkbox-icon parent-fallback and the input-count growth past `x.10`).
- **The editor is left alone.** The callback bails when `GFCommon::is_form_editor()` is true, so opening/saving the form in the GF editor never bakes the dynamic choices into the database; the stored (frozen) choices stay there as an inert fallback. The entry-detail screen still gets the live list, so stored entries render the right service labels.
- **Input suffixes** come from `vc_gf_input_suffix()`, which skips every multiple of 10 (GF's legacy `x.10` quirk), so POST names and entry meta keys match GF's own numbering. Labels are `html_entity_decode()`d so "SEO & AI Search" renders with a plain ampersand, not `&amp;`.
- **Conditional-logic remap.** The same pass rewrites any field conditional-logic rule still keyed on a renamed pillar slug (`digital-marketing`→`strategy-analytics`, `paid-search-ppc`→`paid-media`, `content-creation`→`content-marketing`) to the live slug, so the step-2 "current website" / "new or redesign" fields keep firing. Update this map if a pillar is renamed again.

## Theme files

- `assets/css/components/common/_enquiry-form.scss` — everything, scoped under `.gform_wrapper.enquiry-form_wrapper` so the styling travels with the form to any future placement. Steps indicator (Archivo outlined numerals, filled red when completed, ember connectors), picker cards (service-card dialect with corner watermark icons), radio chips, underline inputs incl. url/tel/select, forge/ghost footer buttons, validation plate + messages, spinner recolour, `.is-submitting` dim, full dark-mode twin block.
- `assets/js/enquiry-form/steps.js` → `js/enquiry-form.js` bundle — step entrance animation, scroll/focus management, `role="status"` step announcements, `.is-submitting` loading state, dataLayer events. Self-contained: bails unless an `enquiry-form_wrapper` sits inside a `.form-container`.
- `inc/gravity-forms.php` (required from `functions.php`) — the theme's GF hooks: `gform_confirmation_anchor` → false for `enquiry-form` forms (theme JS owns scrolling); the **dynamic service picker** (`gform_pre_render` / `pre_validation` / `pre_submission_filter` / `admin_pre_render` → `vc_enquiry_populate_service_picker()`, see above); and `gform_field_choice_markup_pre_render` → injects the service SVG watermarks (`.choice-card-icon`) into the picker labels, keyed on choice value — the icon is read from each term's `icon` ACF field, so it is correct for any pillar.
- Enqueue: inside the contact-template block in `inc/styles-scripts.php`. `assets/js/contact/form-progress.js` was fixed alongside (checkable fields now count complete when any option is checked; previously `every(checked)` meant a required checkbox group could never register).

## Moving the form to another page

1. Add `[gravityform id="5" title="false" description="false" ajax="true"]` inside a `.form > .form-container` panel (include the `.form-progress` hairline span if the page's bundle runs form-progress.js).
2. Extend the enqueue condition for the `enquiry-form` handle in `inc/styles-scripts.php`.
3. Nothing else: the SCSS is wrapper-scoped and the JS keys on the wrapper class.

## Verified GF mechanics (2.9.31, markup v2, Orbital) — do not re-derive

- `ajax="true"` posts through a hidden iframe; the outer `#gform_wrapper_5` persists and its contents are replaced wholesale each step. Bind on `document`/the panel, re-query per event.
- Because the form has conditional logic, GF renders it hidden until its JS runs, and every re-render arrives at `opacity: 0` until GF's persistent `gform_post_render` handler re-applies rules. **Animate on `gform_post_render` + double `requestAnimationFrame`, never on `gform_page_loaded`** (the form is still invisible there). `gform_page_loaded` fires only on real AJAX renders and is used as the "this was a navigation" flag.
- No-JS visitors see no form (stock GF behaviour for conditional-logic forms); the contact-channels column is the fallback.
- On validation failure GF re-renders the same page, focuses the `.gform_validation_errors` plate and announces it assertively — the theme must not add its own focus/aria there. `scroll-margin-top` plus a small scroll nudge in steps.js keep the plate clear of the fixed header.
- Steps markup (`.gf_step` + `gf_step_active/completed/pending`) is server-rendered per page; state changes are discrete by design and the panel's `.form-progress` hairline provides the animated continuity.

## Gotchas (each cost real debugging time)

- **The contact column's reveal selectors must stay direct-child (`.contact-form-col > h2`)** in `_main.scss` (light rule, dark rule, pre-hide), `contact/reveal.js` and `misc/_motion.scss`. GF renders its validation summary heading as an `h2` inside the column; a descendant selector pre-hides it, and the error text only appears when the 2.5s failsafe fires.
- **Orbital paints its own focus rings (blue) and a resting navy shadow on buttons**: the partial kills `:focus` rings for mouse clicks and box-shadow at rest, keeping the red house ring on `:focus-visible` only.
- **`appearance: none` selects top-align their text in Chrome** regardless of height; the balanced block padding (13px/12px in the 50px box) does the vertical centring.
- Footer layout: Back is `order: -1` with a forced `margin: 0 auto 0 0 !important` (Orbital's button margins otherwise beat the auto push); Continue/Send float right via `justify-content: flex-end`.

- **Checkbox fields need an `inputs` array** (`{id: '1.1', label}` per choice, skipping ids ending in 0). Choices alone render fine but server-side value collection iterates `inputs`, so a required checkbox validates as empty forever without it.
- **Orbital out-specifies sane wrapper selectors for buttons and the validation plate border** (theme-class stacks + attribute selectors). The forge/ghost button properties and the plate border carry `!important` deliberately — same trade-off as the theme's other GF overrides.
- Orbital gives choice labels fit-content width (cards need `display: flex` on `.gchoice` + `flex: 1 1 auto` on the label), stacks radios in a column (`flex-direction: row` needed for chips), and sizes textareas tall (`height: 140px !important`).
- The Facebook Pixel submits its own tracking form on button clicks — an empty-looking `form.submit()` in devtools is NOT the GF submission.
- After the confirmation, form-progress.js resets the hairline to 0 (no required fields left in the DOM). Pre-existing shared behaviour, cosmetic.
- GF discards values of fields that were conditionally hidden at submit time — re-ticking a service later means its follow-up answers start blank. Correct, not a bug.

## dataLayer events (inert until GTM/GA4 exists)

- `enquiry_step` `{form_id, step, step_name}` — pushed on first interaction (step 1) and on every step render, including back-navigations, so drop-off per step reports directly.
- `enquiry_submit` `{form_id}` — on `gform_confirmation_loaded`.

## Editing copy

Field labels, descriptions, button text, validation messages, the About You optional-step note (HTML field 18), the confirmation and the notification all live in GF admin → form 5. **The step-1 service choices are the exception:** they come from the service taxonomy, not GF admin (see "Dynamic service picker" above) — add or reorder services in Global Settings > Service List. The "It takes about a minute." line is the `ct_form_note` ACF field on the Contact page.
