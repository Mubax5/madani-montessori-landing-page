# Madani Montessori Islamic School Website

Website resmi **Madani Montessori Islamic School** untuk TK Islam Terpadu berbasis Montessori yang menyediakan program sekolah, bimbel, serta agenda kegiatan sekolah.

Project ini dirancang sebagai website multipage dengan tampilan elegan, minimalis, clean, dan eksklusif. Website juga terhubung dengan **Admin CMS** sehingga konten seperti teks, gambar, program, galeri, kontak, dan CTA WhatsApp dapat dikelola tanpa mengubah kode secara langsung.

---

## Tentang Project

Website ini dibuat untuk membantu Madani Montessori Islamic School menampilkan informasi sekolah secara profesional dan mudah diakses oleh orang tua calon siswa.

Fokus utama website:

- Menampilkan profil sekolah secara elegan
- Menjelaskan program KB, TK A, TK B, dan TK C
- Menampilkan program unggulan berbasis Montessori dan Islam Terpadu
- Menyediakan informasi bimbel
- Menampilkan Agenda seperti trial class, study tour, open house, parenting class, workshop, field trip, dan event sekolah
- Menampilkan galeri kegiatan sekolah
- Menyediakan form kontak dan pendaftaran
- Memudahkan calon orang tua menghubungi admin melalui WhatsApp
- Memudahkan admin sekolah mengelola konten website melalui CMS

---

## Fitur Utama

### Website Publik

- Home page
- Tentang Kami
- Program Sekolah
- Program Unggulan
- Bimbel
- Agenda
- Galeri
- Kontak & Pendaftaran
- CTA WhatsApp
- Maps / lokasi sekolah
- Form minat pendaftaran
- Responsive mobile, tablet, dan desktop

### Admin CMS

Admin CMS digunakan untuk mengelola konten website secara dinamis.

Fitur admin:

- Login admin
- Dashboard admin
- Kelola hero section
- Kelola halaman website
- Kelola program sekolah
- Kelola program unggulan
- Kelola bimbel
- Kelola agenda, kategori agenda, dan pendaftaran agenda
- Kelola galeri kegiatan
- Kelola FAQ
- Kelola kontak sekolah
- Kelola template pesan WhatsApp
- Kelola data pendaftaran
- Upload dan ganti gambar
- Edit teks website tanpa ubah kode

---

## Identitas Sekolah

| Informasi | Detail |
|---|---|
| Nama Sekolah | Madani Montessori Islamic School |
| Jenjang | KB, TK A, TK B, TK C |
| Konsep | TK Islam Terpadu berbasis Montessori |
| Program Tambahan | Bimbel, Agenda |
| Alamat | Jalan Raya Perum Korpri Blok J1 No.16, Cisauk |
| WhatsApp | 0821 2357 6275 |

---

## Teknologi yang Digunakan

Project ini direkomendasikan menggunakan stack berikut:

- **Laravel**  EBackend dan web framework
- **Blade Template**  EView website publik
- **Tailwind CSS**  EStyling modern dan responsive
- **Filament Admin**  EAdmin CMS
- **MySQL**  EDatabase
- **Laravel Eloquent ORM**  ERelasi dan query database
- **Laravel Storage**  EUpload gambar dan file
- **Vite**  EAsset bundling
- **WhatsApp Link Integration**  ECTA dan template pesan otomatis

---

## Design Direction

Website menggunakan konsep visual:

- Elegan
- Minimalis
- Clean
- Premium
- Islami modern
- Mewah dan eksklusif
- Tidak terlalu ramai
- Tidak terlihat seperti template AI generik

### Warna Utama

| Nama | Warna |
|---|---|
| Primary Navy | `#0A1F5C` |
| Elegant Blue | `#123C8C` |
| Royal Blue | `#1D4ED8` |
| Gold Accent | `#F5C542` |
| Soft Yellow | `#FFE08A` |
| Cream | `#FFF7E6` |
| White | `#FFFFFF` |
| Text Dark | `#111827` |
| Text Muted | `#64748B` |

### Font

- **Poppins** untuk body text dan UI
- **League Spartan** untuk heading
- Logo ditampilkan dalam bentuk bulat dengan border radius penuh

---

## Struktur Halaman

```txt
/
├── Home
├── Tentang Kami
├── Program Sekolah
├── Program Unggulan
├── Bimbel
├── Agenda
├── Galeri
└── Kontak & Pendaftaran
```

---

Route publik Agenda:

```txt
GET /agenda
GET /agenda/{slug}
POST /agenda/{agenda}/registrations
GET /training-parenting -> redirect 301 ke /agenda
```

---

## Struktur Admin CMS

```txt
/admin
├── Dashboard
├── Halaman
├── Hero Section
├── Program Sekolah
├── Program Unggulan
├── Bimbel
├── Agenda
├── Galeri
├── FAQ
├── Kontak
├── Template WhatsApp
└── Pendaftaran
```

---

## Database Utama

Beberapa tabel utama yang digunakan:

| Tabel | Fungsi |
|---|---|
| `users` | Data admin CMS |
| `pages` | Konten halaman dinamis |
| `sections` | Section konten website |
| `programs` | Program sekolah dan bimbel |
| `featured_programs` | Program unggulan |
| `agenda_categories` | Kategori agenda sekolah |
| `agendas` | Data agenda sekolah |
| `agenda_registrations` | Data pendaftaran agenda |
| `galleries` | Galeri kegiatan |
| `faqs` | FAQ website |
| `registrations` | Data form pendaftaran |
| `settings` | Pengaturan umum website |
| `whatsapp_templates` | Template pesan WhatsApp |

---

## Instalasi Project

### 1. Clone Repository

```bash
git clone https://github.com/username/madani-montessori-website.git
cd madani-montessori-website
```

### 2. Install Dependency Backend

```bash
composer install
```

### 3. Install Dependency Frontend

```bash
npm install
```

### 4. Setup Environment

Copy file `.env.example` menjadi `.env`.

```bash
cp .env.example .env
```

Lalu sesuaikan konfigurasi database dan password admin awal:

```env
APP_NAME="Madani Montessori Islamic School"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
ADMIN_INITIAL_PASSWORD=admin123

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=madani_cms
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

### 5. Generate App Key

```bash
php artisan key:generate
```

### 6. Jalankan Migration dan Seeder

```bash
php artisan migrate --seed
```

### 7. Jalankan Storage Link

```bash
php artisan storage:link
```

Catatan: command ini untuk lokal atau hosting tradisional. Untuk Laravel Cloud, jangan masukkan `php artisan storage:link` ke deploy commands.

### 8. Jalankan Development Server

```bash
php artisan serve
```

Di terminal lain, jalankan Vite:

```bash
npm run dev
```

Website dapat dibuka di:

```txt
http://localhost:8000
```

Admin CMS dapat dibuka di:

```txt
http://localhost:8000/admin
```

---

## Akun Admin Default

> Sesuaikan dengan data seeder project.

```txt
Email    : admin@madanimontessori.sch.id
Password : nilai `ADMIN_INITIAL_PASSWORD` saat seed pertama, default lokal `admin123`
```

Seeder tidak akan menimpa password admin yang sudah ada. Untuk production, set `ADMIN_INITIAL_PASSWORD` ke password kuat sebelum seed pertama, lalu segera ubah password setelah login pertama.

---

## Build Production

Untuk build asset production:

```bash
npm run build
```

Pastikan konfigurasi `.env` production sudah disesuaikan:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-website.com
ADMIN_INITIAL_PASSWORD=password-kuat
```

Lalu jalankan:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Deploy Laravel Cloud

Repo ini bisa dideploy ke Laravel Cloud dengan build dan deploy commands berikut.

Build commands:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
npm ci
npm run build
php artisan optimize
```

Deploy commands:

```bash
php artisan migrate --force
```

Setelah deploy pertama selesai, jalankan seed satu kali dari Commands Laravel Cloud:

```bash
php artisan db:seed --force
```

Jangan jadikan `php artisan db:seed --force` sebagai deploy command permanen. Seeder membuat akun admin awal, dan data awal halaman publik memang dibutuhkan supaya route seperti `/` tidak 404.

Environment variable minimal untuk Laravel Cloud:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-laravel-cloud.laravel.cloud
ADMIN_INITIAL_PASSWORD=password-super-kuat
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
MAIL_MAILER=log
```

Untuk upload gambar CMS production, gunakan object storage/S3-compatible storage dan set `FILESYSTEM_DISK=s3`. Upload Filament mengikuti disk default dari env tersebut. Livewire temporary upload akan ikut memakai S3 saat `FILESYSTEM_DISK=s3`, atau bisa dipaksa dengan `LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK=s3`.

Contoh environment object storage Laravel Cloud:

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=isi_dari_laravel_cloud
AWS_SECRET_ACCESS_KEY=isi_dari_laravel_cloud
AWS_DEFAULT_REGION=auto
AWS_BUCKET=madani-media
AWS_ENDPOINT=https://endpoint-s3-compatible
AWS_URL=https://public-bucket-url
AWS_USE_PATH_STYLE_ENDPOINT=false
LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK=s3
```

Jangan commit `.env` dan jangan menaruh secret key di kode.

---

## Integrasi WhatsApp

Website menggunakan CTA WhatsApp dengan format:

```txt
https://wa.me/6282123576275?text=ISI_PESAN_ENCODED
```

Contoh template pesan:

```txt
Assalamu’alaikum, saya ingin konsultasi pendaftaran Madani Montessori Islamic School. Mohon info program, jadwal, dan biaya. Terima kasih.
```

Template pesan dapat dikelola melalui Admin CMS jika fitur sudah tersedia.

---

## Responsive Design

Website dibuat dengan pendekatan **mobile-first**.

Breakpoint:

| Ukuran | Layout |
|---|---|
| 360 E30px | Mobile layout 1 kolom |
| >= 768px | Tablet layout 2 kolom |
| >= 1024px | Desktop layout lebih luas dan grid |

---

## Folder Penting

```txt
app/
├── Models/
├── Http/
━E  ├── Controllers/
━E  └── Requests/

resources/
├── views/
━E  ├── layouts/
━E  ├── components/
━E  └── pages/
├── css/
└── js/

database/
├── migrations/
└── seeders/

public/
├── images/
└── storage/
```

---

## Roadmap

- [ ] Redesign semua halaman publik dengan tema elegan minimalis
- [ ] Integrasi penuh dengan Admin CMS
- [ ] Upload dan manajemen gambar dari admin
- [ ] Dynamic content untuk semua section
- [ ] Form pendaftaran tersimpan ke database
- [ ] Export data pendaftaran
- [ ] Lightbox galeri
- [ ] SEO meta tag per halaman
- [ ] Optimasi performa gambar
- [ ] Deployment production

---

## Deployment Checklist

Sebelum deploy, pastikan:

- [ ] `.env` production sudah benar
- [ ] `APP_DEBUG=false`
- [ ] Database sudah dibuat
- [ ] Migration sudah dijalankan
- [ ] Seed data awal sudah dijalankan sekali
- [ ] Asset sudah di-build dengan `npm run build`
- [ ] Folder `storage` dan `bootstrap/cache` writable untuk hosting non-Cloud
- [ ] Admin default sudah diganti password
- [ ] Upload CMS production memakai object storage jika deploy ke Laravel Cloud
- [ ] Domain dan SSL sudah aktif

---

## Catatan Keamanan

File berikut tidak boleh di-upload ke GitHub:

```txt
.env
/vendor
/node_modules
/storage/logs
/storage/framework/cache
/storage/framework/sessions
```

Pastikan `.gitignore` sudah dikonfigurasi dengan benar.

---

## Lisensi

Project ini dibuat untuk kebutuhan website resmi **Madani Montessori Islamic School**.

---

```txt
© 2026 Mubax5. All rights reserved.
```
