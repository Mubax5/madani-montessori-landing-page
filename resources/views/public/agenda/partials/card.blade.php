@php
    $compact = $compact ?? false;
    $coverUrl = $agenda->cover_image_final_url;
    $category = $agenda->category;
    $registerUrl = $agenda->registration_type === 'form'
        ? route('agenda.show', $agenda->slug) . '#agenda-registration'
        : $agenda->registrationCtaUrl();
    $isExternal = $agenda->registration_type !== 'form';
    $badgeStyle = $category?->color ? '--badge-color: ' . $category->color : null;
@endphp

<article @class(['agenda-card', 'agenda-card--compact' => $compact]) data-agenda-category="{{ $category?->slug }}">
    <a href="{{ route('agenda.show', $agenda->slug) }}" class="agenda-card__media" aria-label="Detail {{ $agenda->title }}">
        @if ($coverUrl)
            <img src="{{ $coverUrl }}" alt="Cover {{ $agenda->title }}" loading="lazy">
        @else
            <x-image-placeholder class="agenda-image-placeholder" label="Agenda Madani" />
        @endif
    </a>

    <div class="agenda-card__body">
        <div class="agenda-card__topline">
            <span class="agenda-badge" @if ($badgeStyle) style="{{ $badgeStyle }}" @endif>{{ $category?->name ?? 'Agenda' }}</span>
            <span class="agenda-status">{{ $agenda->registrationStatusLabel() }}</span>
        </div>

        <h3><a href="{{ route('agenda.show', $agenda->slug) }}">{{ $agenda->title }}</a></h3>

        @if (! $compact && $agenda->excerpt)
            <p>{{ $agenda->excerpt }}</p>
        @endif

        <dl class="agenda-meta-list">
            <div>
                <dt>Tanggal</dt>
                <dd>{{ $agenda->date_label }}</dd>
            </div>
            <div>
                <dt>Jam</dt>
                <dd>{{ $agenda->time_label }}</dd>
            </div>
            <div>
                <dt>Lokasi</dt>
                <dd>{{ $agenda->location_name ?: 'Madani Montessori Islamic School' }}</dd>
            </div>
            @if ($agenda->quota)
                <div>
                    <dt>Kuota</dt>
                    <dd>{{ $agenda->quota }} peserta</dd>
                </div>
            @endif
        </dl>

        <div class="agenda-card__actions">
            <a href="{{ route('agenda.show', $agenda->slug) }}" class="btn btn--outline-dark">Detail Agenda</a>
            <a href="{{ $registerUrl }}" class="btn btn--gold" @if ($isExternal) target="_blank" rel="noopener noreferrer" @endif>Daftar</a>
        </div>
    </div>
</article>
