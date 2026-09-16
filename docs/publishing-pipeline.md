# Publishing pipeline

Local files in, populated WordPress content out. The server side lives in this theme (`inc/rest-publish.php`); the client side (the `vc-publish` CLI and every content source file) lives in `~/repos/vulkancreative-content`. Authoring is interview-driven through five global Claude Code skills. Built and verified August 2026, local site only; live rollout waits on the checklist at the end of this file.

## The routes

All three registered on `rest_api_init` in `inc/rest-publish.php`, namespace `vc/v1`, Application Password auth, `manage_options` capability. Anonymous callers get 401.

### `POST /wp-json/vc/v1/publish`

Composite create-or-update keyed on `type` + `slug`. Supported types: `post`, `project`, `case_study`, `testimonial`, `page` (landing pages). Request shape:

```json
{
  "type": "case_study",
  "slug": "client-name",
  "title": "Client Name",
  "status": "draft",
  "overwrite": false,
  "dry_run": false,
  "core": { "excerpt": "...", "featured_media": 123, "author": 2, "template": "page-templates/page-landing-page.php", "date": "2026-03-15" },
  "fields": { "cs_client_name": "...", "cs_results_stats": [ { "value": "1.8x", "label": "..." } ] },
  "refs": { "cs_testimonial": "client-name-testimonial" },
  "terms": { "service": ["branding"] },
  "primary_terms": { "service": "branding" },
  "seo": { "title": null, "description": "..." }
}
```

Semantics, all verified by harness:

- **Validation first.** Everything is validated (required fields including ACF's own flags and the per-type hard gates, term existence, attachment existence and mime, reference resolution, template registration), and a failure returns 400 with named errors and writes nothing.
- **Drafts by default.** `status` is only applied when sent; create defaults to draft. Publishing is an explicit choice.
- **Non-destructive.** On update, non-empty fields are kept unless `overwrite` is true. The response carries a per-field report (`set`, `kept existing`, `skipped (empty incoming)`).
- **`dry_run`** runs the full validation and returns a would-do diff without writing.
- **`core.date`** (project and case_study, YYYY-MM-DD, Sep 2026) is the go-live date: it writes `post_date`/`post_date_gmt` on create and on update (with `edit_date`, or a draft's date resets to now), which is what orders /work/, /case-studies/ and the service-page strips. It is placement rather than editor content, so it applies on every run whatever `overwrite` says, and a re-run moves the entry. Future dates are rejected because WordPress would schedule the post.
- **ACF by key.** `fields` arrive as names; the endpoint resolves them against the live ACF registry per type (per layout for `lp_sections` flexible content) and writes with `update_field()` by key, the pattern the retired seeders proved.
- **Slashing.** Values are pre-slashed before `update_field` because `update_metadata` unslashes once; without it a literal backslash in content is silently eaten.
- **Refs.** `refs` carry slugs (`pj_case_study`, `cs_testimonial`); the endpoint resolves them to IDs, errors when missing, warns when the target is not published (its section will not render).
- **Terms.** Resolved by slug, never auto-created. `primary_terms` writes Yoast's `_yoast_wpseo_primary_{tax}` meta, which the card templates read.
- **SEO.** `seo.title`/`seo.description` write `_yoast_wpseo_title`/`_yoast_wpseo_metadesc`, then a no-op `wp_update_post` rebuilds the Yoast indexable.
- **Type quirks.** The three CPTs are title-only (core content/excerpt/thumbnail do not exist on them; imagery lives in ACF). `post` has no core content either (the editor is removed); blog bodies live in the ACF `intro_key_takeaways` + `content` fields, and the excerpt must be explicit. `page` publishes always carry the landing template.

### `GET /wp-json/vc/v1/publish-schema`

The resolved per-type model: fields (names, types, required flags, layouts, sub-fields), taxonomies with every term, the per-type `require` list, plus `_environment` (registered page templates and the live Gravity Forms list). The CLI validates against this, so client and server can never disagree. `vc-publish schema [type]` prints it.

### `GET /wp-json/vc/v1/media?hash=<sha256>`

Attachment lookup by source hash. The CLI stamps `_vc_source_hash` (registered on attachments with an auth callback) on every upload through core `/wp/v2/media`, and checks here first, so the same file never uploads twice.

## The CLI

```bash
node ~/repos/vulkancreative-content/tools/vc-publish.js validate <file-or-dir...>
node ~/repos/vulkancreative-content/tools/vc-publish.js publish <file-or-dir...> [--dry-run] [--publish] [--overwrite] [--site <name>]
node ~/repos/vulkancreative-content/tools/vc-publish.js schema [type]
```

Config in `~/.config/vc-publish/config.json` (Application Password, 600 permissions, never in a repo). `validate` adds the house-style linter (em dashes, the banned-word list, US spellings, percentage spacing). Multi-file publishes run in dependency order (testimonial, case study, project, post, page) so slug references resolve in one command.

## Authoring

Formats are documented where they are used: each skill carries its `references/format.md`, and the content repo README summarises them. The five skills, all in `~/.claude/skills/`, all invocation-only (run with `/name`; they never fire automatically, so other clients' sessions stay untouched):

| Skill | Job |
|---|---|
| `vulkan-creative-publish` | Mechanics: commands, config, flow, troubleshooting |
| `vulkan-creative-blog` | Articles: interview, outline gate, SEO and humanise passes, pipeline output |
| `vulkan-creative-work` | Portfolio entries: short interview, straight to draft |
| `vulkan-creative-case-study` | Results narratives: fact and story interview, outline gate, paired work entry and testimonial in one flow |
| `vulkan-creative-landing-page` | lp_sections pages: offer interrogation, section arc gate, conversion copy |

House rules the whole stack enforces: metrics and quotes are never invented; terms are never auto-created; work and case-study images are hard gates; drafts until Ibrar says publish.

## Live rollout checklist (PARKED: run deliberately, in order, when the time comes)

Nothing below has been done. The pipeline currently talks to https://vulkancreative.test only.

1. Deploy the current theme to live first: the CPTs, the templates and this endpoint all arrive with it. Live currently runs a pre-July build.
2. Repoint live page 287 (`/your-business/`) to `page-templates/page-landing-page.php` in the live DB: its old template no longer exists in the theme, and WordPress would silently fall back to `page.php`.
3. Flush rewrite rules once after the deploy (Settings, Permalinks, Save) so `/work/`, `/case-studies/` and `/services/` resolve.
4. Enable Application Passwords on live (they are currently not advertised) and confirm the `Authorization` header survives Hostinger's stack; if `/wp-json/` is blocked at the edge, the query-string form `/?rest_route=/vc/v1/publish` is the fallback.
5. Wordfence: check its login-security policy allows application passwords, and allowlist the publishing IP so bulk runs do not trip rate limiting. Large HTML payloads can hit WAF signatures; test with one real post before batches.
6. Caching: publishes need a purge path through LiteSpeed, Hostinger hCDN and Cloudflare. Drafts are unaffected; test a published change end to end.
7. Yoast: confirm `project` and `case_study` (posts and archives) are indexable on live once the real entries are in. Locally all four noindex toggles were already off and the sample content was deleted on 16 Sep 2026 (`feat/real-work-entries`).
8. Review the live landing pages' `[placeholder]`-flagged figures before anything drives traffic at them.
9. Fill the `live` profile in `~/.config/vc-publish/config.json` (URL, username, a fresh Application Password created on live). Publish one throwaway draft, verify, delete, then trust it.
