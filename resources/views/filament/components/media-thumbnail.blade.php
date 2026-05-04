@if ($media)
    <div class="madani-media-thumb">
        @if ($media->image_final_url)
            <img src="{{ $media->image_final_url }}" alt="{{ $media->alt_text }}">
        @else
            <span class="madani-media-placeholder madani-media-placeholder--large" aria-hidden="true"></span>
        @endif
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
