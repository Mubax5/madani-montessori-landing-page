<section class="cream-band section-pad">
    <div class="section-shell">
        @php
            $topics = [
                'Montessori dasar',
                'Rutinitas anak',
                'Komunikasi positif',
                'Practical life',
                'Adab harian',
                'Manajemen kelas',
            ];
        @endphp

        <div class="section-kicker">Training & Parenting</div>
        <h2 class="section-title">Workshop premium untuk guru dan orang tua yang ingin lebih terarah.</h2>
        <p class="section-lead">
            Materi dibuat praktis: bisa dibawa ke kelas, rumah, dan rutinitas harian anak.
        </p>

        <div class="training-board">
            <aside class="training-topics-panel" aria-labelledby="training-topics-title">
                <span class="training-panel-eyebrow">Topik populer</span>
                <h3 id="training-topics-title">Materi singkat, aplikatif, dan mudah dibawa ke rutinitas anak.</h3>

                <div class="training-topic-grid" aria-label="Topik populer">
                    @foreach ($topics as $topic)
                        <span>{{ $topic }}</span>
                    @endforeach
                </div>

                <div class="training-format-list">
                    <div>
                        <span>01</span>
                        <strong>Workshop guru</strong>
                        <small>Strategi kelas, observasi anak, dan practical life.</small>
                    </div>
                    <div>
                        <span>02</span>
                        <strong>Parenting class</strong>
                        <small>Pendampingan rumah, adab harian, dan komunikasi positif.</small>
                    </div>
                </div>
            </aside>

            <div class="training-schedule-panel" aria-labelledby="training-schedule-title">
                <div class="training-schedule-head">
                    <div>
                        <span class="training-panel-eyebrow">Jadwal terdekat</span>
                        <h3 id="training-schedule-title">Agenda Training &amp; Parenting</h3>
                    </div>
                    <a href="{{ \App\Support\SiteContent::whatsappUrl('minat_training_parenting') }}" class="btn btn--gold training-schedule-cta">Minta Jadwal</a>
                </div>

                <div class="training-agenda-list">
                @forelse ($trainingEvents as $event)
                    @php
                        $target = ucfirst(str_replace('_', ' ', $event->target_audience));
                    @endphp
                    <article class="training-agenda-card">
                        <time class="training-agenda-date" @if ($event->event_date) datetime="{{ $event->event_date->toDateString() }}" @endif>
                            <span>{{ $event->event_date?->format('d') ?? '--' }}</span>
                            <small>{{ $event->event_date?->format('M Y') ?? 'Segera' }}</small>
                        </time>
                        <div class="training-agenda-body">
                            <div class="training-agenda-meta">
                                <span>{{ $target }}</span>
                                @if ($event->event_time)
                                    <span>{{ $event->event_time }}</span>
                                @endif
                            </div>
                            <h4>{{ $event->title }}</h4>
                            @if ($event->topic)
                                <p class="training-agenda-topic">{{ $event->topic }}</p>
                            @endif
                            <p>{{ $event->description }}</p>
                        </div>
                        <a href="{{ \App\Support\SiteContent::whatsappUrl('minat_training_parenting') }}" class="training-agenda-link" aria-label="Minta jadwal untuk {{ $event->title }}">
                            <span aria-hidden="true">-&gt;</span>
                        </a>
                    </article>
                @empty
                    <article class="training-empty-card">
                        <div class="training-empty-mark">
                            <span>Segera</span>
                        </div>
                        <div>
                            <span class="training-panel-eyebrow">Guru &amp; Orang Tua</span>
                            <h4>Jadwal sedang disusun</h4>
                            <p>Hubungi admin untuk menerima info batch workshop berikutnya atau request sesi khusus sekolah dan komunitas.</p>
                        </div>
                        <a href="{{ \App\Support\SiteContent::whatsappUrl('minat_training_parenting') }}" class="training-agenda-link" aria-label="Minta jadwal Training & Parenting">
                            <span aria-hidden="true">-&gt;</span>
                        </a>
                    </article>
                @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
