@extends('admin_template')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h4>Edit Event</h4>
        <p class="text-muted">
            Perbarui informasi event.
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form
                action="/admin/event/update/{{ $event->id }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Nama Event
                    </label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $event->nama) }}"
                        required
                    >

                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="tanggal_mulai"
                            class="form-control"
                            value="{{ old(
                                'tanggal_mulai',
                                $event->tanggal_mulai?->format('Y-m-d')
                            ) }}"
                            required
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="tanggal_selesai"
                            class="form-control"
                            value="{{ old(
                                'tanggal_selesai',
                                $event->tanggal_selesai?->format('Y-m-d')
                            ) }}"
                        >

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Kategori
                        </label>

                        <input
                            type="text"
                            name="kategori"
                            class="form-control"
                            value="{{ old('kategori', $event->kategori) }}"
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Lokasi
                        </label>

                        <input
                            type="text"
                            name="lokasi"
                            class="form-control"
                            value="{{ old('lokasi', $event->lokasi) }}"
                        >

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Penyelenggara
                    </label>

                    <input
                        type="text"
                        name="penyelenggara"
                        class="form-control"
                        value="{{ old('penyelenggara', $event->penyelenggara) }}"
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Deskripsi
                    </label>

                    <textarea
                        name="deskripsi"
                        rows="6"
                        class="form-control"
                    >{{ old('deskripsi', $event->deskripsi) }}</textarea>

                </div>

                @if($event->gambar)

                    <div class="mb-3">

                        <label class="form-label d-block">
                            Gambar Saat Ini
                        </label>

                        <img
                            src="{{ asset('storage/' . $event->gambar) }}"
                            alt="{{ $event->nama }}"
                            style="
                                width: 220px;
                                height: 140px;
                                object-fit: cover;
                                border-radius: 8px;
                            "
                        >

                    </div>

                @endif

                <div class="mb-3">

                    <label class="form-label">
                        Ganti Gambar
                    </label>

                    <input
                        type="file"
                        name="gambar"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti gambar.
                        Maksimal 2 MB.
                    </small>

                </div>

                <div class="form-check mb-4">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="form-check-input"
                        id="is_active"
                        {{ old('is_active', $event->is_active) ? 'checked' : '' }}
                    >

                    <label
                        class="form-check-label"
                        for="is_active"
                    >
                        Event aktif
                    </label>

                </div>

                <div class="d-flex gap-2">

                    <a
                        href="/admin/event"
                        class="btn btn-secondary"
                    >
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
