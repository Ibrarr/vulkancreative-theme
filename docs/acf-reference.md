# ACF reference

Full ACF field group catalogue and code patterns. Moved out of the main CLAUDE.md on 2026-07-03 as a long lookup table: see CLAUDE.md's "ACF Setup" section for the condensed version (field naming/prefix conventions, JSON sync basics) that stays loaded by default.

## Field groups

The core field groups plus an ACF options page definition, stored as JSON in `acf-json/` (the work, case-studies and services groups have their own sections below):

| File | Group | Applies to | Fields |
|---|---|---|---|
| `homepage.json` | Homepage | Page type = Front Page | 38 fields with `hp_` prefix: tag/heading/description text for the sections (including `hp_our_work_heading`/`hp_our_work_subheading` and the latest-insights trio `hp_latest_heading`/`hp_latest_subheading`/`hp_latest_cta_label`), the `hp_our_work_projects` relationship (post type `project`, max 8, drag-ordered; sets both the selection and the order of the Our Work shelf), plus repeaters `hp_results_stats` (`value`, `label`), `hp_process_steps` (`title`, `description`) and `hp_testimonials_logos` (`logo`, `alt_text`), the why-section extras `hp_why_note_title/text` and `hp_why_cta_text/label`. The testimonials rating chip fields `hp_testimonials_rating_value/label` were retired in Aug 2026 (the chip reads the Global Settings Reviews group via `vc_google_reviews()`; fields removed from the JSON, front-page meta deleted). The story fields (`hp_story_tag/heading/description`) were removed when the story section moved to the About page. An `hp_testimonials_items` repeater also exists in the group, but the template reads the `testimonial` CPT instead; the why bento's `hp_why_items` rows and `hp_why_stat_value/label` are no longer registered in the group, though `front-page.php` still reads the saved meta with inline fallbacks. |
| `case-study.json` | Case Study | Post type = `case_study` | `cs_client_name`, `cs_sector`, `cs_metric_value`, `cs_metric_label` (text), `cs_summary` (textarea), `cs_image` (image, array), `cs_featured` (true/false; the homepage work section queries `cs_featured` = 1). The metric doubles as the hero headline on `single-case_study.php` and the card metric everywhere |
| `case-study-details.json` | Case Study Details | Post type = `case_study` | The narrative content for `/case-studies/{slug}/` (July 2026, keys `field_cs_01xx`, seamless, every field optional; empty hides its section): `cs_year` (text), `cs_overview_statement`/`cs_overview_support` (textareas), `cs_challenge_statement` (textarea), `cs_challenge_body`/`cs_approach_body` (textareas, `new_lines` wpautop), `cs_approach_gallery` (repeater: `image` + optional `caption`), `cs_results_stats` (repeater max 4: `value` + `label`), `cs_results_narrative` (textarea), `cs_testimonial` (post_object → `testimonial`, return id) |
| `case-studies-page-settings.json` + `case-studies-page.json` | Case Studies Page | Options sub-page under the Case Studies menu | `csp_` prefix: archive hero heading parts + `csp_hero_subheading` (the positioning line), `csp_filter_all_label`, three-part heading fields for overview/challenge/approach/results/testimonial/related/CTA and `csp_cta_subheading`. Consumed via `vc_heading_parts( 'csp_…', 'options', $fallback )`; values seeded by Settings > Seed Case Studies |
| `project.json` | Project | Post type = `project` | The lighter Our Work tier: `pj_client_name` (text, required), `pj_sector` (text), `pj_description` (textarea, required, one sentence), `pj_image` (image, array, required), `pj_link` (url, optional live site; linkless tiles are not clickable). No featured/order fields: curation lives on the homepage in `hp_our_work_projects` |
| `testimonial.json` | Testimonial | Post type = `testimonial` | `tm_quote` (textarea), `tm_name`, `tm_role`, `tm_company` (text), `tm_photo` (image, array; falls back to the theme placeholder headshot) |
| `global-fields.json` | Global Fields | Options page `global-settings` | Two tabs. **Company info**: `company_email`, `company_phone`, `company_location`, `company_map_url`, the single source for the site's contact details, read by the Contact page and the footer via the `'options'` id. **Hero logos**: `worked_with_logos` (repeater: `logo` image), feeds the hero logo marquee. |
| `global-settings.json` | Global Settings | -- | Not a field group: the ACF UI options page definition ("Global Settings" admin page, slug `global-settings`, data stored in options). |
| `blog.json` | Blog | Post type = `post` | `intro_key_takeaways` (WYSIWYG), `content` (WYSIWYG), `faqs` (repeater: `question` text + `answer` WYSIWYG) |
| `your-business.json` | Your Business | Page template = `page-your-business.php` | 25 fields with `yb_` prefix covering hero, problem, solution, outcomes, trust, CTA sections. Mix of text, textarea, and repeaters. |
| `contact-us.json` | Contact us | Page template = `page-contact-us.php` | Fields with `ct_` prefix for the Contact page: hero (`ct_hero_heading`, `ct_hero_subheading`), `ct_next_heading` + `ct_next_steps` repeater, and `ct_form_heading`. The contact details (email/phone/location/map) are NOT here: they live in Global Settings → Company info (`company_*`), shared with the footer. The details column has no heading; the channels lead. |
| `about-us.json` | About Us | Page template = `page-about-us.php` | Fields with `ab_` prefix for the About page: `ab_hero_heading`/`ab_hero_subheading`, `ab_intro_statement`/`ab_intro_support`, `ab_founders_heading` + `ab_founders` repeater (min 2, max 2: `name`, `role`, `short_bio`, `long_bio` (WYSIWYG, basic toolbar, no media), `photo` (image, array), `linkedin_url`, `email`, `phone`), `ab_story_heading`/`ab_story_description`/`ab_story_button_label`/`ab_story_video_url`, `ab_values_heading` + `ab_values` repeater (`word`, `line`; max 5), `ab_how_heading`/`ab_how_intro` + `ab_how_items` repeater (`title`, `description`), `ab_proof_heading`, and the Aug 2026 press band fields `ab_press_heading_start/_red/_end`, `ab_press_body`, `ab_press_image` (the feature spread repeated as the print run; image, return array), `ab_press_link_label`, `ab_press_link_url`. Every `default_value` mirrors the approved launch copy and the About page's saved values are seeded to match, repeater rows included. |
| `free-website-page.json` | Free Website Page | Page template = `page-free-website.php` | Fields with `fw_` prefix (keys `field_fw1xx` for the three-part heading sets, `field_fw0xx` for the rest) for the standalone offer landing page at `/free-website/`: heading parts for hero/how/ledger/contrast/proof/faq/enquire, `fw_hero_subheading`/`fw_hero_primary_label`/`fw_hero_secondary_label`/`fw_hero_note`, `fw_how_subheading` + `fw_how_steps` repeater (max 4: `title`, `description`), `fw_ledger_subheading` + `fw_ledger_items` repeater (`item`, `detail`) + `fw_ledger_included_label`/`fw_ledger_total_label`/`fw_ledger_total_value`/`fw_ledger_monthly_note`, `fw_contrast_note` + `fw_contrast_usual_title`/`fw_contrast_this_title` + `fw_contrast_rows` repeater (`usual`, `this`), `fw_faqs` repeater (`question`, `answer` textarea; also feeds the FAQPage JSON-LD), `fw_enquire_subheading`/`fw_enquire_note`, `fw_header_cta_label`, `fw_sticky_label`. Every `default_value` mirrors the launch copy and the page's saved values are seeded to match. |
| `user.json` | User | All user forms | `job_title` (text) |
| `services.json` | Services | Taxonomy = `service` | `icon` (text); the old `order` field was removed in July 2026 (ordering lives in the Global Settings Service List, see below) |

## JSON sync

- Save path: `VC_TEMPLATE_DIR . '/acf-json'` (configured in `inc/acf.php`).
- Load path: same directory.
- Filename sanitisation: title is lowercased, spaces and underscores become hyphens, `.json` extension appended.
- Field groups sync automatically. Commit the JSON files to version control.

## Common ACF patterns in templates

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


## Work groups (July 2026)

- **Work Page Settings** (`acf-json/work-page-settings.json` + `work-page.json`): an options sub-page under the Our Work admin menu, prefix `wk_` — hero heading parts + subheading, the "All Work" chip label, and the shared single-page strings (overview/related/CTA heading parts, CTA subheading). Read with `get_field(..., 'options')` / `vc_heading_parts(..., 'options', ...)`. Values are saved (seeded), not just defaults.
- **Project Details** (`acf-json/project-details.json`, `pj_`, seamless, `menu_order` 1 under the base Project group): `pj_year`, `pj_overview_statement`, `pj_overview_support`, `pj_deliverables` (repeater: `label`), `pj_gallery` (repeater: `image` + optional `caption`), `pj_case_study` (post_object → `case_study`; the single source of truth for the project-to-case-study pairing, feeding the project hero button, the full-story band and the case study pages' reverse queries — see `docs/case-studies-system.md`). Every field optional: an empty field hides its section on `single-project.php`.
- Seeder: `inc/project-seed.php` (Settings > Seed Work), the service-seed pattern; also the canonical source of the gallery caption copy.

## Services groups (July and August 2026)

- **Service List** (PHP-registered in `inc/service-list-fields.php`, group `group_vc_service_list`, Global Settings options page): repeater `service_list` (`field_vc_service_list`), one row per pillar: `service` (taxonomy select, top-level only, `field_vc_service_list_term`), `show_menu`/`show_footer`/`show_homepage`/`show_hub` toggles, and since Aug 2026 a nested `children` repeater (`field_vc_service_list_children` → `service`, `field_vc_service_list_child_term`, children-only picker labelled "Pillar › Child") that sets the pillar's child order. Read via `vc_ordered_services($location)` and `vc_service_children($parent_id)`; children left out of the row render after the listed ones alphabetically.
- **Reviews** (PHP-registered in `inc/google-reviews.php`, group `group_vc_google_reviews`, Global Settings, Aug 2026): `google_rating` (text, default 5.0), `google_review_count` (number), `google_rating_date` (date picker, returns `M Y`, the as-of line), `google_profile_url` (url, the Google Business Profile share link). Read only through `vc_google_reviews()` (same file), which filters through `vc_google_reviews` so a Places API fetch can override later.
- **Partner Logos** (PHP-registered in `inc/trust-signals-fields.php`, group `group_vc_partner_logos`, Global Settings, Aug 2026): `partner_logos_heading` (text, the homepage strip label), `partner_logos_footer_label` (text, the footer group label, default "Official partners") + `partner_logos` repeater: `logo` (image, required; both hosts are dark surfaces), `name` (text, alt fallback), `url` (optional link). Rendered by `template-parts/partner-logos.php` on the homepage why band and in the footer trust row.
- **Press Features** (same file, group `group_vc_press_features`, Global Settings, Aug 2026): `press_features` repeater (`logo` required, `name`, `url`) feeding the footer "As featured in" credit; the first row's logo is also the About press section's media fallback.
- **Service Page** (`acf-json/service-page.json`, `sv_`, taxonomy = `service`): full field list in `docs/services-system.md`; Aug 2026 added `sv_related_services` (`field_680b0260`, taxonomy multi_select, return id, no save/load terms) which overrides the derived "More ways we can help" strip when set (chosen order, first three, current term skipped). Group exists in the DB and was synced.

## Case study groups (July 2026)

- Field tables for the **Case Study Details** group and the **Case Studies Page** options are in the table above; full build notes in `docs/case-studies-system.md`.
- The **Service Page** group gained `sv_case_studies_heading_start/_red/_end` for the term pages' case-studies strip (fallback "Case <span>studies</span>").
- Seeder: `inc/case-study-seed.php` (Settings > Seed Case Studies) fills the case study narratives, terms, testimonial links and Yoast metadescs, creates the matching sample projects with their `pj_case_study` links, and seeds the `csp_` options copy.
