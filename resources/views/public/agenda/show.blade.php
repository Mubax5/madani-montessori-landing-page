@extends('layouts.public')

@php
    $category = $agenda->category;
    $badgeStyle = $category?->color ? '--badge-color: ' . $category->color : null;
@endphp

@section('content')
    <section class="agenda-detail-hero elegant-blue-texture">
        <div class="agenda-detail-hero__grid">
            <div>
                <div class="hero-eyebrow">Detail Agenda</div>
                <span class="agenda-badge agenda-badge--light" @if ($badgeStyle) style="{{ $badgeStyle }}" @endif>{{ $category?->name ?? 'Agenda' }}</span>
                <h1 class="text-balance">{{ $agenda->title }}</h1>
                <div class="agenda-detail-hero__meta">
                    <span>{{ $agenda->date_label }}</span>
                    <span>{{ $agenda->time_label }}</span>
                    <span>{{ $agenda->location_name ?: 'Madani Montessori Islamic School' }}</span>
                </div>
            </div>

            <div class="agenda-detail-hero__media">
                @if ($agenda->cover_image_url)
                    <img src="{{ $agenda->cover_image_url }}" alt="Cover {{ $agenda->title }}">
                @else
                    <div class="agenda-image-placeholder">Agenda Madani</div>
                @endif
            </div>
        </div>
    </section>

    <section class="cream-band section-pad">
        <div class="section-shell agenda-detail-layout">
            <article class="agenda-detail-main">
                <div class="section-kicker">Detail Informasi</div>
                <h2 class="section-title">Informasi kegiatan.</h2>

                @if ($agenda->excerpt)
                    <p class="agenda-detail-lead">{{ $agenda->excerpt }}</p>
                @endif

                <div class="agenda-richtext">
                    @if ($agenda->description)
                        {!! \App\Support\HtmlSanitizer::clean($agenda->description) !!}
                    @else
                        <p>Detail agenda akan diperbarui oleh admin CMS.</p>
                    @endif
                </div>

                <div class="agenda-detail-facts">
                    @if ($agenda->target_audience)
                        <div><span>Target Peserta</span><strong>{{ $agenda->target_audience }}</strong></div>
                    @endif
                    @if ($agenda->quota)
                        <div><span>Kuota</span><strong>{{ $agenda->quota }} peserta</strong></div>
                    @endif
                    <div><span>Biaya</span><strong>{{ $agenda->formatted_price }}</strong></div>
                    <div><span>Status</span><strong>{{ $agenda->registrationStatusLabel() }}</strong></div>
                </div>

                @if ($agenda->maps_url)
                    <a href="{{ $agenda->maps_url }}" class="btn btn--outline-dark agenda-map-link" target="_blank" rel="noopener noreferrer">Buka Maps</a>
                @endif
            </article>

            <aside class="agenda-registration-panel" id="agenda-registration">
                <div class="section-kicker">Pendaftaran</div>
                <h2>Daftar agenda ini.</h2>
                <p>{{ $agenda->registrationStatusLabel() }}</p>

                @if (session('success'))
                    <div class="success-message">{{ session('success') }}</div>
                @endif

                @error('agenda_registration')
                    <small>{{ $message }}</small>
                @enderror

                @if ($agenda->registration_type === 'form' && $agenda->isRegistrationOpen())
                    <form action="{{ route('agenda.registrations.store', $agenda->slug) }}" method="POST" class="agenda-registration-form">
                        @csrf
                        <label>
                            <span>Nama orang tua</span>
                            <input type="text" name="parent_name" value="{{ old('parent_name') }}" required>
                            @error('parent_name') <small>{{ $message }}</small> @enderror
                        </label>
                        <label>
                            <span>Nama anak</span>
                            <input type="text" name="child_name" value="{{ old('child_name') }}">
                            @error('child_name') <small>{{ $message }}</small> @enderror
                        </label>
                        <label>
                            <span>Usia anak</span>
                            <input type="number" name="child_age" value="{{ old('child_age') }}" min="1" max="18">
                            @error('child_age') <small>{{ $message }}</small> @enderror
                        </label>
                        <label>
                            <span>Nomor WhatsApp</span>
                            <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number') }}" required>
                            @error('whatsapp_number') <small>{{ $message }}</small> @enderror
                        </label>
                        <label>
                            <span>Email</span>
                            <input type="email" name="email" value="{{ old('email') }}">
                            @error('email') <small>{{ $message }}</small> @enderror
                        </label>
                        <label>
                            <span>Jumlah peserta</span>
                            <input type="number" name="participant_count" value="{{ old('participant_count', 1) }}" min="1" max="20" required>
                            @error('participant_count') <small>{{ $message }}</small> @enderror
                        </label>
                        <label class="field-wide">
                            <span>Catatan</span>
                            <textarea name="note">{{ old('note') }}</textarea>
                            @error('note') <small>{{ $message }}</small> @enderror
                        </label>
                        <button type="submit" class="btn btn--gold">Kirim Pendaftaran</button>
                    </form>
                @else
                    <a href="{{ $agenda->registrationCtaUrl() }}" class="btn btn--gold" target="_blank" rel="noopener noreferrer">Daftar Sekarang</a>
                    @if ($agenda->registration_type === 'form')
                        <p class="agenda-registration-note">Form internal tidak tampil karena pendaftaran belum dibuka.</p>
                    @endif
                @endif
            </aside>
        </div>
    </section>

    @if ($relatedAgendas->isNotEmpty())
        <section class="white-band section-pad">
            <div class="section-shell">
                <div class="section-kicker">Agenda Terkait</div>
                <h2 class="section-title">Kegiatan lain yang mungkin cocok.</h2>
                <div class="agenda-grid agenda-grid--related">
                    @foreach ($relatedAgendas as $agenda)
                        @include('public.agenda.partials.card', ['agenda' => $agenda, 'compact' => true])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
