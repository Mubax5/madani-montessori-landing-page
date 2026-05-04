@props(['label' => null])

<div {{ $attributes->class('image-placeholder') }} role="img" aria-label="{{ $label ?: 'Visual Madani Montessori' }}">
    <span class="image-placeholder__mark" aria-hidden="true"></span>
    @if ($label)
        <span class="image-placeholder__label">{{ $label }}</span>
    @endif
</div>
