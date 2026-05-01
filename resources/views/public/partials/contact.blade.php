@php
    $mapsEmbedUrl = $settings->get('maps_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d652.1473505745165!2d106.62805344122339!3d-6.3529058163396215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e3874490ef33%3A0x64684f18b41459a0!2sNational%20Education%20Centre!5e0!3m2!1sen!2sid!4v1777585749144!5m2!1sen!2sid');
@endphp

<aside class="contact-card">
    <div class="section-kicker">Kontak</div>
    <h2>Hubungi admin pendaftaran.</h2>
    <p>{{ $sections->get('contact')?->subheading ?: 'Isi form pendaftaran atau hubungi WhatsApp agar admin dapat membantu memilih program yang sesuai.' }}</p>

    <div class="contact-list">
        <div>
            <strong>Alamat</strong><br>
            {{ $settings->get('address') }}
        </div>
        <a href="{{ $whatsappUrl }}">WhatsApp: {{ $settings->get('whatsapp_number') }}</a>
        <a href="{{ $settings->get('maps_url') }}" target="_blank" rel="noreferrer">Buka lokasi di Google Maps</a>
    </div>

    <div class="btn-row mt-6">
        <a href="{{ $whatsappUrl }}" class="btn btn--whatsapp">Chat WhatsApp</a>
        <a href="{{ $settings->get('maps_url') }}" target="_blank" rel="noreferrer" class="btn btn--outline-dark">Buka Maps</a>
    </div>

    <div class="map-panel map-panel--embed mt-7">
        <iframe
            src="{{ $mapsEmbedUrl }}"
            allowfullscreen
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi Madani Montessori Islamic School"
        ></iframe>
        <div class="map-panel__content">
            <strong>Madani Montessori Islamic School</strong>
            <span>{{ $settings->get('address') }}</span>
        </div>
    </div>
</aside>
