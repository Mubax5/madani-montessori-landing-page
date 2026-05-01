@php
    $mapsEmbedUrl = $settings->get('maps_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d652.1473505745165!2d106.62805344122339!3d-6.3529058163396215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e3874490ef33%3A0x64684f18b41459a0!2sNational%20Education%20Centre!5e0!3m2!1sen!2sid!4v1777585749144!5m2!1sen!2sid');
@endphp

<section class="cream-band section-pad">
    <div class="section-shell location-layout">
        <div class="map-panel map-panel--embed">
            <iframe
                src="{{ $mapsEmbedUrl }}"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Lokasi Madani Montessori Islamic School"
            ></iframe>
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
                <a href="{{ $settings->get('maps_url') }}" target="_blank" rel="noreferrer" class="btn btn--outline-dark">Buka Lokasi</a>
            </div>
        </div>
    </div>
</section>
