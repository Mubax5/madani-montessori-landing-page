<section class="{{ $page->slug === 'bimbel' ? 'cream-band' : 'white-band' }} section-pad">
    <div class="section-shell">
        <div class="section-kicker">Bimbel</div>
        <h2 class="section-title">Pendampingan belajar yang tenang, bertahap, dan mudah diikuti anak.</h2>
        <p class="section-lead">
            Paket bimbel tampil seperti layanan premium, namun tetap mengambil nama, target, detail, dan CTA dari CMS.
        </p>

        <div class="bimbel-layout">
            @forelse ($bimbelPackages as $package)
                <article @class(['package-card', 'package-card--featured' => $loop->first])>
                    <span class="package-label">Paket {{ chr(64 + $loop->iteration) }}</span>
                    <h3>{{ $package->name }}</h3>
                    <p>{{ $package->description }}</p>
                    <div class="program-meta">
                        <span>{{ $package->target }}</span>
                    </div>
                    <ul>
                        @foreach ($package->items as $item)
                            <li>{{ $item->title }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ \App\Support\SiteContent::whatsappUrl('minat_bimbel') }}" class="btn {{ $loop->first ? 'btn--gold' : 'btn--outline-dark' }}">
                        {{ $package->cta_label ?: 'Tanya paket bimbel' }}
                    </a>
                </article>
            @empty
                <article class="package-card package-card--featured">
                    <span class="package-label">Paket A</span>
                    <h3>Calistung dan Mengaji</h3>
                    <p>Admin dapat menambahkan paket bimbel melalui CMS.</p>
                    <a href="{{ \App\Support\SiteContent::whatsappUrl('minat_bimbel') }}" class="btn btn--gold">Tanya paket bimbel</a>
                </article>
            @endforelse
        </div>

        <div class="benefit-rail" aria-label="Benefit bimbel">
            <article class="benefit-card">
                <span class="benefit-dot">1</span>
                <strong>Asesmen awal</strong>
                <p>Guru melihat kebutuhan anak sebelum menentukan ritme belajar.</p>
            </article>
            <article class="benefit-card">
                <span class="benefit-dot">2</span>
                <strong>Ritme fleksibel</strong>
                <p>Jadwal disesuaikan dengan kesiapan dan fokus anak.</p>
            </article>
            <article class="benefit-card">
                <span class="benefit-dot">3</span>
                <strong>Laporan progres</strong>
                <p>Orang tua mendapat arahan praktis untuk latihan di rumah.</p>
            </article>
        </div>

        @if ($page->slug === 'home')
            <div class="program-journey__footer">
                <a href="{{ route('bimbel') }}" class="btn btn--outline-dark">Lihat Bimbel</a>
            </div>
        @endif
    </div>
</section>
