<section class="{{ ($compact ?? false) ? 'cream-band' : 'white-band' }} section-pad">
    <div class="section-shell">
        <div class="section-kicker">Galeri</div>
        <h2 class="section-title">{{ ($compact ?? false) ? 'Cuplikan ruang belajar dan kegiatan anak.' : 'Momen sekolah dalam komposisi galeri editorial.' }}</h2>
        <p class="section-lead">
            Foto tetap berasal dari CMS galeri, dengan frame rounded dan masonry layout agar tidak terasa seperti grid standar.
        </p>

        @unless ($compact ?? false)
            <div class="gallery-filter" data-gallery-filter>
                <button type="button" data-filter="all" class="is-active">Semua</button>
                <button type="button" data-filter="sekolah">Sekolah</button>
                <button type="button" data-filter="bimbel">Bimbel</button>
                <button type="button" data-filter="event">Event</button>
                <button type="button" data-filter="parenting">Parenting</button>
            </div>
        @endunless

        <div class="gallery-grid" data-gallery-grid>
            @forelse ($galleryItems as $item)
                <figure class="gallery-card" data-category="{{ $item->category }}">
                    @if ($item->media)
                        <img src="{{ $item->media->url }}" alt="{{ $item->media->alt_text ?: $item->title }}">
                        <button type="button" class="gallery-lightbox-trigger" data-lightbox-src="{{ $item->media->url }}" data-lightbox-title="{{ $item->title }}">
                            <span class="sr-only">Buka foto {{ $item->title }}</span>
                        </button>
                    @else
                        <div class="image-placeholder">Foto Kegiatan</div>
                    @endif
                    <figcaption>
                        <strong>{{ $item->title }}</strong>
                        <span>{{ ucfirst($item->category) }}</span>
                    </figcaption>
                </figure>
            @empty
                @foreach (['Sekolah', 'Bimbel', 'Event'] as $placeholder)
                    <figure class="gallery-card" data-category="{{ strtolower($placeholder) }}">
                        <div class="image-placeholder">{{ $placeholder }}</div>
                        <figcaption>
                            <strong>{{ $placeholder }}</strong>
                            <span>CMS</span>
                        </figcaption>
                    </figure>
                @endforeach
            @endforelse
        </div>

        @if ($compact ?? false)
            <div class="program-journey__footer">
                <a href="{{ route('galeri') }}" class="btn btn--outline-dark">Lihat Galeri</a>
            </div>
        @endif
    </div>

    @unless ($compact ?? false)
        <div class="lightbox" data-lightbox aria-modal="true" role="dialog" aria-label="Preview foto galeri">
            <div class="lightbox__dialog">
                <button type="button" class="lightbox__close" data-lightbox-close aria-label="Tutup preview">&times;</button>
                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==" alt="" data-lightbox-image>
                <div class="lightbox__caption" data-lightbox-caption></div>
            </div>
        </div>
    @endunless
</section>
