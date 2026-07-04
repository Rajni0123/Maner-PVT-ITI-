---
name: Technical Excellence
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#45464d'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#76777d'
  outline-variant: '#c6c6cd'
  surface-tint: '#565e74'
  primary: '#000000'
  on-primary: '#ffffff'
  primary-container: '#131b2e'
  on-primary-container: '#7c839b'
  inverse-primary: '#bec6e0'
  secondary: '#855300'
  on-secondary: '#ffffff'
  secondary-container: '#fea619'
  on-secondary-container: '#684000'
  tertiary: '#000000'
  on-tertiary: '#ffffff'
  tertiary-container: '#001d31'
  on-tertiary-container: '#188ace'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dae2fd'
  primary-fixed-dim: '#bec6e0'
  on-primary-fixed: '#131b2e'
  on-primary-fixed-variant: '#3f465c'
  secondary-fixed: '#ffddb8'
  secondary-fixed-dim: '#ffb95f'
  on-secondary-fixed: '#2a1700'
  on-secondary-fixed-variant: '#653e00'
  tertiary-fixed: '#cce5ff'
  tertiary-fixed-dim: '#93ccff'
  on-tertiary-fixed: '#001d31'
  on-tertiary-fixed-variant: '#004b73'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
typography:
  display:
    fontFamily: Hanken Grotesk
    fontSize: 48px
    fontWeight: '800'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Hanken Grotesk
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
  headline-lg-mobile:
    fontFamily: Hanken Grotesk
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
  headline-md:
    fontFamily: Hanken Grotesk
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-sm:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 8px
  container-max: 1280px
  gutter: 24px
  margin-mobile: 16px
  section-gap: 80px
---

## Brand & Style

The design system is engineered for an educational institution that bridges the gap between traditional industrial training and modern technical proficiency. The brand personality is **authoritative, industrious, and empowering**, specifically tailored to young vocational students in Bihar.

The visual style is **Corporate Modern with Industrial accents**. It balances the structured reliability of an academic institution with the raw, precision-focused energy of a technical workshop. The aesthetic relies on high-density information layouts, clean structural lines, and purposeful use of whitespace to ensure accessibility and clarity. This system avoids decorative fluff in favor of utilitarian elegance, signaling that the institution is a place of serious work and tangible skill acquisition.

## Colors

This design system utilizes a high-contrast palette to communicate stability and technical vibrancy.

- **Primary (Deep Navy):** Used for headers, footers, and primary navigation to establish an immediate sense of institutional authority and trust.
- **Secondary (Industrial Gold):** Applied to call-to-action buttons, key highlights, and achievement markers. It represents the "spark" of skill and the vibrancy of a technical career.
- **Tertiary (Technical Blue):** Reserved for links, BSCC branding elements, and informational icons to provide a lighter, more modern digital feel.
- **Backgrounds:** Pure white (#FFFFFF) is used for content-heavy areas to maximize readability, while the neutral off-white (#F8FAFC) is used for section differentiation and card backgrounds.

## Typography

The typography system prioritizes legibility across technical documentation and marketing content.

- **Headlines:** Uses **Hanken Grotesk** for a sharp, contemporary, and engineered look. Bold weights and tight letter spacing reflect precision.
- **Body:** **Inter** is used for all long-form content, providing a neutral and highly legible experience that works well even on low-end mobile devices common in the region.
- **Technical Labels:** **JetBrains Mono** is introduced for secondary labels, course codes, and technical specs (e.g., "Workshop Area: 5000 sqft") to lean into the industrial/technical theme.

## Layout & Spacing

The layout follows a **Fixed-Fluid hybrid grid**. On desktop, content is contained within a 1280px central column to maintain readability of technical descriptions.

- **Grid:** A 12-column grid system is used for desktop, collapsing to 4 columns on mobile.
- **Rhythm:** An 8px base unit drives all padding and margin decisions. 
- **Sectioning:** Large vertical gaps (80px+) are used between major sections to allow high-quality industrial imagery to breathe and prevent the UI from feeling cluttered.
- **Mobile:** Margins shrink to 16px, and complex data tables or workshop schedules should transition to a horizontally scrollable format or stacked card view.

## Elevation & Depth

Depth in this design system is handled through **Tonal Layering** and **Low-Contrast Outlines**.

- **Surfaces:** Cards use a subtle 1px border (#E2E8F0) rather than heavy shadows to maintain a "blueprint" or "schematic" feel.
- **Interaction Depth:** Only upon hover do elements lift using a soft, neutral-tinted shadow (e.g., `0px 10px 15px -3px rgba(15, 23, 42, 0.1)`). 
- **Overlays:** Modals and mobile menus use a high-opacity white background with a crisp border to ensure they feel like distinct, physical layers on top of the workshop-heavy background photography.

## Shapes

The shape language is **Soft (0.25rem)**. This slight rounding takes the edge off the "harshness" of industrial imagery while maintaining a professional, structured appearance. 

- **Standard Elements:** Buttons and input fields use a 4px (0.25rem) radius.
- **Featured Cards:** Larger containers use 8px (0.5rem) to feel more modern and welcoming.
- **Icons:** Should be housed in square containers or very slightly rounded frames to echo the look of industrial control panels.

## Components

### Buttons
- **Primary:** Deep Navy background with White text. Sharp, clear, and assertive.
- **Secondary (Action):** Industrial Gold background with Navy text. Used for "Apply Now" or "Admission Open."
- **Ghost:** Transparent background with a Navy border for less critical actions like "Download Syllabus."

### Cards
- **Course Cards:** Feature a top-aligned image of the workshop, followed by a Hanken Grotesk heading and a JetBrains Mono "Course Duration" label.
- **BSCC Information Card:** Specifically styled with a Technical Blue border and the official Bihar Government logo. It should highlight "Zero-Interest" or "State Supported" as high-visibility badges.

### Input Fields
- Understated styling with a light gray border. Focus state uses a 2px Deep Navy border. Labels are always visible above the field in a bold Inter font.

### Icons
- Use "Duotone" or "Outline" styles representing technical tools (wrenches, circuits, gears) and academic milestones (caps, certificates). Icons should be monochromatic Navy or Gold.

### BSCC Dedicated Section
- A distinct full-width banner or card group using the Technical Blue palette. This section must feel "Official" yet "Accessible," acting as a bridge between the institution and government support services.