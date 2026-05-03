@extends('layouts.public')

@php
    $hero = $sections->get('hero') ?? $sections->get('profile') ?? $sections->get('contact') ?? $sections->get('main') ?? $sections->first();
    $heroImage = $hero?->image?->url;
    $heroAlt = $hero?->image?->alt_text ?: 'Kegiatan Madani Montessori Islamic School';
    $siteName = $settings->get('site_name', 'Madani Montessori Islamic School');
    $siteTagline = $settings->get('site_tagline', 'TK Islam Terpadu berbasis Montessori, Bimbel, dan Agenda.');
    $heroHeading = $hero?->heading ?: $page->title;
    $heroSubheading = $hero?->subheading ?: $page->meta_description;
    $badges = $sections->get('hero')?->payload['badges'] ?? ['TK Islam Terpadu', 'Montessori', 'Bimbel', 'Parenting'];
    $highlights = $sections->get('hero')?->payload['highlights'] ?? [
        ['title' => 'Montessori Approach', 'description' => 'Anak belajar melalui material konkret, ritme mandiri, dan lingkungan yang tertata.'],
        ['title' => 'Tahfidz, Doa & Hadits', 'description' => 'Pembiasaan Islami hadir lembut dalam rutinitas harian anak.'],
        ['title' => 'Adab dan Karakter', 'description' => 'Fokus pada salam, tanggung jawab kecil, empati, dan kemandirian.'],
        ['title' => 'Sekolah + Bimbel', 'description' => 'Pilihan reguler, half-day, full-day, serta bimbel bertahap.'],
    ];
    $profile = $sections->get('profile');
    $mission = $profile?->payload['mission'] ?? [
        'Menyediakan lingkungan Montessori yang tertata.',
        'Membiasakan ibadah dan adab harian.',
        'Melibatkan orang tua dalam proses tumbuh anak.',
    ];
    $vision = $profile?->payload['vision'] ?? 'Menumbuhkan anak mandiri, berakhlak, dan cinta belajar.';
    $innerPanel = match ($page->slug) {
        'tentang' => ['Profil Sekolah', 'Pendekatan Montessori dan Islam Terpadu disusun sebagai rutinitas yang hangat, rapi, dan mudah dipahami anak.'],
        'program-sekolah' => ['KB sampai TK C', 'Program bertahap dari eksplorasi awal, kesiapan literasi, sampai persiapan transisi ke SD.'],
        'program-unggulan' => ['Program Inti', 'Setiap kegiatan menyeimbangkan praktik langsung, pembiasaan adab, dan kepekaan sensorial.'],
        'bimbel' => ['Bimbel Ramah Anak', 'Pendampingan calistung, mengaji, matematika, dan English dengan asesmen awal.'],
        'agenda' => ['Agenda Sekolah', 'Trial class, study tour, parenting class, workshop, dan kegiatan sekolah.'],
        'galeri' => ['Dokumentasi', 'Cuplikan kegiatan sekolah, bimbel, event, dan parenting yang bisa diperbarui dari CMS.'],
        'kontak' => ['Konsultasi Pendaftaran', 'Admin membantu memilih program, jadwal kunjungan, dan langkah pendaftaran berikutnya.'],
        default => ['Madani Montessori', $siteTagline],
    };
@endphp

@section('content')
    @if ($page->slug === 'home')
        <section class="home-hero elegant-blue-texture">
            <div class="home-hero__grid">
                <div>
                    <div class="hero-eyebrow">{{ $heroHeading }}</div>
                    <h1 class="text-balance">{{ $siteName }}</h1>
                    <p class="home-hero__summary">{{ $heroSubheading ?: $siteTagline }}</p>
                    <div class="pill-row mt-6">
                        @foreach ($badges as $badge)
                            <span class="pill">{{ $badge }}</span>
                        @endforeach
                    </div>
                    <div class="home-hero__actions btn-row">
                        <a href="{{ $whatsappUrl }}" class="btn btn--gold">{{ $hero?->cta_label ?: 'Konsultasi via WhatsApp' }}</a>
                        <a href="{{ route('program-sekolah') }}" class="btn btn--outline">Lihat Program</a>
                    </div>
                </div>

                <div class="hero-visual" aria-label="Visual kegiatan Madani Montessori">
                    <div class="hero-visual__frame">
                        @if ($heroImage)
                            <img src="{{ $heroImage }}" alt="{{ $heroAlt }}">
                        @else
                            <div class="image-placeholder">Foto Kegiatan Sekolah</div>
                        @endif
                    </div>
                    <div class="hero-floating-card">
                        <strong>Montessori yang tenang, Islami, dan terarah.</strong>
                        <span>Sekolah dan bimbel untuk anak usia dini dengan komunikasi orang tua yang dekat.</span>
                        <div class="hero-microgrid">
                            <span>KB - TK A, B, C</span>
                            <span>Calistung</span>
                            <span>Mengaji</span>
                            <span>Sensorial</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-highlights">
            <div class="section-shell">
                <div class="highlight-rail">
                    @foreach ($highlights as $item)
                        <article class="highlight-card">
                            <span class="highlight-card__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['description'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        @include('public.partials.programs', ['limit' => 4])
        @include('public.partials.featured')
        @include('public.partials.bimbel')
        @include('public.partials.location')
    @else
        <section class="inner-hero elegant-blue-texture">
            <div class="inner-hero__grid">
                <div class="inner-hero__content">
                    <div class="hero-eyebrow">{{ $page->title }}</div>
                    <h1 class="text-balance">{{ $heroHeading }}</h1>
                    <p>{{ $heroSubheading }}</p>
                </div>
                <aside class="inner-hero__panel">
                    <strong>{{ $innerPanel[0] }}</strong>
                    <p>{{ $innerPanel[1] }}</p>
                    <div class="pill-row mt-5">
                        <span class="pill">Clean</span>
                        <span class="pill">Premium</span>
                        <span class="pill">Islami</span>
                    </div>
                </aside>
            </div>
        </section>

        @if ($page->slug === 'tentang')
            <section class="cream-band section-pad">
                <div class="section-shell story-layout">
                    <div class="story-text">
                        <div class="section-kicker">Cerita Madani</div>
                        <h2 class="section-title">Ruang awal belajar yang rapi, hangat, dan bernilai adab.</h2>
                        <p>{{ $profile?->subheading ?: $heroSubheading }}</p>
                        <div class="value-badges">
                            <span>Mandiri</span>
                            <span>Beradab</span>
                            <span>Fokus</span>
                            <span>Cinta Belajar</span>
                        </div>
                    </div>
                    <div class="story-image-stack">
                        <div class="story-image">
                            @if ($heroImage)
                                <img src="{{ $heroImage }}" alt="{{ $heroAlt }}">
                            @else
                                <div class="image-placeholder">Foto Ruang Kelas</div>
                            @endif
                        </div>
                        <div class="vision-card">
                            <span>Visi</span>
                            <p>{{ $vision }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="white-band section-pad">
                <div class="section-shell">
                    <div class="section-kicker">Misi Sekolah</div>
                    <h2 class="section-title">Pembiasaan kecil yang disusun konsisten setiap hari.</h2>
                    <div class="mission-timeline">
                        @foreach ($mission as $item)
                            <article class="mission-item">
                                <span>{{ $loop->iteration }}</span>
                                <p>{{ $item }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            @include('public.partials.gallery', ['compact' => true])
        @elseif ($page->slug === 'program-sekolah')
            @include('public.partials.programs', ['limit' => null])
            @include('public.partials.steps')
            @include('public.partials.faq')
        @elseif ($page->slug === 'program-unggulan')
            @include('public.partials.featured')
            <section class="cream-band section-pad">
                <div class="section-shell">
                    <div class="section-kicker">Metode Belajar</div>
                    <h2 class="section-title">Anak memegang, mencoba, mengulang, lalu memahami.</h2>
                    <div class="method-grid">
                        <article class="method-card">
                            <h3>Praktik langsung</h3>
                            <p>Material dan aktivitas konkret membantu anak memahami konsep tanpa tekanan.</p>
                        </article>
                        <article class="method-card">
                            <h3>Sensorial</h3>
                            <p>Indra anak dilatih melalui pengalaman visual, taktil, gerak, bunyi, dan urutan kerja.</p>
                        </article>
                        <article class="method-card">
                            <h3>Pembiasaan adab</h3>
                            <p>Rutinitas kelas diarahkan agar anak terbiasa santun, tertib, dan peduli.</p>
                        </article>
                    </div>
                </div>
            </section>
        @elseif ($page->slug === 'bimbel')
            @include('public.partials.bimbel')
            @include('public.partials.faq')
            <a href="{{ \App\Support\SiteContent::whatsappUrl('minat_bimbel') }}" class="mobile-sticky-cta">Tanya Bimbel via WhatsApp</a>
        @elseif ($page->slug === 'galeri')
            @include('public.partials.gallery', ['compact' => false])
        @elseif ($page->slug === 'kontak')
            @include('public.partials.steps')
            <section class="cream-band section-pad">
                <div class="section-shell contact-layout">
                    @include('public.partials.contact')
                    @include('public.partials.lead-form')
                </div>
            </section>
            @include('public.partials.faq')
        @endif
    @endif

    <section class="cta-section">
        <div class="section-shell">
            <div class="cta-card elegant-blue-texture">
                <div>
                    <div class="section-kicker section-kicker--light">Konsultasi</div>
                    <h2>Diskusikan program yang paling cocok untuk anak.</h2>
                    <p>Admin Madani siap membantu menjelaskan pilihan sekolah, bimbel, agenda, jadwal, dan langkah pendaftaran.</p>
                </div>
                <a href="{{ $whatsappUrl }}" class="btn btn--gold">Chat Admin Madani</a>
            </div>
        </div>
    </section>
@endsection
