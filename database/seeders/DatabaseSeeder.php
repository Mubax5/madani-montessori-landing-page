<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use App\Models\BimbelPackage;
use App\Models\BimbelPackageItem;
use App\Models\Faq;
use App\Models\FeaturedProgram;
use App\Models\GalleryItem;
use App\Models\MediaAsset;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Program;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\TrainingEvent;
use App\Models\WhatsappTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = collect([
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Pemilik akses penuh CMS.'],
            ['name' => 'Admin Konten', 'slug' => 'admin_konten', 'description' => 'Mengelola konten website.'],
            ['name' => 'Admin Pendaftaran', 'slug' => 'admin_pendaftaran', 'description' => 'Mengelola leads pendaftaran.'],
            ['name' => 'Viewer', 'slug' => 'viewer', 'description' => 'Melihat dashboard dan leads.'],
        ])->mapWithKeys(fn (array $role): array => [
            $role['slug'] => Role::updateOrCreate(['slug' => $role['slug']], $role),
        ]);

        AdminUser::query()
            ->whereIn('email', [
                'konten@madanimontessori.sch.id',
                'pendaftaran@madanimontessori.sch.id',
            ])
            ->delete();

        AdminUser::updateOrCreate(
            ['email' => 'admin@madanimontessori.sch.id'],
            [
                'role_id' => $roles['super_admin']->id,
                'name' => 'Admin Madani',
                'password_hash' => Hash::make('admin123'),
                'is_active' => true,
            ],
        );

        $this->seedSettings();
        $this->seedNavigation();
        $this->seedMedia();
        $this->seedPages();
        $this->seedPrograms();
        $this->seedBimbel();
        $this->seedTraining();
        $this->seedGallery();
        $this->seedFaqs();
        $this->seedWhatsappTemplates();
    }

    private function seedSettings(): void
    {
        foreach ([
            'site_name' => ['Madani Montessori Islamic School', 'text'],
            'site_tagline' => ['TK Islam Terpadu berbasis Montessori, Bimbel, Training & Parenting.', 'textarea'],
            'address' => ['National Education Centre, Tangerang, Banten', 'textarea'],
            'whatsapp_number' => ['6282123576275', 'text'],
            'email' => ['info@madanimontessori.sch.id', 'text'],
            'maps_url' => ['https://maps.google.com/?q=National+Education+Centre', 'url'],
            'maps_embed_url' => ['https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d652.1473505745165!2d106.62805344122339!3d-6.3529058163396215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e3874490ef33%3A0x64684f18b41459a0!2sNational%20Education%20Centre!5e0!3m2!1sen!2sid!4v1777585749144!5m2!1sen!2sid', 'url'],
            'primary_color' => ['#0A1F5C', 'color'],
            'footer_summary' => ['Madani Montessori Islamic School mendampingi anak belajar mandiri, berakhlak, dan percaya diri melalui pendekatan Montessori dan nilai Islam Terpadu.', 'textarea'],
        ] as $key => [$value, $type]) {
            SiteSetting::updateOrCreate(['setting_key' => $key], [
                'setting_value' => $value,
                'setting_type' => $type,
            ]);
        }
    }

    private function seedNavigation(): void
    {
        foreach ([
            ['Beranda', '/', 1],
            ['Tentang', '/tentang', 2],
            ['Program Sekolah', '/program-sekolah', 3],
            ['Program Unggulan', '/program-unggulan', 4],
            ['Bimbel', '/bimbel', 5],
            ['Training & Parenting', '/training-parenting', 6],
            ['Galeri', '/galeri', 7],
            ['Kontak', '/kontak', 8],
        ] as [$label, $url, $order]) {
            NavigationItem::updateOrCreate(['url' => $url, 'location' => 'header'], [
                'label' => $label,
                'sort_order' => $order,
                'is_active' => true,
            ]);

            NavigationItem::updateOrCreate(['url' => $url, 'location' => 'footer'], [
                'label' => $label,
                'sort_order' => $order,
                'is_active' => true,
            ]);
        }
    }

    private function seedMedia(): void
    {
        foreach ([
            ['hero-classroom.png', '/images/generated/hero-classroom.png', 'Kegiatan belajar Montessori Islami di kelas Madani'],
            ['about-classroom.png', '/images/generated/about-classroom.png', 'Ruang kelas Montessori yang hangat dan rapi'],
            ['cta-family.png', '/images/generated/cta-family.png', 'Ilustrasi keluarga berkonsultasi dengan sekolah'],
        ] as [$file, $path, $alt]) {
            MediaAsset::updateOrCreate(['file_path' => $path], [
                'file_name' => $file,
                'mime_type' => 'image/png',
                'alt_text' => $alt,
                'caption' => $alt,
            ]);
        }
    }

    private function seedPages(): void
    {
        $pages = [
            'home' => ['Beranda', 'Madani Montessori Islamic School', 'TK Islam Terpadu berbasis Montessori untuk anak yang tumbuh mandiri, berakhlak, dan percaya diri.'],
            'tentang' => ['Tentang Kami', 'Tentang Madani Montessori', 'Profil, visi misi, dan pendekatan Montessori Islami Madani.'],
            'program-sekolah' => ['Program Sekolah', 'Program KB dan TK Madani', 'Program sekolah KB, TK A, TK B, dan TK C dengan pilihan reguler, half-day, dan full-day.'],
            'program-unggulan' => ['Program Unggulan', 'Program Unggulan Madani', 'Kegiatan unggulan sekolah yang menguatkan karakter, kemandirian, dan adab anak.'],
            'bimbel' => ['Bimbel', 'Bimbel Madani', 'Bimbel ramah anak untuk membaca, menulis, berhitung, dan pendampingan belajar.'],
            'training-parenting' => ['Training & Parenting', 'Training dan Parenting Madani', 'Workshop guru, kelas parenting, dan konsultasi pendidikan anak.'],
            'galeri' => ['Galeri', 'Galeri Kegiatan Madani', 'Dokumentasi kegiatan sekolah, bimbel, dan event parenting.'],
            'kontak' => ['Kontak & Pendaftaran', 'Kontak dan Pendaftaran', 'Hubungi Madani Montessori dan kirim data pendaftaran calon siswa.'],
        ];

        foreach ($pages as $slug => [$title, $metaTitle, $metaDescription]) {
            $page = Page::updateOrCreate(['slug' => $slug], [
                'title' => $title,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
                'is_published' => true,
            ]);

            $this->seedSections($page);
        }
    }

    private function seedSections(Page $page): void
    {
        $heroImage = MediaAsset::where('file_path', '/images/generated/hero-classroom.png')->first();
        $aboutImage = MediaAsset::where('file_path', '/images/generated/about-classroom.png')->first();

        $content = [
            'home' => [
                ['hero', 'Hero Beranda', 'Sekolah Montessori Islami yang hangat untuk masa awal belajar anak', 'Madani Montessori Islamic School mendampingi anak belajar mandiri, beradab, dan percaya diri melalui lingkungan yang tertata, guru yang peduli, dan pembiasaan Islami.', 'Konsultasi via WhatsApp', 'konsultasi_umum', $heroImage?->id, [
                    'badges' => ['TK Islam Terpadu', 'Montessori', 'Bimbel', 'Parenting'],
                    'highlights' => [
                        ['title' => 'Lingkungan tertata', 'description' => 'Ruang belajar rapi dengan material yang mudah dijangkau anak.'],
                        ['title' => 'Adab harian', 'description' => 'Pembiasaan salam, doa, mandiri, dan saling menghargai.'],
                        ['title' => 'Guru pendamping', 'description' => 'Anak dibimbing sesuai ritme tumbuh dan kesiapan belajar.'],
                        ['title' => 'Komunikasi orang tua', 'description' => 'Sekolah mudah dihubungi untuk konsultasi perkembangan anak.'],
                    ],
                ]],
                ['location', 'Lokasi Preview', 'Dekat dan mudah dihubungi', 'Kunjungi sekolah atau konsultasi lebih dulu melalui WhatsApp untuk menyesuaikan kebutuhan anak.', 'Lihat kontak', '/kontak', null, null],
            ],
            'tentang' => [
                ['profile', 'Profil Sekolah', 'Madani Montessori Islamic School', 'Kami menggabungkan pendekatan Montessori dengan nilai Islam Terpadu agar anak belajar melalui pengalaman konkret, adab harian, dan lingkungan yang aman.', 'Konsultasi profil sekolah', 'konsultasi_umum', $aboutImage?->id, [
                    'vision' => 'Menjadi sekolah awal yang menumbuhkan anak mandiri, berakhlak, dan cinta belajar.',
                    'mission' => ['Menyediakan lingkungan Montessori yang tertata.', 'Membiasakan ibadah dan adab harian.', 'Melibatkan orang tua dalam proses tumbuh anak.'],
                ]],
            ],
            'kontak' => [
                ['contact', 'Kontak', 'Mari konsultasikan kebutuhan anak', 'Isi form pendaftaran atau hubungi WhatsApp agar admin dapat membantu memilih program yang sesuai.', 'Chat WhatsApp', 'konsultasi_umum', null, null],
            ],
        ];

        foreach ($content[$page->slug] ?? [
            ['main', 'Konten Utama', $page->title, $page->meta_description, 'Konsultasi via WhatsApp', 'konsultasi_umum', null, null],
        ] as $index => [$key, $name, $heading, $body, $ctaLabel, $ctaUrl, $imageId, $payload]) {
            PageSection::updateOrCreate(
                ['page_id' => $page->id, 'section_key' => $key],
                [
                    'section_name' => $name,
                    'heading' => $heading,
                    'subheading' => $body,
                    'body' => null,
                    'payload' => $payload,
                    'cta_label' => $ctaLabel,
                    'cta_url' => $ctaUrl,
                    'image_id' => $imageId,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }

    private function seedPrograms(): void
    {
        foreach ([
            ['kb', 'Kelompok Bermain', 'reguler', 'Program awal untuk kemandirian, sensori, bahasa, dan sosial anak.', '2-4 tahun', 'Reguler'],
            ['tk_a', 'TK A', 'reguler', 'Kegiatan Montessori untuk kesiapan literasi, numerasi, motorik, dan adab harian.', '4-5 tahun', 'Reguler'],
            ['tk_b', 'TK B', 'half_day', 'Persiapan transisi ke SD dengan kegiatan konkret, proyek kecil, dan pembiasaan ibadah.', '5-6 tahun', 'Half-day'],
            ['tk_c', 'TK C', 'full_day', 'Pendampingan lebih panjang untuk anak yang membutuhkan ritme belajar dan istirahat terstruktur.', '5-6 tahun', 'Full-day'],
        ] as $order => [$type, $name, $category, $description, $age, $duration]) {
            Program::updateOrCreate(['program_type' => $type, 'category' => $category], [
                'name' => $name,
                'description' => $description,
                'age_range' => $age,
                'duration' => $duration,
                'sort_order' => $order + 1,
                'is_active' => true,
            ]);
        }

        foreach ([
            ['Adab dan Doa Harian', 'Pembiasaan salam, doa, berbagi, dan tanggung jawab kecil setiap hari.', 'sparkles'],
            ['Montessori Practical Life', 'Aktivitas konkret untuk melatih fokus, koordinasi, dan kemandirian anak.', 'hand-raised'],
            ['Tahsin dan Hafalan Ringan', 'Pengenalan huruf hijaiyah, surat pendek, dan doa pilihan dengan suasana lembut.', 'book-open'],
            ['Parent Communication', 'Orang tua mendapat arahan praktis untuk melanjutkan kebiasaan baik di rumah.', 'chat'],
        ] as $order => [$title, $description, $icon]) {
            FeaturedProgram::updateOrCreate(['title' => $title], [
                'description' => $description,
                'icon' => $icon,
                'sort_order' => $order + 1,
                'is_active' => true,
            ]);
        }
    }

    private function seedBimbel(): void
    {
        $packages = [
            ['calistung', 'Bimbel Calistung', 'Pendampingan membaca, menulis, dan berhitung dengan cara bertahap.', 'Anak usia TK dan awal SD'],
            ['school-support', 'Pendampingan Belajar', 'Bantuan belajar harian untuk anak yang membutuhkan ritme dan fokus.', 'Anak TK B sampai SD awal'],
        ];

        foreach ($packages as [$slug, $name, $description, $target]) {
            $package = BimbelPackage::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'description' => $description,
                'target' => $target,
                'cta_label' => 'Tanya paket bimbel',
                'cta_message' => 'Assalamualaikum, saya ingin bertanya tentang program Bimbel Madani Montessori Islamic School.',
                'is_active' => true,
            ]);

            foreach (['Asesmen awal anak', 'Jadwal fleksibel', 'Laporan perkembangan'] as $index => $item) {
                BimbelPackageItem::updateOrCreate(['package_id' => $package->id, 'title' => $item], [
                    'description' => 'Admin akan menjelaskan detail sesuai kebutuhan anak.',
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }

    private function seedTraining(): void
    {
        foreach ([
            ['Workshop Montessori untuk Guru', 'Montessori', 'guru', now()->addWeeks(2)->toDateString(), '09.00-12.00'],
            ['Kelas Parenting: Rutinitas Anak di Rumah', 'Parenting', 'orang_tua', now()->addWeeks(4)->toDateString(), '10.00-11.30'],
        ] as $order => [$title, $topic, $target, $date, $time]) {
            TrainingEvent::updateOrCreate(['title' => $title], [
                'topic' => $topic,
                'target_audience' => $target,
                'event_date' => $date,
                'event_time' => $time,
                'description' => 'Sesi praktis dengan contoh kegiatan dan ruang tanya jawab.',
                'status' => 'published',
                'sort_order' => $order + 1,
            ]);
        }
    }

    private function seedGallery(): void
    {
        $media = MediaAsset::whereIn('file_path', [
            '/images/generated/hero-classroom.png',
            '/images/generated/about-classroom.png',
            '/images/generated/cta-family.png',
        ])->get();

        foreach ($media as $index => $asset) {
            GalleryItem::updateOrCreate(['media_id' => $asset->id], [
                'category' => $index === 2 ? 'event' : 'sekolah',
                'title' => ['Kegiatan kelas', 'Ruang belajar', 'Konsultasi keluarga'][$index] ?? 'Foto kegiatan',
                'description' => 'Foto awal dapat diganti admin melalui Media Library.',
                'is_featured' => $index === 0,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }

    private function seedFaqs(): void
    {
        foreach ([
            ['program-sekolah', 'Apakah bisa konsultasi sebelum mendaftar?', 'Bisa. Orang tua dapat menghubungi WhatsApp untuk menyesuaikan program dengan usia dan kebutuhan anak.'],
            ['program-sekolah', 'Apakah tersedia half-day dan full-day?', 'Ya, admin dapat menjelaskan jadwal yang tersedia sesuai program dan kapasitas kelas.'],
            ['bimbel', 'Apakah bimbel harus setiap hari?', 'Tidak harus. Jadwal dapat disesuaikan setelah asesmen awal.'],
            ['kontak', 'Bagaimana cara mendaftar?', 'Isi form pendaftaran atau kirim pesan WhatsApp. Admin akan menghubungi untuk langkah berikutnya.'],
        ] as $order => [$scope, $question, $answer]) {
            Faq::updateOrCreate(['page_scope' => $scope, 'question' => $question], [
                'answer' => $answer,
                'sort_order' => $order + 1,
                'is_active' => true,
            ]);
        }
    }

    private function seedWhatsappTemplates(): void
    {
        foreach ([
            ['Konsultasi Umum', 'konsultasi_umum', 'Assalamualaikum, saya ingin konsultasi pendaftaran Madani Montessori Islamic School. Mohon info program, jadwal, dan biaya. Terima kasih.'],
            ['Minat Program Sekolah', 'minat_program_sekolah', 'Assalamualaikum, saya berminat mendaftar Program Sekolah di Madani Montessori Islamic School. Mohon info KB/TK, jadwal, dan biaya. Terima kasih.'],
            ['Minat Bimbel', 'minat_bimbel', 'Assalamualaikum, saya ingin bertanya tentang program Bimbel Madani Montessori Islamic School. Mohon info paket, jadwal, dan biaya. Terima kasih.'],
            ['Minat Training & Parenting', 'minat_training_parenting', 'Assalamualaikum, saya ingin bertanya tentang Training & Parenting Madani Montessori Islamic School. Mohon info jadwal dan topiknya. Terima kasih.'],
        ] as [$name, $key, $message]) {
            WhatsappTemplate::updateOrCreate(['template_key' => $key], [
                'name' => $name,
                'message' => $message,
                'is_active' => true,
            ]);
        }
    }
}
