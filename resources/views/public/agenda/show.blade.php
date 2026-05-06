@extends('layouts.public')

@php
    $category = $agenda->category;
    $badgeStyle = $category?->color ? '--badge-color: ' . $category->color : null;
    $isFormRegistration = $agenda->registration_type === 'form';
    $ctaUrl = $isFormRegistration ? '#agenda-registration' : $agenda->registrationCtaUrl();
    $ctaExternal = ! $isFormRegistration;
    $ctaLabel = $agenda->registration_type === 'external_url' ? 'Daftar Sekarang' : ($isFormRegistration ? 'Isi Pendaftaran' : 'Daftar via WhatsApp');
@endphp

@section('content')
    <section class="agenda-detail-hero agenda-detail-hero--compact elegant-blue-texture">
        <div class="agenda-detail-hero__grid agenda-detail-hero__grid--compact">
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

            <div class="agenda-detail-hero__cta">
                <p>{{ $agenda->registrationStatusLabel() }}</p>
                <a href="{{ $ctaUrl }}" class="btn btn--gold" @if ($ctaExternal) target="_blank" rel="noopener noreferrer" @endif>{{ $ctaLabel }}</a>
            </div>
        </div>
    </section>

    <section class="cream-band section-pad">
        <div class="section-shell agenda-detail-layout">
            <article class="agenda-detail-main">
                <div class="agenda-detail-cover">
                    @if ($agenda->cover_image_final_url)
                        <img src="{{ $agenda->cover_image_final_url }}" alt="Cover {{ $agenda->title }}">
                    @else
                        <x-image-placeholder class="agenda-image-placeholder" label="Agenda Madani" />
                    @endif
                </div>

                <div class="agenda-detail-copy">
                    <div class="section-kicker">Informasi Agenda</div>
                    <h2 class="section-title">Rincian kegiatan.</h2>

                    @if ($agenda->excerpt)
                        <p class="agenda-detail-lead">{{ $agenda->excerpt }}</p>
                    @endif

                    <div class="agenda-richtext">
                        @if ($agenda->description)
                            {!! \App\Support\HtmlSanitizer::clean($agenda->description) !!}
                        @else
                            <p>Informasi lengkap akan tersedia menjelang jadwal kegiatan.</p>
                        @endif
                    </div>
                </div>
            </article>

            <aside class="agenda-registration-panel" id="agenda-registration">
                <div class="section-kicker">Ringkasan</div>
                <h2>{{ $agenda->registrationStatusLabel() }}</h2>

                <dl class="agenda-detail-facts agenda-detail-facts--stacked">
                    <div><span>Tanggal</span><strong>{{ $agenda->date_label }}</strong></div>
                    <div><span>Jam</span><strong>{{ $agenda->time_label }}</strong></div>
                    <div><span>Lokasi</span><strong>{{ $agenda->location_name ?: 'Madani Montessori Islamic School' }}</strong></div>
                    @if ($agenda->target_audience)
                        <div><span>Target Peserta</span><strong>{{ $agenda->target_audience }}</strong></div>
                    @endif
                    @if ($agenda->quota)
                        <div><span>Kuota</span><strong>{{ $agenda->quota }} peserta</strong></div>
                    @endif
                    <div><span>Biaya</span><strong>{{ $agenda->formatted_price }}</strong></div>
                </dl>

                @if ($agenda->maps_url)
                    <a href="{{ $agenda->maps_url }}" class="btn btn--outline-dark agenda-map-link" target="_blank" rel="noopener noreferrer">Buka Lokasi</a>
                @endif

                @if (session('success'))
                    <div class="success-message" role="status">{{ session('success') }}</div>
                @endif

                @error('agenda_registration')
                    <small>{{ $message }}</small>
                @enderror

                @if ($isFormRegistration && $agenda->isRegistrationOpen())
                    <form action="{{ route('agenda.registrations.store', $agenda->slug) }}" method="POST" class="agenda-registration-form">
                        @csrf
                        <div class="honeypot-field" aria-hidden="true">
                            <label>Website
                                <input type="text" name="{{ \App\Support\PublicFormAbuseGuard::honeypotField() }}" tabindex="-1" autocomplete="off">
                            </label>
                        </div>
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
                    <a href="{{ $agenda->registrationCtaUrl() }}" class="btn btn--gold agenda-registration-panel__cta" target="_blank" rel="noopener noreferrer">{{ $agenda->registration_type === 'external_url' ? 'Daftar Sekarang' : 'Daftar via WhatsApp' }}</a>
                    @if ($isFormRegistration)
                        <p class="agenda-registration-note">Pendaftaran online belum dibuka untuk jadwal ini.</p>
                    @endif
                @endif
            </aside>
        </div>
    </section>

    @if ($relatedAgendas->isNotEmpty())
        <section class="white-band section-pad section-pad--tight">
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
