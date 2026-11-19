# QA visual Beranda

**final result: passed**

## Bukti perbandingan

- PNG acuan 1536 × 1024 memberi patokan judul dua baris, lengkung tipis, dan pemisah dangkal. Revisi memakai hero setinggi layar, tekstur raster, dan foto alat peraga di kanan bagian pengenalan.
- Tangkapan browser diperiksa pada 1440 × 900 dan 390 × 844. Bukti bernama navy-desktop.jpg dan navy-mobile.jpg menunjukkan iterasi sebelumnya.

## Temuan

- **Komposisi.** Hero berakhir di batas viewport pada enam ukuran layar. Garis emas menegaskan pemisah. Foto dan tekstur termuat di browser.
- **Susunan halaman.** Bagian belajar kembali memakai navy bertekstur dengan narasi singkat. Program, Kegiatan, Galeri, dan PPDB memiliki fungsi berbeda. Navbar menggunakan tautan dengan penanda aktif sederhana.
- **Media dan isi.** Hero pratinjau menampilkan ruang Montessori berlisensi dan berlabel. Foto alat peraga memiliki kredit. Foto murid serta Galeri Madani masih menunggu aset berizin.

## Verifikasi perilaku

- Lebar 360, 390, 768, 1024, 1440, dan 1920 px diperiksa untuk overflow di Beranda. Semua halaman dalam diperiksa pada 360 px.
- Ringkasan program dapat dibuka dengan Enter dan menutup pilihan sebelumnya. FAQ PPDB dapat dibuka dengan Enter. Navbar mobile menandai halaman PPDB dengan aria-current.
- Offset parallax berubah saat scroll. Setelah reduced motion aktif, offset dihapus dan transform dinonaktifkan. Konsol browser tidak melaporkan galat selama pemeriksaan.
