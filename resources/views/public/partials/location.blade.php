@php
    $rawMapsUrl = trim((string) $settings->get('maps_url'));
    $rawMapsEmbedUrl = trim((string) $settings->get('maps_embed_url'));
    $hasOldLocationKeyword = fn (string $url): bool => str_contains(strtolower($url), 'national+education+centre')
        || str_contains(strtolower($url), 'national%20education%20centre')
        || str_contains(strtolower($url), 'national education centre');
    $mapsUrl = filter_var($rawMapsUrl, FILTER_VALIDATE_URL) && ! $hasOldLocationKeyword($rawMapsUrl) ? $rawMapsUrl : null;
    $mapsEmbedUrl = filter_var($rawMapsEmbedUrl, FILTER_VALIDATE_URL) && ! $hasOldLocationKeyword($rawMapsEmbedUrl) ? $rawMapsEmbedUrl : null;
@endphp

<section class="cream-band section-pad">
    <div class="section-shell location-layout">
        <div @class(['map-panel', 'map-panel--embed' => filled($mapsEmbedUrl)])>
            @if ($mapsEmbedUrl)
                <iframe
                    src="{{ $mapsEmbedUrl }}"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi Madani Montessori Islamic School"
                ></iframe>
            @endif
            <div class="map-panel__content">
                <strong>Lokasi Madani</strong>
                <span>{{ $settings->get('address') }}</span>
            </div>
        </div>

        <div class="address-card">
            <div class="section-kicker">Lokasi</div>
            <h2 class="section-title">Datang ke sekolah atau konsultasi dari rumah.</h2>
            <p class="section-lead">{{ $sections->get('location')?->subheading ?: $settings->get('address') }}</p>
            <div class="btn-row mt-6">
                <a href="{{ route('kontak') }}" class="btn btn--primary">Lihat Kontak</a>
                @if ($mapsUrl)
                    <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn--outline-dark">Buka Lokasi</a>
                @endif
            </div>
        </div>
    </div>
</section>
