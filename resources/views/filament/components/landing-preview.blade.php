@php
    $previewId = 'madani-preview-' . \Illuminate\Support\Str::uuid();
    $variant = $variant ?? 'modal';
@endphp

<div class="madani-preview madani-preview--{{ $variant }}" id="{{ $previewId }}">
    <div class="madani-preview__bar">
        <div>
            <span>Live preview</span>
            <strong>{{ $title }}</strong>
        </div>
        <a href="{{ $url }}" target="_blank" rel="noreferrer">Buka tab baru</a>
    </div>

    <div class="madani-preview__devices">
        <section class="madani-preview__device madani-preview__device--desktop">
            <header>Desktop</header>
            <iframe src="{{ $url }}" title="Preview desktop {{ $title }}" loading="lazy"></iframe>
        </section>
        <section class="madani-preview__device madani-preview__device--tablet">
            <header>Tablet</header>
            <iframe src="{{ $url }}" title="Preview tablet {{ $title }}" loading="lazy"></iframe>
        </section>
        <section class="madani-preview__device madani-preview__device--mobile">
            <header>Mobile</header>
            <iframe src="{{ $url }}" title="Preview mobile {{ $title }}" loading="lazy"></iframe>
        </section>
    </div>
</div>
