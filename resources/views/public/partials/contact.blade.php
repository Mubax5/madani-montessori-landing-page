@php
    $rawMapsUrl = trim((string) $settings->get('maps_url'));
    $rawMapsEmbedUrl = trim((string) $settings->get('maps_embed_url'));
    $mapsUrl = filter_var($rawMapsUrl, FILTER_VALIDATE_URL) ? $rawMapsUrl : null;
    $mapsEmbedUrl = filter_var($rawMapsEmbedUrl, FILTER_VALIDATE_URL) ? $rawMapsEmbedUrl : null;
    $whatsappNumber = preg_replace('/\D+/', '', $settings->get('whatsapp_number', '6282123576275')) ?: '6282123576275';
    $whatsappDisplay = filled($settings->get('whatsapp_display')) ? $settings->get('whatsapp_display') : '+62 821-2357-6275';
    $email = filled($settings->get('email')) ? $settings->get('email') : 'madanimontessori@gmail.com';
    $instagramHandle = filled($settings->get('instagram_handle')) ? $settings->get('instagram_handle') : '@madanimontessori';
    $instagramUrl = filled($settings->get('instagram_url')) ? $settings->get('instagram_url') : 'https://www.instagram.com/madanimontessori';
@endphp

<aside class="contact-card">
    <div class="section-kicker">Kontak</div>
    <h2>Hubungi Madani Montessori.</h2>
    <p>{{ $sections->get('contact')?->subheading ?: 'Isi form pendaftaran atau hubungi WhatsApp agar kami dapat membantu memilih program yang sesuai.' }}</p>

    <div class="contact-list">
        <div>
            <strong>Alamat</strong><br>
            {{ $settings->get('address') }}
        </div>
        <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener noreferrer">WhatsApp: {{ $whatsappDisplay }}</a>
        <a href="mailto:{{ $email }}">Email: {{ $email }}</a>
        <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer">Instagram: {{ $instagramHandle }}</a>
        @if ($mapsUrl)
            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer">Buka lokasi di Google Maps</a>
        @endif
    </div>

    <div class="btn-row mt-6">
        <a href="{{ $whatsappUrl }}" class="btn btn--whatsapp">Chat WhatsApp</a>
        @if ($mapsUrl)
            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn--outline-dark">Buka Lokasi</a>
        @endif
    </div>

    <div @class(['map-panel', 'map-panel--embed' => filled($mapsEmbedUrl), 'mt-7'])>
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
            <strong>Madani Montessori Islamic School</strong>
            <span>{{ $settings->get('address') }}</span>
        </div>
    </div>
</aside>
