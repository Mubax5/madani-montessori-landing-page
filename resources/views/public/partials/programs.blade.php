@php
    $programList = $programs->take($limit ?? $programs->count());
    $isPreview = filled($limit);
@endphp

<section class="{{ $isPreview ? 'white-band' : 'cream-band' }} section-pad">
    <div class="section-shell">
        <div class="section-kicker">Program Sekolah</div>
        <h2 class="section-title">{{ $isPreview ? 'Jenjang belajar yang bertahap dan personal.' : 'Roadmap program dari eksplorasi awal sampai siap transisi.' }}</h2>
        <p class="section-lead">
            Setiap jenjang dirancang untuk memberi ruang anak bergerak, mencoba, berlatih, dan tumbuh dalam adab harian.
        </p>

        <div class="program-roadmap">
            @foreach ($programList as $program)
                @php
                    $code = match ($program->program_type) {
                        'kb' => 'KB',
                        'tk_a' => 'TK A',
                        'tk_b' => 'TK B',
                        'tk_c' => 'TK C',
                        default => strtoupper(str_replace('_', ' ', $program->program_type)),
                    };
                @endphp
                <article class="program-roadmap__item">
                    <div class="program-code">{{ $code }}</div>
                    <div class="program-roadmap__content">
                        <h3>{{ $program->name }}</h3>
                        <p>{{ $program->description }}</p>
                        <div class="program-meta">
                            <span>{{ $program->age_range }}</span>
                            <span>{{ $program->duration }}</span>
                            <span>{{ strtoupper(str_replace('_', ' ', $program->category)) }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @unless ($isPreview)
            <div class="learning-mode-strip">
                <article class="learning-mode">
                    <strong>Reguler</strong>
                    <span>Ritme harian ringkas untuk stimulasi inti, adab, dan aktivitas Montessori.</span>
                </article>
                <article class="learning-mode">
                    <strong>Half-day</strong>
                    <span>Durasi lebih panjang untuk anak yang mulai siap dengan proyek dan rutinitas kelas.</span>
                </article>
                <article class="learning-mode">
                    <strong>Full-day</strong>
                    <span>Pendampingan terstruktur dengan jeda istirahat, makan, dan pembiasaan mandiri.</span>
                </article>
            </div>
        @endunless

        <div class="program-journey__footer">
            @if ($isPreview)
                <a href="{{ route('program-sekolah') }}" class="btn btn--outline-dark">Detail Program Sekolah</a>
            @else
                <a href="{{ $whatsappUrl }}" class="btn btn--gold">Konsultasi Program</a>
            @endif
        </div>
    </div>
</section>
