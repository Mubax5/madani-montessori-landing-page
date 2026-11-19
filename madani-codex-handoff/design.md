# Madani Montessori - Design Direction (Draft for Codex)

Status: **DRAFT / AWAITING USER APPROVAL**. Source image `references/madani-homepage-reference.png` was viewed positively by the user as "better", but additional revisions were requested. This file is a handoff specification, not a final official brand manual.

## Visual character

Custom, warm, editorial Islamic Montessori school identity. Ivory/off-white paper, formal high-contrast navy typography, muted antique-gold linework, restrained sage botanical watercolor ornaments, authentic Montessori wooden apparatus. Read like a deliberately crafted educational institution website, not a wedding invitation page or generic SaaS landing template.

### Source visual

- Screenshot provided: 1536 x 1024 PNG (homepage top only).
- Original source of ornamental identity: four-page Annual Celebration Invitation TKIT Madani Montessori PDF (must be attached separately to Codex).
- Screenshot defines *direction*, not exact final implementation. Later spoken feedback supersedes any visual feature of screenshot.

### Design tokens (first pass, sampled/estimated, verify)

| Token | Initial value | Purpose |
|---|---|---|
| `--paper` | `#FBF3E9` | Primary warm ivory background |
| `--paper-alt` | `#FCF4EB` | Slightly varied paper surface |
| `--paper-light` | `#FDF5EC` | Low-contrast section variations |
| `--ink` | `#162D49` | Navy editorial text |
| `--ink-strong` | `#183053` | Alternate observed dark navy |
| `--gold` | `#C9A064` | Hairline ornaments, subtle dividers |
| `--gold-light` | `#D1AF79` | Secondary hairline tone |
| `--sage` | `#92977C` | Restrained watercolor leaves |
| `--hero-veil` | `rgba(251,243,233,0.82)` | Cream photographic overlay starting point |

No unapproved high-saturation colors, no glowing gradients, no glassmorphism. Maintain contrast for functional text and controls.

## Header / navigation

At approximately 1536px view, logo/wordmark top left and text navigation on the right. Height approx 64-80px. Menu items `Beranda`, `Profil`, `Program`, `Kegiatan`, `Galeri`, `PPDB`, `Kontak`; submenu disclosure only where pages exist. Quiet thin active marker and focus indicator; correct touch/keyboard behavior. Avoid large logo lockups and intrusive CTA pill. Mobile should show accessible compact menu with nested disclosures.

## Homepage hero

- Full-bleed genuine approved learning/classroom image under heavy cream veil. Keep students visually perceptible but distinctly less prominent than title. The reference already demonstrates high fading: near-white/cream with background left/right children only faintly visible.
- Large centered two-line headline **Madani / Montessori**, deep navy classic editorial serif, intentional spacing. No eyebrow/slogan/badges. Visible live text, never baked into image.
- Delicate botanical leaves and thin antique gold ornamental flourish only to the extent supported by original invitation. No gratuitous giant mosque arch.
- In 1536x1024 screenshot, hero/delimiter occupies approximately first 640px; adapt final hero to content and device.

## Custom separator geometry

Design a shallow recessed transition: left/right edges at a higher horizontal baseline, then short gently rounded transitions descending ~35-50px into a long lower central horizontal baseline. Thin warm golden strokes. This should look almost like a delicately drafted inset border, **NOT a huge wave, ellipse, semicircle or dome**. SVG path or responsive pseudo elements are reasonable; test 360px to 1920px.

## Body composition

- Intro heading `Mengenal Madani Montessori`, readable factual copy only; website overview links to separate profile page.
- A **single carefully styled group** of isolated Montessori learning manipulatives beside intro (pink tower, natural knobbed cylinders, wooden bead cube, etc.). Bring them closer together than in screenshot. Align scale, common baseline, direction of light and contact shadows. No isolated objects that appear to be placed independently across a large empty region.
- No repeated boxed classroom photos for normal sections. Gallery may use licensed photography; other pages can use select cutouts and typography.
- Deliberate larger vertical section margins than screenshot; desktop major section padding 120-160px, tablet 88-120px, mobile 72-96px as starting values. Adjust visually for content. Avoid overly tight transition directly under divider.
- Content width ~1160-1280px, readable copy width ~55-70ch; editorial grid, strong alignment; minimal borders/cards.

## Typography

Test and compare a legitimate/licensed high-contrast editorial serif similar in character to the screenshot for display. Possible starting candidates: Cormorant Garamond / Bodoni Moda / similar, chosen only after visual QA. Pair with one unobtrusive accessible body/UI family when necessary, maximum two major families. Main desktop hero approx 92-124px with thoughtful line heights. On mobile, use responsive clamp and preserve both words with correct wrapping. Native Indonesian spelling. Do not invent copy for official school claims.

## Information architecture

Build REAL pages and routes for Beranda, Profil + subpages, Program + subpages, Kegiatan/Agenda + details, Galeri, PPDB (possible register/status if real), Kontak, and legally required policies. Home teasers link into detail pages. Multi-page navigation is a hard requirement. Route paths and available features should be confirmed against existing legacy code.

## Interaction and responsive behavior

- Real dropdown navigation including click, keyboard, mobile disclosure, active state.
- Buttons navigate or perform real approved actions. Never present fake submissions as success.
- All page types have 404/empty/data-loading states when applicable.
- `prefers-reduced-motion` respected; restrained micro-interactions only.
- Semantic headings and landmarks, touch targets, alt text, contrast, focus visibility, privacy and consent for identifiable minors.
- Capture desktop full-page and mobile screenshots for comparison at each iteration.

## Anti-patterns: explicitly prohibited

Generic "Tempat Anak Tumbuh" slogans; oversized hero eyebrow; meaningless chips; arbitrary metrics; decorative card grids; image boxes beside every title; large photos in each section; floating random Montessori objects; heavy gradients/glass; fake Arabic calligraphy; unverified religious quotations; fictitious staff, dates or events; AI-generated people presented as real school photos; inaccessible image text; direct rewrite of legacy repo/prod without authorization.

## Design approval checklist

- [ ] Hero headline is unequivocally primary; faded child photo is contextual, not dominant.
- [ ] Navbar quiet and functional, with true multi-page routes.
- [ ] Gold/gentle inset divider matches shallow rounded shoulder request.
- [ ] Grouped Montessori props form one still life; not disparate decorations.
- [ ] Sections have more breathing room than screenshot.
- [ ] Full homepage mockup to footer exists, plus major inner pages and mobile patterns.
- [ ] Visual language is recognizably connected to the actual invitation PDF.
- [ ] All factual content verified; asset permissions confirmed.
- [ ] User explicitly approves mockups before final production implementation.
