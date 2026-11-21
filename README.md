# Madani Montessori

Website sekolah multi-halaman dengan Astro, TypeScript, dan CSS editorial. Hasil build berupa HTML statis. Pratinjau bisa dibuka tanpa akses ke WordPress, Laravel, atau data pendaftaran sekolah.

## Menjalankan di lokal

Gunakan Node.js 22.13+ pada lini 22 atau Node.js 24+.

~~~sh
npm ci
npm run dev
~~~

Untuk verifikasi, jalankan npm run lint, npm run check, npm run build, lalu npm test. Gunakan npm run preview untuk melihat hasil build.

## Memperbarui isi

- Data program, kontak, dan kegiatan ada di [src/content/school.ts](src/content/school.ts). Sumber dan status konfirmasi tercatat di [inventaris konten](docs/content-inventory.md).
- Impor foto berizin ke `src/content/school.ts`. Isi `image` dan `alt` pada `schoolMedia.hero`, `schoolMedia.materials`, `schoolMedia.profile`, atau `schoolMedia.practicalLife` sesuai penempatannya. Gunakan `position` dan `mobilePosition` bila crop hero perlu disesuaikan. Hingga empat foto galeri pertama tampil di Beranda.
- Pratinjau mengirim noindex secara default. Variabel PUBLIC_INDEXABLE=true disediakan untuk build yang sudah disetujui untuk publikasi.
- Domain tujuan adalah madanimontessori.com. Build yang bisa diindeks memakai domain itu untuk URL canonical.

## Dokumentasi

- [Desain dan acuan visual](docs/design.md)
- [Peta halaman dan audit rute lama](docs/information-architecture.md)
- [Inventaris konten](docs/content-inventory.md)
- [Manifest aset](docs/assets-manifest.md)
- [Checklist QA](docs/qa-checklist.md)
