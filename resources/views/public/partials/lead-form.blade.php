<form action="{{ route('leads.store') }}" method="POST" class="lead-form">
    @csrf
    <input type="hidden" name="source_page" value="{{ $page->slug }}">

    <div>
        <div class="section-kicker">Pendaftaran</div>
        <h2 class="section-title">Kirim data calon siswa.</h2>
        <p class="section-lead">Data ini membantu admin menghubungi orang tua dengan informasi program yang lebih sesuai.</p>
    </div>

    @if (session('success'))
        <div class="success-message" role="status">{{ session('success') }}</div>
    @endif

    <div class="form-grid">
        <label>
            <span>Nama Orang Tua</span>
            <input name="parent_name" value="{{ old('parent_name') }}" autocomplete="name" required minlength="3">
            @error('parent_name') <small>{{ $message }}</small> @enderror
        </label>

        <label>
            <span>Nama Anak</span>
            <input name="child_name" value="{{ old('child_name') }}" required minlength="2">
            @error('child_name') <small>{{ $message }}</small> @enderror
        </label>

        <label>
            <span>Usia Anak</span>
            <input type="number" name="child_age" min="2" max="12" value="{{ old('child_age') }}" required>
            @error('child_age') <small>{{ $message }}</small> @enderror
        </label>

        <label>
            <span>Pilih Program</span>
            <select name="selected_program" required>
                @foreach (['KB', 'TK A', 'TK B', 'TK C', 'Bimbel', 'Training & Parenting'] as $option)
                    <option value="{{ $option }}" @selected(old('selected_program') === $option)>{{ $option }}</option>
                @endforeach
            </select>
            @error('selected_program') <small>{{ $message }}</small> @enderror
        </label>

        <label>
            <span>Nomor WhatsApp</span>
            <input name="whatsapp_number" value="{{ old('whatsapp_number') }}" placeholder="08xxxxxxxxxx" inputmode="tel" autocomplete="tel" required>
            @error('whatsapp_number') <small>{{ $message }}</small> @enderror
        </label>

        <label class="field-wide">
            <span>Catatan</span>
            <textarea name="note" rows="4" maxlength="1000" placeholder="Ceritakan kebutuhan anak atau jadwal yang diinginkan.">{{ old('note') }}</textarea>
            @error('note') <small>{{ $message }}</small> @enderror
        </label>
    </div>

    <button type="submit" class="btn btn--gold">Kirim Pendaftaran</button>
</form>
