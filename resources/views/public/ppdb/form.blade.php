@extends('layouts.public')

@section('content')
    <section class="inner-hero elegant-blue-texture">
        <div class="inner-hero__grid">
            <div class="inner-hero__content">
                <div class="hero-eyebrow">Daftar PPDB</div>
                <h1 class="text-balance">Form pendaftaran murid baru.</h1>
                <p>Data dikirim ke sistem Madani Nidham dan diproses admin sekolah.</p>
            </div>
            <aside class="inner-hero__panel">
                <strong>Siapkan dokumen</strong>
                <p>Akta, KK, pas foto, dan KTP orang tua bisa diunggah sekarang atau menyusul sesuai arahan admin.</p>
            </aside>
        </div>
    </section>

    <section class="cream-band section-pad">
        <div class="section-shell">
            <form id="ppdb-form" class="lead-form" data-api-url="{{ $apiUrl }}">
                <div>
                    <div class="section-kicker">PPDB Online</div>
                    <h2 class="section-title">Kirim data calon siswa.</h2>
                    <p class="section-lead">Setelah berhasil, nomor pendaftaran akan tampil di halaman ini.</p>
                </div>

                <div id="ppdb-status" class="success-message" role="status" hidden></div>

                <div class="form-grid">
                    <label>
                        <span>Nama Anak</span>
                        <input name="childName" required minlength="2">
                    </label>
                    <label>
                        <span>Tanggal Lahir Anak</span>
                        <input type="date" name="childBirthDate" required>
                    </label>
                    <label>
                        <span>Gender</span>
                        <select name="childGender" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </label>
                    <label>
                        <span>Program Tujuan</span>
                        <select name="programApplied" required>
                            <option value="KB">KB</option>
                            <option value="TKA">TK A</option>
                            <option value="TKB">TK B</option>
                            <option value="TKC">TK C</option>
                            <option value="Bimbel">Bimbel</option>
                        </select>
                    </label>
                    <label>
                        <span>Nama Orang Tua</span>
                        <input name="parentName" autocomplete="name" required minlength="3">
                    </label>
                    <label>
                        <span>Nomor WhatsApp</span>
                        <input name="parentPhone" inputmode="tel" placeholder="08xxxxxxxxxx" required>
                    </label>
                    <label>
                        <span>Email Orang Tua</span>
                        <input type="email" name="parentEmail" required>
                    </label>
                    <label class="field-wide">
                        <span>Alamat</span>
                        <textarea name="address" rows="4" required></textarea>
                    </label>

                    @foreach ($documentRequirements as $requirement)
                        <label>
                            <span>{{ $requirement }}</span>
                            <input type="hidden" name="documentNames[]" value="{{ $requirement }}">
                            <input type="file" name="documents[]" accept="image/*,.pdf">
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="btn btn--gold">Kirim Pendaftaran</button>
            </form>
        </div>
    </section>

    <script>
        document.getElementById('ppdb-form')?.addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.currentTarget;
            const status = document.getElementById('ppdb-status');
            const button = form.querySelector('button[type="submit"]');
            const apiUrl = form.dataset.apiUrl;

            status.hidden = false;
            status.textContent = 'Mengirim data pendaftaran...';
            button.disabled = true;

            try {
                const response = await fetch(`${apiUrl}/registrations`, {
                    method: 'POST',
                    headers: { Accept: 'application/json' },
                    body: new FormData(form),
                });
                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Pendaftaran gagal dikirim.');
                }

                status.textContent = `Pendaftaran berhasil. Nomor pendaftaran: ${payload.data.registrationNumber}. Simpan nomor ini untuk cek status.`;
                form.reset();
            } catch (error) {
                status.textContent = error.message || 'Pendaftaran gagal dikirim.';
            } finally {
                button.disabled = false;
            }
        });
    </script>
@endsection
