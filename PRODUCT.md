# PRODUCT.md

<!-- Written by the impeccable init flow. Sourced from the theme's CLAUDE.md (the verified design-language documentation) and decisions Ibrar has confirmed in review; no answers were invented. Lives in the theme folder deliberately: all Claude working documents belong inside wp-content/themes/vulkancreative-theme/ (Ibrar's rule). Point impeccable's context script here with --target when needed. -->

## Register

Brand. This is the Vulkan Creative marketing site (WordPress, custom theme at `wp-content/themes/vulkancreative-theme/`). Design is the product: the site itself is the agency's proof of craft.

## Users and purpose

Prospective clients (UK business owners and marketing leads) evaluating whether to hire the agency. They arrive sceptical of agency fluff and want evidence: real work, real people, measurable results. The site's job is to earn a contact-form enquiry (Gravity Form 2 via /contact/).

Emotions to evoke: confidence, heat, credibility. Never whimsy, never corporate blandness.

## Brand personality

Molten forge: bold, kinetic, proof-heavy. Three words: forged, honest, measured. Dark-first (dark mode is the default; light is the opt-out). Big expanded Archivo display type, Poppins body, one brand red used as an accent and never as a wash.

## Anti-references (documented house rules)

- No recognisable stock component shapes (plain sliders, plain grids, equal columns); sections lead with custom geometry or motion.
- No rounded pills, no eyebrow tags above headings, no index numerals on rows/cells, no hover states on non-interactive elements, no movement of a container on hover, no content gated behind hover.
- No purple/AI gradients, no emoji as icons, no bounce or elastic easing.
- No small red text on light surfaces (3.25:1); the red tick carries the accent instead.

## Strategic design principles

- Sections alternate fixed surface pairs (#FFF↔#1E1E1E, #F5F5F5↔#121212) with full-dark #121212 anchor bands; ember hairlines mark same-tone dark seams.
- Motion is IntersectionObserver + GSAP with reduced-motion parity and nothing pre-hidden without JS; SplitText line reveals on section headings; exponential ease-outs only.
- Accessibility floor: 4.5:1 contrast (documented standing exception: white-on-red buttons), 44px targets, `:focus-visible` parity for every hover affordance, keyboard-complete interactions.
- Every editable string lives in ACF with defaults mirroring live copy; editors always see real content.
- Copy: UK English, active voice, professional and friendly, no em dashes, percentages like 20%.
