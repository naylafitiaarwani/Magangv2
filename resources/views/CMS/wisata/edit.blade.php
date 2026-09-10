@extends('admin_template')

@section('title page', 'Edit Wisata')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">
                Edit Data Wisata
            </h3>
        </div>

        <form
            action="{{ url('/admin/wisata/update/' . $wisata->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan:</strong>

                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Nama --}}
                <div class="form-group">
                    <label for="nama">
                        Nama Wisata
                    </label>

                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        class="form-control"
                        value="{{ old('nama', $wisata->nama) }}"
                        required
                    >
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label for="deskripsi">
                        Deskripsi
                    </label>

                    <textarea
                        name="deskripsi"
                        id="deskripsi"
                        class="form-control"
                        rows="4"
                    >{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                </div>

                {{-- Lokasi --}}
                <div class="form-group">
                    <label for="lokasi">
                        Lokasi
                    </label>

                    <input
                        type="text"
                        name="lokasi"
                        id="lokasi"
                        class="form-control"
                        value="{{ old('lokasi', $wisata->lokasi) }}"
                    >
                </div>

                {{-- Jam Operasional --}}
                <div class="form-group">
                    <label for="jam_operasional">
                        Jam Operasional
                    </label>

                    <input
                        type="text"
                        name="jam_operasional"
                        id="jam_operasional"
                        class="form-control"
                        value="{{ old('jam_operasional', $wisata->jam_operasional) }}"
                        placeholder="08:00 - 17:00"
                    >
                </div>

                {{-- Harga --}}
                <div class="form-group">
                    <label for="harga_mulai">
                        Harga Mulai
                    </label>

                    <input
                        type="number"
                        name="harga_mulai"
                        id="harga_mulai"
                        class="form-control"
                        value="{{ old('harga_mulai', $wisata->harga_mulai) }}"
                        min="0"
                    >
                </div>

                <div class="row">

                    {{-- Rating --}}
                    <div class="col-md-6">
                        <div class="form-group">

                            <label for="rating">
                                Rating
                            </label>

                            <input
                                type="number"
                                name="rating"
                                id="rating"
                                class="form-control"
                                value="{{ old('rating', $wisata->rating) }}"
                                min="0"
                                max="5"
                                step="0.1"
                            >

                        </div>
                    </div>

                    {{-- Jumlah ulasan --}}
                    <div class="col-md-6">
                        <div class="form-group">

                            <label for="jumlah_ulasan">
                                Jumlah Ulasan
                            </label>

                            <input
                                type="number"
                                name="jumlah_ulasan"
                                id="jumlah_ulasan"
                                class="form-control"
                                value="{{ old('jumlah_ulasan', $wisata->jumlah_ulasan) }}"
                                min="0"
                            >

                        </div>
                    </div>

                </div>

                {{-- Kategori --}}
                <div class="form-group">

                    <label for="kategori">
                        Kategori
                    </label>

                    <input
                        type="text"
                        name="kategori"
                        id="kategori"
                        class="form-control"
                        value="{{ old('kategori', $wisata->kategori) }}"
                    >

                </div>

                {{-- GAMBAR --}}
                <div class="form-group">

                    <label for="gambar">
                        Gambar Wisata
                    </label>

                    {{-- Gambar lama --}}
                    @if($wisata->gambar)

                        <div class="mb-3">

                            <p class="mb-2">
                                <strong>Gambar saat ini:</strong>
                            </p>

                            <img
                                src="{{ asset('storage/' . (str_contains($wisata->gambar, '/') ? $wisata->gambar : 'wisata/' . $wisata->gambar)) }}"
                                alt="{{ $wisata->nama }}"
                                class="img-thumbnail"
                                style="max-width:300px; max-height:200px; object-fit:cover;"
                            >

                        </div>

                    @endif

                    {{-- Upload gambar baru --}}
                    <div class="custom-file">

                        <input
                            type="file"
                            name="gambar"
                            id="gambar"
                            class="custom-file-input"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <label
                            class="custom-file-label"
                            for="gambar"
                        >
                            Pilih gambar baru
                        </label>

                    </div>

                    <small class="form-text text-muted">
                        Kosongkan jika tidak ingin mengganti gambar.
                        Format JPG, JPEG, PNG atau WEBP. Maksimal 2 MB.
                    </small>

                    {{-- Preview gambar baru --}}
                    <div class="mt-3">

                        <img
                            id="preview-gambar"
                            src=""
                            alt="Preview gambar baru"
                            style="display:none; max-width:300px; max-height:200px; object-fit:cover;"
                            class="img-thumbnail"
                        >

                    </div>

                </div>

                <div class="row">

                    {{-- Latitude --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="latitude">
                                Latitude
                            </label>

                            <input
                                type="number"
                                name="latitude"
                                id="latitude"
                                class="form-control"
                                value="{{ old('latitude', $wisata->latitude) }}"
                                step="any"
                            >

                        </div>

                    </div>

                    {{-- Longitude --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="longitude">
                                Longitude
                            </label>

                            <input
                                type="number"
                                name="longitude"
                                id="longitude"
                                class="form-control"
                                value="{{ old('longitude', $wisata->longitude) }}"
                                step="any"
                            >

                        </div>

                    </div>

                </div>

                {{-- Status --}}
                <div class="form-group">

                    <div class="custom-control custom-switch">

                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            class="custom-control-input"
                            id="is_active"
                            {{ old('is_active', $wisata->is_active) ? 'checked' : '' }}
                        >

                        <label
                            class="custom-control-label"
                            for="is_active"
                        >
                            Aktif
                        </label>

                    </div>

                </div>

            </div>

            <div class="card-footer">

                <a
                    href="{{ url('/admin/wisata') }}"
                    class="btn btn-secondary"
                >
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fas fa-save"></i>
                    Update
                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@section('script')

<script>
    $('#gambar').on('change', function () {

        const file = this.files[0];

        if (file) {

            $(this)
                .next('.custom-file-label')
                .html(file.name);

            const reader = new FileReader();

            reader.onload = function (e) {

                $('#preview-gambar')
                    .attr('src', e.target.result)
                    .show();

            };

            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
