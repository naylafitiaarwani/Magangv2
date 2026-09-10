@extends('CMS.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h4>Tambah Event</h4>
        <p class="text-muted">
            Tambahkan event atau agenda baru.
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form
                action="/admin/event/store"
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
                        value="{{ old('nama') }}"
                        placeholder="Contoh: Festival Mangga Indramayu"
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
                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                            value="{{ old('tanggal_mulai') }}"
                            required
                        >

                        @error('tanggal_mulai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="tanggal_selesai"
                            class="form-control @error('tanggal_selesai') is-invalid @enderror"
                            value="{{ old('tanggal_selesai') }}"
                        >

                        @error('tanggal_selesai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
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
                            value="{{ old('kategori') }}"
                            placeholder="Contoh: Festival"
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
                            value="{{ old('lokasi') }}"
                            placeholder="Contoh: Alun-Alun Indramayu"
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
                        value="{{ old('penyelenggara') }}"
                        placeholder="Nama penyelenggara"
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
                        placeholder="Deskripsi event..."
                    >{{ old('deskripsi') }}</textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Gambar Event
                    </label>

                    <input
                        type="file"
                        name="gambar"
                        class="form-control @error('gambar') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small class="text-muted">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </small>

                    @error('gambar')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-check mb-4">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="form-check-input"
                        id="is_active"
                        {{ old('is_active', true) ? 'checked' : '' }}
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
                        Simpan Event
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
