@extends('layouts.public')

@section('content')
    <section class="inner-hero elegant-blue-texture">
        <div class="inner-hero__grid">
            <div class="inner-hero__content">
                <div class="hero-eyebrow">PPDB Online</div>
                <h1 class="text-balance">Pendaftaran murid baru Madani Montessori.</h1>
                <p>Isi form online, simpan nomor pendaftaran, lalu cek status seleksi kapan pun dari halaman ini.</p>
                <div class="btn-row mt-6">
                    <a href="{{ route('ppdb.daftar') }}" class="btn btn--gold">Daftar PPDB Online</a>
                    <a href="{{ route('ppdb.cek-status') }}" class="btn btn--outline">Cek Status</a>
                </div>
            </div>
            <aside class="inner-hero__panel">
                <strong>KB - TK C</strong>
                <p>Data masuk langsung ke dashboard Madani Nidham untuk diproses admin sekolah.</p>
                <div class="pill-row mt-5">
                    <span class="pill">Form online</span>
                    <span class="pill">Upload dokumen</span>
                    <span class="pill">Status real-time</span>
                </div>
            </aside>
        </div>
    </section>

    <section class="cream-band section-pad">
        <div class="section-shell">
            <div class="section-kicker">Alur PPDB</div>
            <h2 class="section-title">Tiga langkah singkat.</h2>
            <div class="stepper mt-8">
                <article class="step-card">
                    <span class="step-number">1</span>
                    <h3>Isi form</h3>
                    <p>Lengkapi data anak, orang tua, program tujuan, alamat, dan dokumen awal.</p>
                </article>
                <article class="step-card">
                    <span class="step-number">2</span>
                    <h3>Simpan nomor</h3>
                    <p>Nomor pendaftaran digunakan untuk cek status dan komunikasi dengan admin.</p>
                </article>
                <article class="step-card">
                    <span class="step-number">3</span>
                    <h3>Review admin</h3>
                    <p>Admin memproses data dari dashboard dan menghubungi orang tua untuk langkah berikutnya.</p>
                </article>
            </div>
        </div>
    </section>
@endsection
