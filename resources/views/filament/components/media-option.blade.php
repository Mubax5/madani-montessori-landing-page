<div class="madani-media-option">
    @if ($media->image_final_url)
        <img src="{{ $media->image_final_url }}" alt="{{ $media->alt_text }}">
    @else
        <span class="madani-media-placeholder" aria-hidden="true"></span>
    @endif
    <span>
        <strong>{{ $media->alt_text ?: $media->file_name }}</strong>
        <small>{{ $media->file_name }}</small>
    </span>
</div>
