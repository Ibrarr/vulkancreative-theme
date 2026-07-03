# DESIGN.md

<!-- Written by the impeccable init flow. The authoritative token source is the theme: assets/css/_variables.scss and _mixins.scss, documented in the theme CLAUDE.md "Design Language" section. This file is a summary for design tooling; when they disagree, the SCSS wins. Lives in the theme folder deliberately: all Claude working documents belong inside wp-content/themes/vulkancreative-theme/ (Ibrar's rule). -->

## Theme

Dark-first molten forge. Server renders dark for everyone; light mode is a persisted opt-out. Full-dark #121212 anchor bands (hero bands, why section) punctuate alternating surfaces.

## Colour

- Brand red `$vc-primary` #FF3B30 (accent only), hot orange `$vc-secondary` #FF4500 (hovers, ember hairlines).
- Light surfaces: #FFFFFF (`$vc-surface-white`) and #F5F5F5 (`$vc-background-white`). Dark pairings: #1E1E1E and #121212 respectively; footer #0D0D0D.
- Neutrals: greys 100/200/400/600/700; on dark, surface #262626, hairline rgba(245,245,245,.10), muted #B5B5B5.
- Accents: radial glows rgba(red,.22/.10); ember hairlines rgba(orange,.35 dark / .25 light).

## Typography

- Display: Archivo variable (weight 900, width 125%; 110% for sub-display), via `vc-display-1/2/3` clamp() mixins. Section h2s render uppercase.
- Body/UI: Poppins 400 to 700 via `vc-p` (18/16) and `vc-body` (16/14). Muted text: grey-600/700 on light, #B5B5B5 on dark.
- Red words inside headings are plain `<span>`s coloured `$vc-primary`.

## Components

- Buttons: `vc-button-forge` (solid red, white bold label, 2px corners, glow hover) and `vc-button-ghost` (hairline). Title Case labels.
- Corners: 2px on buttons, cards, plates, arrows; 10px only on large media panels and portraits.
- Badges on imagery: solid plates rgba(13,13,13,.85), white eyebrow type, no borders or ticks.
- Portraits: duotone (grayscale + brand-red soft-light overlay). Images rest at grayscale(0.25) and saturate on interactive hover/focus.
- Text over imagery: light depth scrim + caption-anchored gradient sized to the caption block; verified by alpha-compositing over worst-case white.

## Layout and spacing

Bootstrap 5 grid. Section rhythm `vc-section-padding` (120px desktop, 64px below lg). Spacing 8px scale ($space-1 to $space-15). Z-index scale: 1/10/40/100/1000.

## Motion

GSAP + IntersectionObserver reveals (threshold ~0.1, rootMargin '0px 0px -4% 0px'); durations 0.55 to 0.8s; eases power2.out / power3.out / expo.out (back.out only for physical settles); rises 24 to 36px; staggers 0.07 to 0.14. SplitText line masks on headings with revert on complete. Reduced-motion parity is mandatory: state changes stay functional and instant, nothing hidden; misc/_motion.scss is the CSS safety net.
