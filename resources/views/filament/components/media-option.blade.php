<div class="madani-media-option">
    <img src="{{ $media->url }}" alt="{{ $media->alt_text }}">
    <span>
        <strong>{{ $media->alt_text ?: $media->file_name }}</strong>
        <small>{{ $media->file_name }}</small>
    </span>
</div>
