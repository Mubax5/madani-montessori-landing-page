# MASTER PROMPT FOR CODEX - MADANI MONTESSORI WEBSITE

> Copy this whole prompt into Codex and attach the reference assets listed at the end. This is a complete project brief, not a request for a one-screen landing page. The attached homepage screenshot is a visual DIRECTION, not an approved pixel-perfect final design. The user's spoken corrections take precedence over the screenshot.

## 0. YOUR ROLE AND MISSION

Act as a senior product designer, art director, design systems engineer, frontend architect, and full-stack developer. Design and build an original, cohesive, production-quality MULTI-PAGE school website for **Madani Montessori / TKIT Madani Montessori**, a school located in the Cisauk area of Indonesia (verify the school's official name and full address from supplied documents). The site must give prospective families clear access to information about the school, its educational approach, programs, school life, admissions (PPDB), and contacts. The goal is NOT a simple one-page marketing landing page.

The new implementation starts from scratch as a new visual system and clean codebase. Legacy repositories are for CONTENT, DOMAIN LOGIC, INTEGRATION, and ROUTE AUDITING only, not a visual base to restyle. Do not destroy, reset, overwrite, deploy over, or modify the legacy production site without explicit written instruction.

**The design must feel custom and human art-directed, not AI-slop.** Fidelity to the attached visual and the owner's invitation document matters more than fashionable design templates.

## 1. DESIGN REFERENCE PRIORITY

Use sources in this order when they conflict:
1. Explicit user decisions and latest feedback recorded in THIS prompt, particularly the corrections in Section 3.
2. Attached image: `references/madani-homepage-reference.png` (user's 1536 x 1024 desktop homepage mockup, direction accepted as "better", NOT final).
3. Attached original PDF: `ANNUAL CELEBRATION INVITATION TKIT MADANI MONTESSORI.pdf` (source of brand-like color, watercolor botanical ornament, fine gilt rules, cream paper look, restrained Islamic Montessori warmth).
4. Official school content, approved school photographs, official logo, and other approved assets that the user supplies.
5. Legacy app content and feature contracts discovered by inspecting the old repositories.
6. Outside inspiration only if it is genuinely appropriate, and never to replace the user's supplied references.

Do not guess what an absent file looks like. Ask for it or clearly label the missing source. Do not use the generated screenshot's fictitious children or placeholder school copy as verified real-school content.

## 2. FULL HISTORY OF USER FEEDBACK (HIGH PRIORITY)

The user rejected multiple earlier concepts for feeling generic or dramatically different from the school's invitation. Lessons learned:
- The user does NOT want a single-page landing page or a generic company profile. Build a real multi-page website with top-level navigation, meaningful submenus, individual content pages and links from homepage teasers.
- The homepage is an attractive editorial OVERVIEW of the school, with links to deeper pages; it does not duplicate every inner page in full.
- Do not write fabricated motivational slogans like "Tempat Anak Tumbuh, Belajar, dan Berharmoni". The main home hero headline must simply be **"Madani Montessori"**, typically split across two elegant lines.
- No automatic hero eyebrow, generic subtitles, chips, decorative pills, badges, empty metrics, repeated icons, or other filler under the hero heading.
- Reject the classic "text left, boxed photo right" SaaS/landing-page formula.
- The owner wants warm pale ivory/cream, with navy text, thin antiqued gold/beige rules, and muted sage botanical details derived from the REAL invitation PDF. Islamic influence is refined and contextual, not ornate mosque-arch wallpaper or unverified Arabic calligraphy.
- The hero SHOULD have children learning/playing Montessori in the BACKGROUND, but the photography must be covered with a STRONG cream/near-white overlay so it remains recognizable yet does NOT compete with the dark navy Madani Montessori headline. The latest reference screenshot demonstrates this better than earlier versions. Use a genuine school photograph with rights/parental consent for production, not generated faces.
- Navbar in earlier mockups was too dominant. Keep a slender, quiet, well-spaced header with the school wordmark and clear multi-page links. No giant logo/header box, high-contrast bar, massive active underlines or oversized CTA.
- Hero-to-next-section divider is important. Outer left and right edges sit slightly HIGHER, the long central section sits LOWER, and the two transitional shoulders are only gently rounded. It should read like a shallow recessed/stepped contour with rounded corners. NOT a huge wave, semicircle, U-shape or pointed mosque dome. Fine golden hairline tracing is welcome; copy the uploaded screenshot's DIVIDER silhouette, not its ornamental arch above the title as a replacement divider.
- The user disliked large photographs in EVERY content section. Outside the hero, favor spacious typography and small clusters of isolated/transparent Montessori manipulatives (PNG/WebP/SVG assets) integrated into the page, not big stock-photo rectangles or repetitive photo cards. The Galeri page is an intentional exception and can use authentic approved photography.
- Latest specific correction: the Montessori teaching aids in the latest image feel too far apart, "like they are fighting". Cluster them naturally so they visually belong together (overlap lightly, coherent shared baseline, scale, soft contact shadows, balanced relation to text). Never float unrelated objects at random.
- Latest specific correction: vertical spacing between sections still feels too tight. Add more intentional negative space and consistent large vertical rhythm; do not jam next section immediately against hero.
- Strong preference against AI-slop: no random decorative stickers, excessive blurred gradients, glassmorphism, generic bento/card grids, repetitious layouts, made-up data, meaningless decorative labels, AI-generated stock children, big rounded boxes, or generic marketing language.

Most recent reference image was described as "better", but has NOT been approved as final. Incorporate the last TWO corrections (grouped teaching aids, more section spacing) before asking for final design approval.

## 3. VISUAL EXTRACTION FROM THE ATTACHED SCREENSHOT

### Layout and composition
- Source screenshot: **1536 x 1024 px**. It shows a compact navigation at the top, a pale photographic hero, centered two-line oversized serif wordmark, delicate botanical/gold ornaments, shallow rounded recessed divider, and the start of "Mengenal Madani Montessori".
- Header: minimal unboxed wordmark on the left and text navigation to the right, visually quiet on the pale background; about 64-80px tall on desktop depending on actual font metrics.
- Main hero: roughly 500-570px high on this screenshot. Calm large negative space and single central focal point. The photo is visible at left and right behind a pale cream veil. Text sits centered with large refined dark navy high-contrast serif letters.
- Fine warm gold accent linework; botanical watercolor sage corner ornaments. Use the invitation's linework selectively, not as heavy picture frames surrounding every text block.
- Hero boundary: a narrow gold double/hairline approximately 35-50px shallow drop toward a mostly straight long central span, softened S-curve shoulders near left/right. Responsive and controlled, not decorative wave art.
- Second section: ivory background continuous with hero, an editorial "Mengenal Madani Montessori" heading at left, restrained short body text, small coherent grouped Montessori manipulatives at right. **Increase top/bottom padding compared to source** and group the objects more tightly.

### Approximate color system (sampled from PNG, NOT official brand codes)
- Background ivory: `#FBF3E9` (sample near image top-left); adjacent paper tints `#FCF4EB`, `#FDF5EC`.
- Primary navy ink: `#162D49` to `#183053` (dark editorial text; verify visual contrast).
- Thin gold linework: muted warm gold around `#C9A064` to `#D1AF79`; image shows variable antique gold strokes, not metallic gradients.
- Muted sage: around `#92977C` (botanical illustrated accents only; calibrate from PDF).
- Light wooden natural material: beige/honey wood colors; blush/pale rose only on actual Montessori objects, not as a UI accent everywhere.
- These are STARTING tokens. Extract final tokens from assets, verify in a local screenshot comparison, and record selected values in `docs/design.md`.

### Typography
- Main headline: elegant HIGH-CONTRAST EDITORIAL SERIF with relatively fine hairlines and strong large letter shapes, centered as "Madani" on line one and "Montessori" on line two. Think of a carefully tested classic serif like Cormorant Garamond, Bodoni Moda, or another locally available/licensed equivalent; do not claim a specific font without examining originals. No more than two main font families.
- Heading color: dark navy. Desktop hero type approximately 92-124px depending on exact font and line-height; responsive via clamp. Do not allow mobile crop or awkward word breaks.
- Secondary heading: elegant navy serif, significant but subordinate. Body text: editorial, clear, accessible, not dense or excessively ornamental.
- Keep headings/text genuinely selectable HTML rather than baking typography into images.

### Photo layering
- Use an authentic approved photo showing several pupils learning with age-appropriate Montessori objects and natural atmosphere, with consent.
- Full-bleed hero image in `object-fit: cover` behind the foreground, not a side-by-side rectangle.
- Layer an opaque but slightly translucent ivory overlay, approximately `rgba(251,243,233,0.78)` to `0.88` as starting tests. Adjust for individual photo exposure and final visual comparison: faces/activities faintly visible, typographic title instantly dominant. Keep contrast WCAG-compliant.
- Avoid filters that make the image ugly, artificial, plastic, or overexposed; should feel like a quiet matte print in the paper background.

### Montessori cutout cluster
- Prefer REAL Montessori teaching materials such as pink tower, knobbed cylinders, spindle blocks, wooden bead cube and stacking material, or other authentic verified Montessori apparatus.
- Source transparent-background cutouts from user-approved photos or provided PNG/WebP. Remove white background cleanly only when authorized; don't invent nonsensical apparatus.
- Compose as a SINGLE HARMONIOUS STILL LIFE, with coherent perspective, apparent common resting surface, matching scale/light direction, modest overlap, soft realistic contact shadow. Never distribute them as unrelated floating items.
- Do not place a large image rectangle next to the text; the cutouts should integrate with the ivory page.

## 4. WEBSITE INFORMATION ARCHITECTURE: MULTI-PAGE IS NON-NEGOTIABLE

Propose a proper route tree, validate it against school source data, and implement true individual routes, rather than a long anchor-scrolling landing page. Suggested base architecture:

- `/` - Beranda. A curated overview of school identity, its approach, selected programs, current school activities, selected authentic gallery, PPDB entry point, and contact. Each teaser links to its own route.
- `/profil` - Profil Sekolah. School story, introduction, official identity, history and philosophy, and links to focused subpages.
- `/profil/visi-misi` - Visi & Misi (ONLY official wording). Optional `/profil/tim-pendidik` and `/profil/fasilitas` if approved content exists.
- `/program` - Program overview; `/program/program-sekolah` and `/program/program-unggulan` for genuine actual programs. `/bimbel` only if this is an official active offering.
- `/metode-montessori` - Clear informational page on the school's actual Montessori approach if content is confirmed; don't misrepresent accreditation.
- `/kegiatan` or `/agenda` - Upcoming/past activities if source data supports them, with detail routes (e.g. `/agenda/[slug]`). Add `/berita` and `/berita/[slug]` if the school has real articles; no invented news.
- `/galeri` - School gallery, with categories and actual licensed/consented photographs.
- `/ppdb` - Admissions overview and verified instructions. `/ppdb/daftar` and `/ppdb/cek-status` ONLY if the underlying process and secure integrations are real and fully implemented; otherwise route to official contact/intake and clearly indicate scope.
- `/kontak` - Real location, hours, official telephone/WhatsApp/email, map, contact methods.
- `/kebijakan-privasi` and other policy pages as appropriate to actual data collection.

Top desktop navigation from latest reference is quiet: `Beranda`, `Profil` [dropdown], `Program` [dropdown], `Kegiatan` [dropdown if needed], `Galeri`, `PPDB`, `Kontak`. Responsive mobile uses an accessible disclosure menu, not squashed text. Use accessible active states, real URLs, keyboard support, proper focus order, aria-expanded and tap targets.

Use one consistent visual language across inner pages BUT do not mindlessly duplicate identical hero layouts, fake photography, or generic cards. Every page needs a purposeful information hierarchy and content design, with editorial spacing, dividers, and selective cutout Montessori motifs. Keep real photography intentional: hero (subtle) and dedicated gallery / relevant real-story pages, not a mandatory huge photo in each section.

### Minimum page-specific design work
Before implementation, design complete, scrolling desktop mockups for:
1. Full homepage from navigation to footer (NOT just first fold).
2. Profile overview + one representative profile inner page.
3. Program overview + representative program detail.
4. Kegiatan/agenda list + example detail.
5. Gallery overview.
6. PPDB informational page; form/status state only if real.
7. Kontak page.
Also provide representative mobile layouts (at least homepage, navigation, program and PPDB, and a mobile pattern for remaining pages). If official content is missing, use visibly labeled structured placeholders and ask for authoritative text; DO NOT fabricate school claims, dates, testimonials, prices, teachers, school facilities, addresses, or enrollment status.

## 5. HOME PAGE SECTIONS AND RHYTHM

Use page structure as a content plan, not a requirement for every section to be the same visual module:
- Understated top header and navigation.
- Hero with nearly-cream photographic background and centered "Madani Montessori", delicately restrained botanical/gilt elements, shallow rounded stepped divider.
- "Mengenal Madani Montessori" section with authentic concise text and a unified cluster of Montessori materials; roomy spacing. Link to `/profil`.
- Educational identity / Montessori + Islamic principles as a readable typographic editorial section using verified school descriptions (NO four generic equal feature cards unless justified by actual content).
- Program teaser: two or three actual programs as editorial blocks/list with meaningful links, not invented offerings.
- Kegiatan / latest agenda preview: real dates/content or a gracefully designed empty state and link to the full page.
- Select school life/galeri: minimal selected approved photos if appropriate; don't make photo cards the default for everything.
- PPDB: restrained but clear link to admissions information, without a fabricated offer or deadline.
- Location/contact preview and footer with real verified school details.

Spacing: target a calm, premium editorial rhythm. Suggested desktop vertical section padding ~120-160px for major sections, smaller for related sub-blocks; tablet ~88-120px; mobile ~72-96px. Fine-tune by screenshot, typography, and content. Avoid excessive empty voids but do not compress sections or put them visually on top of the divider. Consistent content max-width ~1160-1280px and readable long-text measure ~55-70ch. Follow natural responsive grids without changing the intentional composition.

## 6. TECHNICAL AND LEGACY CONTEXT

The following repositories were discussed earlier; VERIFY their current contents before choosing the target:
- `https://github.com/Mubax5/madani-montes-website` - previously inspected as a React/TypeScript frontend using Vite, with route code covering Home, Tentang, Program Sekolah/Unggulan, Bimbel, Training Parenting, Galeri, and Kontak.
- `https://github.com/Mubax5/madani-montessori-landing-page` - previously inspected as Laravel with Tailwind and Filament-related code; routes included `/ppdb`, `/ppdb/daftar`, `/ppdb/cek-status`, `/agenda`, agenda registration, program pages, gallery, and contacts.
- Existing domain mentioned: `madanimontessori.my.id`. The active deploy/repo relationship has NOT been verified. Confirm before any deployment or migration.

**Do not assume these projects have the same function or that one is currently live.** Audit the existing routes, dependencies, database, forms, APIs, CMS, content, media assets and deployment configuration. Preserve any real school workflows and data. The user explicitly wants a from-scratch visual rebuild, and previously suggested a NEW SEPARATE REPOSITORY to keep the legacy system intact. Ask which GitHub repo/organization will host the new project. If a new repo is authorized, establish a clean main branch and work on a feature branch before opening a PR. If a legacy repo is explicitly chosen as the base, create a separate branch and do not delete or overwrite source/features without permission. Never push to or merge directly into main or deploy to production automatically.

Suggest a maintainable, SEO-friendly architecture fitting requirements and real hosting constraints. Do not blindly choose a framework or rewrite the backend before checking production requirements. Static pages and dynamic PPDB/agenda/Filament CMS may need a deliberate integration approach. Use semantic HTML, accessible CSS, responsive images, modular components, and type safety where applicable. Text, borders, SVG ornaments, and cutout objects should be distinct web layers, not flattened into a screenshot.

## 7. EXPECTED DELIVERY PROCESS AND APPROVAL GATES

**Phase A - intake / audit**
1. List all assets and existing repos received; explicitly identify missing critical sources.
2. Inspect actual PNG screenshot and PDF pages visually, inspect legacy code as needed, and record findings with confidence/uncertainty.
3. Produce a concise site map, routes, content inventory, design rules, and implementation plan. Don't pretend missing school data is confirmed.

**Phase B - design approval**
4. Create `docs/design.md` with design intent, tokens, typography choices, component rules, shallow divider geometry, hero image overlay behavior, Montessori object still-life rules, responsive breakpoints, spacing scale, accessibility notes, and explicit anti-slop exclusions.
5. Create `docs/information-architecture.md`, `docs/content-inventory.md`, and `docs/assets-manifest.md` with owner-source gaps.
6. Produce *full-length* homepage visual mockup plus inner-page layouts and representative responsive/mobile views. Show actual page progression to footer, not just a 1024px top crop.
7. Reconcile visual against the reference PNG and invitation PDF, AND incorporate latest feedback (grouped objects + larger gaps). Request explicit user sign-off before coding the final design system. Provide a design-diff summary.

**Phase C - implement after approval**
8. Create reusable but varied page components, typography tokens, ornament assets, hero, responsive nav/dropdowns, footer, content routes, data interfaces, necessary real APIs/CMS connections. Do not create fake working actions or mocked PPDB submissions presented as real.
9. Implement full pages and states; build mobile-first behavior and correct URL routing. Add semantic alt text and captions for actual gallery images. Ensure forms protect children's and parents' personal data, server validation, anti-spam/rate limits, no secret exposure, sensible privacy handling.
10. Use image transforms, `srcset`/responsive image behavior, lazy-loading below fold, preloading only meaningful hero resources, and optimized SVG/PNG/WebP. Test reduced motion preference. Avoid excessive animations; restrained, accessible micro-interactions only.

**Phase D - verify + PR**
11. Run build, lint, typechecks, appropriate tests, navigation checks, no dead links, responsive and keyboard checks, check 360/390px mobile, tablet, 1280/1440/1920 desktop, test empty states, 404, menu behavior, PPDB form behavior as implemented.
12. Capture real browser screenshots and compare manually/visually with supplied reference. Iterate until hierarchy, overlay, colors, rhythm and object grouping actually match. Check WCAG 2.2 AA as target; aim for good Core Web Vitals, prevent layout shift.
13. Create an informative Pull Request in the USER-CONFIRMED target repo only: summary, linked screenshots, pages created, architecture/legacy tradeoffs, asset provenance, tests and accessibility/performance checks, remaining known gaps, migration considerations. DO NOT merge, deploy or modify production without approval. If GitHub account permissions, target repo, or branch setup are unavailable, provide commits/diff/PR-ready instructions and clearly report why a PR was not created.

## 8. REQUIRED OUTPUT FILES IN THE NEW REPOSITORY

Minimum:
- `README.md`: setup, scripts, development run, known assumptions.
- `docs/design.md`: definitive living design specification, reflecting user-approved final choices rather than generic descriptions.
- `docs/information-architecture.md`: menu tree and all routes.
- `docs/content-inventory.md`: actual content source vs TODOs.
- `docs/assets-manifest.md`: each asset, source, usage, owner permission, placeholder/final state.
- `docs/qa-checklist.md`: visual, responsive, behavior, accessibility and performance verification.
- `.env.example` if environment variables are needed; NEVER include secrets.
- Source code, real reusable components, optimized assets, test coverage as appropriate to selected stack.

Do not create meaningless files purely to look complete. Prefer accurate documentation and fully working functionality.

## 9. ACCEPTANCE CRITERIA (DO NOT SELF-APPROVE)

User should be able to say:
- "Ini website sekolah lengkap dengan banyak page, bukan cuma landing page panjang."
- "Judul Madani Montessori jelas paling dominan; foto anak terlihat samar lewat overlay krem."
- "Navbar ringan dan nggak mendominasi."
- "Pemisah hero ke section berikutnya agak turun di tengah, kiri-kanannya lebih tinggi, ujungnya rounded halus, bukan gelombang besar."
- "Material Montessori jadi satu komposisi yang menyatu, bukan benda terpencar atau foto kotak di tiap section."
- "Setiap section punya ruang napas; tipografi, ornamen, dan warna nyambung ke undangan PDF."
- "Tidak ada konten sekolah fiktif, pola AI-slop, atau CTA/form yang pura-pura bekerja."
- "Submenu benar-benar membuka halaman tersendiri, semua navigasi dan fitur penting dites."
- "Legacy site/repo aman, design.md ada, dan PR siap direview tanpa merge paksa."

If any of these fail, revisit design before claiming completion. When unsure, ask ONE focused question with options and a recommended default rather than guessing an official fact.

## 10. ASSETS THE USER WILL PROVIDE / YOU MUST REQUEST

Before implementation, explicitly remind the user to upload:
1. **THIS PNG** `Madani Montessori Editorial Homepage(1).png` (or packaged `references/madani-homepage-reference.png`) - mandatory primary composition screenshot.
2. **ORIGINAL 4-PAGE PDF INVITATION** `ANNUAL CELEBRATION INVITATION TKIT MADANI MONTESSORI.pdf` - important main design-language source. Google Drive link can help find it, but upload the actual PDF to Codex when possible: `https://drive.google.com/file/d/1lUJch4twlcM6G-iM25kpq6UJT-ghD1O4/view`.
3. Official school logo/wordmark (prefer vector SVG, or transparent high-resolution PNG), and actual brand/fonts if licensed/known.
4. **REAL, APPROVED SCHOOL PHOTOGRAPHY**, especially one wide hero classroom scene with children learning (permissions/parental consent), and real gallery/event photos. Never use generated faces as documentary evidence.
5. Transparent PNG/WebP of REAL Montessori teaching objects (or user-approved original material photographs for background removal); multiple objects intended for one coherent grouped still-life.
6. Official school copy: full school name, history, mission/vision, philosophy, programs, admissions requirements/schedule, staff/facilities only if approved, official contacts, address, maps/social links, FAQs, and approved testimonials (if any).
7. Confirmed GitHub destination for NEW project, which legacy repo is live (if known), domain/hosting/tech constraints, and whether existing Laravel/Filament CMS, agenda, PPDB and registrations must be retained or integrated.
8. If online PPDB is real: form fields, status workflow, storage/retention/privacy, admins, security obligations, API documentation, database schema or sanitized examples. Never paste private student records, passwords, access tokens, .env credentials or banking/identification data into prompts.

**FIRST CODEX RESPONSE SHOULD:** confirm received files, separate facts from assumptions, flag missing assets (especially photo, logo, PDF and new repo target), give a clear two-stage plan (design approval THEN implementation/PR), and start only the design/intake phase until approved. Do not claim the PDF, the official logo, or actual kids' photos are present until you can inspect the uploaded bytes.
