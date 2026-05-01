@if ($media)
    <div class="madani-media-thumb">
        <img src="{{ $media->url }}" alt="{{ $media->alt_text }}">
        <div>
            <strong>{{ $media->alt_text ?: 'Media terpilih' }}</strong>
            <span>{{ $media->file_name }}</span>
        </div>
    </div>
@else
    <div class="madani-media-thumb madani-media-thumb--empty">
        Belum ada gambar terpilih.
    </div>
@endif
