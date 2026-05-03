@extends('layouts.public')

@section('content')
    <section class="agenda-hero elegant-blue-texture">
        <div class="agenda-hero__grid">
            <div class="agenda-hero__content">
                <div class="hero-eyebrow">Agenda Sekolah</div>
                <h1 class="text-balance">Agenda Madani Montessori</h1>
                <p>Trial class, study tour, parenting class, workshop, field trip, dan kegiatan sekolah tersaji rapi agar orang tua mudah melihat jadwal terdekat.</p>
                <div class="agenda-hero__actions btn-row">
                    <a href="#agenda-terdekat" class="btn btn--gold">Lihat Agenda Terdekat</a>
                    <a href="{{ $whatsappUrl }}" class="btn btn--outline" target="_blank" rel="noopener noreferrer">Daftar via WhatsApp</a>
                </div>
            </div>

            <aside class="agenda-hero__panel">
                <span>Agenda Terdekat</span>
                <strong>{{ $upcomingAgendas->count() }}</strong>
                <p>Temukan jadwal kegiatan terbaru Madani Montessori untuk calon siswa dan orang tua.</p>
                @if ($categories->isNotEmpty())
                    <div class="agenda-hero__mini">
                        @foreach ($categories->take(4) as $category)
                            <span>{{ $category->name }}</span>
                        @endforeach
                    </div>
                @endif
            </aside>
        </div>
    </section>

    <section class="white-band section-pad">
        <div class="section-shell">
            <div class="section-kicker">Agenda Pilihan</div>
            <h2 class="section-title">Kegiatan pilihan dan jadwal paling dekat.</h2>

            @if ($featuredAgenda)
                @php
                    $featuredBadgeStyle = $featuredAgenda->category?->color ? '--badge-color: ' . $featuredAgenda->category->color : null;
                @endphp
                <article class="agenda-featured">
                    <div class="agenda-featured__media">
                        @if ($featuredAgenda->cover_image_url)
                            <img src="{{ $featuredAgenda->cover_image_url }}" alt="Cover {{ $featuredAgenda->title }}">
                        @else
                            <div class="agenda-image-placeholder">Agenda Pilihan</div>
                        @endif
                    </div>
                    <div class="agenda-featured__body">
                        <span class="agenda-badge" @if ($featuredBadgeStyle) style="{{ $featuredBadgeStyle }}" @endif>{{ $featuredAgenda->category?->name ?? 'Agenda' }}</span>
                        <h3>{{ $featuredAgenda->title }}</h3>
                        <p>{{ $featuredAgenda->excerpt ?: 'Kegiatan pilihan Madani Montessori untuk pengalaman belajar yang hangat dan terarah.' }}</p>
                        <dl class="agenda-featured__facts">
                            <div><dt>Tanggal</dt><dd>{{ $featuredAgenda->date_label }}</dd></div>
                            <div><dt>Jam</dt><dd>{{ $featuredAgenda->time_label }}</dd></div>
                            <div><dt>Lokasi</dt><dd>{{ $featuredAgenda->location_name ?: 'Madani Montessori Islamic School' }}</dd></div>
                        </dl>
                        <div class="btn-row">
                            <a href="{{ route('agenda.show', $featuredAgenda->slug) }}" class="btn btn--primary">Detail Agenda</a>
                            <a href="{{ $featuredAgenda->registration_type === 'form' ? route('agenda.show', $featuredAgenda->slug) . '#agenda-registration' : $featuredAgenda->registrationCtaUrl() }}" class="btn btn--gold" @if ($featuredAgenda->registration_type !== 'form') target="_blank" rel="noopener noreferrer" @endif>Daftar</a>
                        </div>
                    </div>
                </article>
            @else
                <article class="agenda-empty">
                    <strong>Belum ada agenda terbaru saat ini.</strong>
                    <p>Silakan hubungi kami untuk informasi jadwal trial class, open house, atau kegiatan berikutnya.</p>
                    <a href="{{ $whatsappUrl }}" class="btn btn--gold" target="_blank" rel="noopener noreferrer">Tanya Jadwal via WhatsApp</a>
                </article>
            @endif
        </div>
    </section>

    <section id="agenda-terdekat" class="cream-band section-pad">
        <div class="section-shell">
            <div class="agenda-section-head">
                <div>
                    <div class="section-kicker">Filter Agenda</div>
                    <h2 class="section-title">Agenda terdekat.</h2>
                    <p class="section-lead">Pilih kategori kegiatan sesuai kebutuhan keluarga atau sekolah.</p>
                </div>
            </div>

            <nav class="agenda-filter" aria-label="Filter kategori agenda">
                <a href="{{ route('agenda.index') }}" @class(['is-active' => blank($selectedCategory)])>Semua</a>
                @foreach ($categories as $category)
                    <a href="{{ route('agenda.index', ['category' => $category->slug]) }}" @class(['is-active' => $selectedCategory === $category->slug])>{{ $category->name }}</a>
                @endforeach
            </nav>

            <div class="agenda-grid">
                @forelse ($upcomingAgendas as $agenda)
                    @include('public.agenda.partials.card', ['agenda' => $agenda])
                @empty
                    <article class="agenda-empty agenda-empty--wide">
                        <strong>Belum ada agenda terbaru saat ini.</strong>
                        <p>Jadwal kategori ini belum tersedia. Silakan hubungi kami untuk informasi jadwal berikutnya.</p>
                        <a href="{{ $whatsappUrl }}" class="btn btn--gold" target="_blank" rel="noopener noreferrer">Tanya Jadwal via WhatsApp</a>
                    </article>
                @endforelse
            </div>
        </div>
    </section>

    @if ($pastAgendas->isNotEmpty())
        <section class="white-band section-pad section-pad--tight">
            <div class="section-shell">
                <div class="section-kicker">Agenda Sebelumnya</div>
                <h2 class="section-title">Dokumentasi jadwal yang sudah lewat.</h2>
                <div class="agenda-past-list">
                    @foreach ($pastAgendas as $agenda)
                        @include('public.agenda.partials.card', ['agenda' => $agenda, 'compact' => true])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
