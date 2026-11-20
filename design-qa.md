# QA visual Madani Montessori

**final result: passed**

## Bukti

- Acuan yang disetujui adalah mockup beranda 724 × 2172 px yang tampil dalam percakapan. Tangkapan browser desktop memakai viewport 1280 × 720 CSS px. Tangkapan penuh memakai skala 0,75. Keduanya ditampilkan pada lebar visual yang sama di [perbandingan](preview-evidence/revision-comparison.jpg).
- [Desktop](preview-evidence/revision-full-desktop.jpg), [ponsel 390 × 844](preview-evidence/revision-mobile.jpg), [Program terbuka](preview-evidence/revision-program-open.jpg), [tabel Program di ponsel](preview-evidence/revision-program-table-mobile.jpg), dan [dialog Galeri](preview-evidence/revision-gallery-dialog.jpg).
- Perbandingan visual mencakup navbar, komposisi hero dan pengenalan, urutan hingga footer, serta detail motif dan kontrol Galeri. Baskervville dan Plus Jakarta Sans aktif pada judul dan teks. Krem dan navy logo menjaga kontras yang jelas. Batas melengkung, ukuran Galeri, dan posisi motif mengikuti koreksi sesudah mockup. Aset raster generatif dipasang terpisah dan Galeri menampilkan caption ilustrasi.

## Perbaikan dan uji ulang

- **Kontur Program.** Saat ringkasan mendapat fokus, wadah dengan overflow tersembunyi dapat bergulir 20 px dan menampakkan garis navy di bawah kontur. Wadah kini memotong ornamen tanpa menjadi area gulir. Pengujian dalam keadaan tertutup dan terbuka menunjukkan scroll internal 0 px serta kontur menutup bidang navy sebesar 1 px.
- **Posisi motif.** Program memiliki ukuran dan titik awal motif yang tetap. Pada desktop, tinggi bidang berubah dari 510 menjadi 555 px ketika Reguler dibuka, sedangkan tinggi motif tetap 422 px. Pada ponsel, bidang berubah dari 396 menjadi 468 px dan motif tetap 304 px. Motif tetap berpangkal pada tepi atas.
- **Galeri.** Browser mengonfirmasi foto 16 banding 9 bergeser otomatis ketika masuk layar. Gambar dapat dibuka bersama deskripsinya, Escape menutup dialog, dan fokus kembali ke foto asal. Saat reduced motion aktif, pergeseran otomatis berhenti dan navigasi keyboard tetap bekerja.
- **Tabel Program.** Tabel menunjukkan tiga program dalam kolom sejajar. Keempat kolom termasuk judul baris muat bersama pada viewport 390 px. Jam dan jumlah makan yang belum tercantum diberi penanda konfirmasi.
- **Navbar dan gerak.** Menu ponsel terbuka dengan Enter dan menutup dengan Escape. Viewport ponsel bebas luapan horizontal. Parallax berubah ketika halaman digulir dan berhenti saat preferensi reduced motion aktif.
- **PPDB.** Tombol konsultasi mengarah ke Kontak. Tautan formulir menuju bagian di halaman PPDB yang sama. Browser menolak nama yang hanya berisi spasi dan menerima contoh nama dan program yang valid. Pengujian memakai isian contoh dan berhenti sebelum membuka WhatsApp. Log browser bersih dari galat pada pemeriksaan ini.

## Penyelarasan visual

- Perubahan relatif terhadap gambar mockup mencakup kontur krem-navy tambahan, Galeri yang lebih ringkas, dan variasi gambar Montessori. Lengkung tipis di hero memakai ruang kosong di atas judul, sementara mockup memuat ranting di area tersebut.
