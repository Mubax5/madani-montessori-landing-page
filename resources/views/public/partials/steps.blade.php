<section class="white-band section-pad">
    <div class="section-shell">
        <div class="section-kicker">Alur Pendaftaran</div>
        <h2 class="section-title">Empat langkah singkat sebelum anak mulai belajar.</h2>
        <div class="stepper">
            @foreach ([
                ['Konsultasi awal', 'Diskusikan usia, kebutuhan anak, dan program yang paling sesuai.'],
                ['Pilih program', 'Admin membantu menjelaskan pilihan KB, TK, bimbel, atau training.'],
                ['Observasi anak', 'Guru melihat kesiapan anak agar kelas dan ritme belajar lebih tepat.'],
                ['Konfirmasi jadwal', 'Orang tua menerima arahan jadwal, administrasi, dan langkah berikutnya.'],
            ] as $step)
                <article class="step-card">
                    <span class="step-number">{{ $loop->iteration }}</span>
                    <div>
                        <h3>{{ $step[0] }}</h3>
                        <p>{{ $step[1] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
