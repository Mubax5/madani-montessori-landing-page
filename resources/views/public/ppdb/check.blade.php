@extends('layouts.public')

@section('content')
    <section class="inner-hero elegant-blue-texture">
        <div class="inner-hero__grid">
            <div class="inner-hero__content">
                <div class="hero-eyebrow">Cek Status PPDB</div>
                <h1 class="text-balance">Pantau status pendaftaran.</h1>
                <p>Masukkan nomor pendaftaran seperti REG-2026-001.</p>
            </div>
            <aside class="inner-hero__panel">
                <strong>Status real-time</strong>
                <p>Data status diambil dari API Madani Nidham.</p>
            </aside>
        </div>
    </section>

    <section class="cream-band section-pad">
        <div class="section-shell section-shell--narrow">
            <form id="ppdb-check-form" class="lead-form" data-api-url="{{ $apiUrl }}">
                <div>
                    <div class="section-kicker">Nomor pendaftaran</div>
                    <h2 class="section-title">Cek status.</h2>
                    <p class="section-lead">Gunakan nomor yang tampil setelah submit form pendaftaran.</p>
                </div>

                <label>
                    <span>Nomor Pendaftaran</span>
                    <input name="registrationNumber" placeholder="REG-2026-001" required>
                </label>

                <button type="submit" class="btn btn--gold">Cek Status</button>
                <div id="ppdb-check-result" class="success-message" role="status" hidden></div>
            </form>
        </div>
    </section>

    <script>
        document.getElementById('ppdb-check-form')?.addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.currentTarget;
            const result = document.getElementById('ppdb-check-result');
            const apiUrl = form.dataset.apiUrl;
            const number = new FormData(form).get('registrationNumber');

            result.hidden = false;
            result.textContent = 'Mengambil status...';

            try {
                const response = await fetch(`${apiUrl}/registrations/check/${encodeURIComponent(number)}`, {
                    headers: { Accept: 'application/json' },
                });
                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Status tidak ditemukan.');
                }

                result.textContent = `Status ${payload.data.registrationNumber}: ${payload.data.status}. Program: ${payload.data.programApplied}.`;
            } catch (error) {
                result.textContent = error.message || 'Status tidak ditemukan.';
            }
        });
    </script>
@endsection
