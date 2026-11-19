# Peta website

| Menu | Halaman | Peran |
|---|---|---|
| Beranda | / | Ikhtisar dan jalur ke halaman dalam |
| Profil | /profil/ | Pengenalan sekolah |
| Visi & Misi | /profil/visi-misi/ | Ruang untuk rumusan resmi |
| Metode Montessori | /metode-montessori/ | Satu contoh aktivitas Practical Life |
| Program | /program/ | Perbandingan langsung, lalu Program Unggulan |
| Program Sekolah | /program/program-sekolah/ | Rincian program Reguler, Half Day, dan Full Day |
| Program Unggulan | /program/program-unggulan/ | Practical Life, adab, dan pengenalan tahsin |
| Kegiatan | /agenda/ | Arsip kegiatan |
| Detail kegiatan | /agenda/annual-celebration/ | Catatan dari undangan sekolah |
| Galeri | /galeri/ | Foto yang disetujui, dikelompokkan menurut kategori |
| PPDB | /ppdb/ | Konsultasi admin dan pertanyaan jadwal kunjungan |
| Kontak | /kontak/ | Saluran resmi yang dipublikasikan dan informasi kunjungan |
| Kebijakan Privasi | /kebijakan-privasi/ | Kebijakan lingkup pratinjau |
| 404 | /404.html | Pemulihan untuk alamat yang tidak dikenal |

## Audit kompatibilitas

Domain tujuan yang dipilih untuk website baru adalah madanimontessori.com.

- Situs aktif WordPress memakai /tentang/, /program/, /agenda-galeri/, dan /kontak/. CTA pendaftarannya membuka /kontak/?ppdb=1#form. Sumber tema WordPress serta alur admin belum diterima. [Situs sekolah](https://madanimontessori.my.id/)
- Repo React/Vite memiliki server Express untuk data program, galeri, dan pertanyaan calon orang tua. Rute publik lama mencakup /tentang, /program-sekolah, /program-unggulan, /bimbel, dan /training-parenting. [Rute frontend lama](https://github.com/Mubax5/madani-montes-website/blob/main/client/src/App.tsx)
- Repo Laravel/Filament menangani CMS, agenda, pendaftaran acara dan lead. Rute /ppdb/daftar dan /ppdb/cek-status memakai layanan Nidham. [Rute Laravel lama](https://github.com/Mubax5/madani-montessori-landing-page/blob/main/routes/web.php)
- Sebelum perpindahan domain, petakan redirect /tentang/ ke /profil/, /program-sekolah/ dan /program-unggulan/ ke submenu masing-masing, serta /agenda-galeri/ ke arsip. Tinjau URL /bimbel/, /training-parenting/, dan setiap URL transaksi bersama admin. Pertahankan akses lama untuk catatan atau transaksi yang masih aktif.
