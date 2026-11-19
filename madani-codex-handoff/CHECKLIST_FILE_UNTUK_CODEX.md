# Checklist file yang harus disiapkan untuk Codex - Madani Montessori

Checklist ini ditujukan untuk pemilik proyek. Upload file sebagai attachment langsung ke Codex bila memungkinkan; jangan hanya menyebut nama atau mengandalkan percakapan ChatGPT ini.

## Wajib untuk desain

- [ ] **Gambar referensi mockup:** `Madani Montessori Editorial Homepage(1).png` (sudah termasuk di folder ZIP ini sebagai `references/madani-homepage-reference.png`). Ini versi yang disebut "better", BELUM final.
- [ ] **PDF undangan asli 4 halaman:** `ANNUAL CELEBRATION INVITATION TKIT MADANI MONTESSORI.pdf`. Tautan Drive: https://drive.google.com/file/d/1lUJch4twlcM6G-iM25kpq6UJT-ghD1O4/view . Attach PDF langsung ke Codex supaya detail botani, border, palet dan font dapat dilihat.
- [ ] **Logo resmi sekolah:** idealnya SVG/AI/EPS atau PNG transparan resolusi tinggi. Jangan pakai logo fiktif dari hasil generate mockup.
- [ ] **Foto asli untuk HERO:** dokumentasi kegiatan anak bermain/belajar Montessori dengan resolusi lebar, sudah mendapatkan izin publikasi orang tua/wali sesuai kebijakan sekolah. Foto tetap akan diredam oleh overlay krem.
- [ ] **Aset Montessori cutout:** PNG/WebP transparan (pink tower, knobbed cylinders, bead material, stacking rings, atau alat peraga asli), atau foto mentah berizin yang bisa dihapus background-nya. Kirim beberapa aset dengan sudut kamera dan pencahayaan yang cocok supaya bisa digabung sebagai satu kelompok objek.

## Wajib untuk isi dan struktur website

- [ ] **Company profile resmi:** nama sekolah yang tepat, sejarah, filosofi, pendekatan Montessori & nilai Islami, visi/misi (jangan dikarang), foto fasilitas dan staf bila perlu.
- [ ] **Daftar program resmi:** nama program, rentang usia, deskripsi, foto/alat peraga yang relevan, tautan atau dokumen detail.
- [ ] **PPDB:** syarat, alur, jadwal, ketentuan biaya bila sekolah mengizinkan publikasi; tentukan apakah ingin pendaftaran online, cek status, kontak WhatsApp atau integrasi backend.
- [ ] **Kegiatan/agenda/berita:** konten nyata, gambar berizin, tanggal, status acara bila ingin dimunculkan. Jangan minta Codex menciptakan event.
- [ ] **Kontak dan identitas:** alamat lengkap, titik Google Maps, nomor WhatsApp resmi, telepon/email, jam layanan, link IG/sosial, domain resmi.
- [ ] **Kebutuhan galeri:** foto sekolah yang layak dipublikasikan (izin penggunaan gambar anak harus jelas), kategori dan caption kalau ada.
- [ ] **Peraturan dan privasi:** privacy notice dan aturan terkait data anak/orang tua jika mengumpulkan data lewat PPDB/form.

## Wajib untuk pengerjaan GitHub / PR

- [ ] **Tentukan repo tujuan baru** untuk proyek dari nol. Contoh lama untuk audit: https://github.com/Mubax5/madani-montes-website dan https://github.com/Mubax5/madani-montessori-landing-page . Jangan berasumsi mana yang live.
- [ ] **Tentukan apa saja backend yang dipertahankan:** Laravel/Filament CMS, agenda, PPDB daftar/cek-status, form, data, dan integrasi lainnya (setelah Codex audit).
- [ ] **Akses yang cukup bagi Codex** ke repo tujuan untuk membuat branch dan PR (bila user mengizinkan). Jangan berikan personal access token atau password dalam chat; pakai metode koneksi aman bawaan platform.
- [ ] **Aturan hosting/deploy:** informasi provider, domain `madanimontessori.my.id`, environment dan batasan (tanpa membocorkan `.env`, key, atau kredensial). Deployment hanya setelah approval, tidak otomatis.

## Susunan folder attachment yang disarankan

```text
madani-assets/
  reference/
    Madani Montessori Editorial Homepage(1).png
    ANNUAL CELEBRATION INVITATION TKIT MADANI MONTESSORI.pdf
  brand/
    logo.svg
    brand-guide.pdf (jika ada)
  photography/
    hero-classroom.jpg
    gallery-*.jpg
  montessori-objects/
    pink-tower.png
    knobbed-cylinders.png
    bead-material.png
  content/
    company-profile.pdf
    programs.md
    ppdb-details.md
    contact-details.md
```

Contoh nama file di atas adalah contoh struktur, BUKAN klaim bahwa file sudah ada. Bila suatu bahan belum siap, tulis `BELUM ADA` dan minta Codex menggunakan placeholder yang jujur dalam mockup, bukan menciptakan detail sekolah.

## Urutan menyerahkan ke Codex

1. Upload PNG mockup dan PDF undangan terlebih dahulu, lalu paste **seluruh** isi `CODEX_MASTER_PROMPT.md`.
2. Upload logo, foto hero asli, alat peraga cutout, dan konten resmi; minta Codex membuat daftar file diterima, gap, dan mockup lengkap dahulu.
3. Review dan setujui desain **beranda full** + halaman dalam + mobile. Koreksi jika gambar terlalu dominan, objek berjauhan, atau spasi terlalu rapat.
4. Setelah APPROVED, baru instruksikan implementasi di branch, dokumentasi `docs/design.md`, QA dan PR. Jangan merge atau deploy otomatis.
