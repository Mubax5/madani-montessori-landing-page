# Checklist QA

## Build dan navigasi

- Jalankan npm run lint, npm run check, npm run build, dan npm test.
- Buka tautan internal, submenu Profil dan Program, arsip kegiatan dan detail, halaman Galeri kosong, serta 404.
- Cek tautan konsultasi PPDB ke Kontak, tautan formulir di dalam halaman PPDB, dan draf WhatsApp untuk minat penerimaan. Cek pula perbedaan pesan konsultasi dan kunjungan dari halaman Kontak.

## Tampilan dan aksesibilitas

- Bandingkan Beranda dengan PNG sumber pada 1536 × 1024. Cek hierarki judul, lengkung dan divider, posisi botani, dan spasi sebelum pengenalan.
- Cek viewport 360, 390, 768, 1280, 1440, dan 1920 px. Pastikan tidak ada overflow horizontal, judul terpotong, atau tata letak program sulit dipindai.
- Uji fokus, skip link, menu keyboard dan sentuh, serta Escape. Cek kontras dan zoom.
- Saat media asli tersedia, cek crop dan overlay per perangkat, izin publikasi, caption, dan ukuran file. Audit Core Web Vitals pada host tujuan sebelum peluncuran.

## Publikasi dan perubahan sistem

- Validasi saluran kontak, alamat, jadwal program, dan konten yang masih menunggu konfirmasi.
- Pastikan keputusan tentang URL lama, alur admin lama, kebijakan privasi, dan domain diambil sebelum cutover.
- Rekam build, hasil uji, dan capture viewport di PR setelah repo tujuan dikonfirmasi.
