@php
    $featureList = $page->slug === 'home' ? $featuredPrograms->take(5) : $featuredPrograms;
@endphp

<section class="{{ $page->slug === 'program-unggulan' ? 'white-band' : 'cream-band' }} section-pad">
    <div class="section-shell">
        <div class="section-kicker">Program Unggulan</div>
        <h2 class="section-title">Kegiatan inti yang membentuk adab, fokus, dan kemandirian.</h2>
        <p class="section-lead">
            Program unggulan tetap mudah diperbarui dari CMS, sementara tampilannya dibuat seperti editorial feature list yang lebih tenang.
        </p>

        <div class="featured-list">
            @forelse ($featureList as $item)
                <article class="feature-row">
                    <span class="feature-badge">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="feature-row__body">
                        <h3>{{ $item->title }}</h3>
                        <p>{{ $item->description }}</p>
                        <div class="gold-divider" aria-hidden="true"></div>
                    </div>
                </article>
            @empty
                <article class="feature-row">
                    <span class="feature-badge">01</span>
                    <div class="feature-row__body">
                        <h3>Tahfidz, Doa dan Hadits</h3>
                        <p>Admin dapat menambahkan program unggulan melalui CMS.</p>
                        <div class="gold-divider" aria-hidden="true"></div>
                    </div>
                </article>
            @endforelse
        </div>

        @if ($page->slug === 'home')
            <div class="program-journey__footer">
                <a href="{{ route('program-unggulan') }}" class="btn btn--outline-dark">Lihat Semua Program Unggulan</a>
            </div>
        @endif
    </div>
</section>
