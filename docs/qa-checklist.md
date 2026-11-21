# Checklist QA

## Build dan navigasi

- Jalankan npm run lint, npm run check, npm run build, dan npm test.
- Buka tautan internal, submenu Profil dan Program, arsip kegiatan dan detail, halaman Galeri kosong, serta 404.
- Cek tautan konsultasi PPDB ke Kontak, tautan formulir di dalam halaman PPDB, dan draf WhatsApp untuk minat penerimaan. Cek pula perbedaan pesan konsultasi dan kunjungan dari halaman Kontak.

## Tampilan dan aksesibilitas

- Cek hierarki judul, keterbacaan kedua tombol hero, crop dan label ilustrasi, serta kontur sebelum footer pada beranda dan halaman dalam.
- Cek viewport 320, 360, 390, 768, 1280, 1440, dan 1920 px, serta mobile lanskap. Pastikan halaman tidak bergeser horizontal. Periksa gulir tabel Program dan keterbacaan judul.
- Uji fokus, skip link, menu keyboard dan sentuh, Escape, dan pengembalian fokus dari dialog galeri. Cek kontras dan pembesaran teks.
- Saat media asli tersedia, cek crop dan overlay per perangkat, izin publikasi, caption, dan ukuran file. Audit Core Web Vitals pada host tujuan sebelum peluncuran.

## Publikasi dan perubahan sistem

- Validasi saluran kontak, alamat, jadwal program, dan konten yang masih menunggu konfirmasi.
- Pastikan keputusan tentang URL lama, alur admin lama, kebijakan privasi, dan domain diambil sebelum cutover.
- Rekam build, hasil uji, dan capture viewport di PR setelah repo tujuan dikonfirmasi.
