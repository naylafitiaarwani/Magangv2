@extends('admin_template')

@section('title page', 'Edit Sejarah')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Edit Data Sejarah
        </h3>
    </div>

    <form
        action="{{ url('/admin/sejarah/update/' . $sejarah->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <div class="card-body">

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- JUDUL --}}

            <div class="form-group">

                <label for="judul">
                    Judul Sejarah
                </label>

                <input
                    type="text"
                    name="judul"
                    id="judul"
                    class="form-control"
                    value="{{ old('judul', $sejarah->judul) }}"
                    required
                >

            </div>


            {{-- KATEGORI --}}

            <div class="form-group">

                <label for="kategori">
                    Kategori
                </label>

                <input
                    type="text"
                    name="kategori"
                    id="kategori"
                    class="form-control"
                    value="{{ old('kategori', $sejarah->kategori) }}"
                >

            </div>


            {{-- LOKASI --}}

            <div class="form-group">

                <label for="lokasi">
                    Lokasi
                </label>

                <input
                    type="text"
                    name="lokasi"
                    id="lokasi"
                    class="form-control"
                    value="{{ old('lokasi', $sejarah->lokasi) }}"
                >

            </div>


            {{-- DESKRIPSI --}}

            <div class="form-group">

                <label for="deskripsi">
                    Deskripsi
                </label>

                <textarea
                name="deskripsi"
                id="deskripsi"
                rows="12"
                class="form-control"
                placeholder="Tulis deskripsi sejarah di sini. Gunakan satu baris kosong untuk memisahkan paragraf."
            >{{ old('deskripsi', $sejarah->deskripsi) }}</textarea>

            <small class="form-text text-muted">
                Gunakan satu baris kosong untuk memisahkan setiap paragraf.
            </small>

            </div>


            {{-- GAMBAR LAMA --}}

            <div class="form-group">

                <label>
                    Gambar Saat Ini
                </label>

                <div class="row">

                    {{-- GAMBAR LAMA DARI KOLOM SEJARAHS --}}

                    @if($sejarah->gambar)

                        <div class="col-md-3 mb-3">

                            <div class="card">

                                <img
                                    src="{{ asset('storage/' . (str_contains($sejarah->gambar, '/') ? $sejarah->gambar : 'sejarah/' . $sejarah->gambar)) }}"
                                    alt="{{ $sejarah->judul }}"
                                    class="card-img-top"
                                    style="height:180px; object-fit:cover;"
                                >

                                <div class="card-body p-2">

                                    <small class="text-muted">
                                        Gambar utama lama
                                    </small>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- GAMBAR DARI SEJARAH_IMAGES --}}

                    @foreach($sejarah->images as $image)

                        <div class="col-md-3 mb-3">

                            <div class="card">

                                <img
                                    src="{{ asset('storage/' . $image->gambar) }}"
                                    alt="{{ $sejarah->judul }}"
                                    class="card-img-top"
                                    style="height:180px; object-fit:cover;"
                                >

                                <div class="card-body p-2">

                                    <div class="custom-control custom-checkbox">

                                        <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="hapus_gambar_{{ $image->id }}"
                                            name="hapus_gambar[]"
                                            value="{{ $image->id }}"
                                        >

                                        <label
                                            class="custom-control-label text-danger"
                                            for="hapus_gambar_{{ $image->id }}"
                                        >
                                            Hapus gambar
                                        </label>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- TAMBAH GAMBAR --}}

            <div class="form-group">

                <label for="gambar">
                    Tambah Gambar
                </label>

                <div class="custom-file">

                    <input
                        type="file"
                        name="gambar[]"
                        id="gambar"
                        class="custom-file-input"
                        accept=".jpg,.jpeg,.png,.webp"
                        multiple
                    >

                    <label
                        class="custom-file-label"
                        for="gambar"
                    >
                        Pilih gambar baru
                    </label>

                </div>

                <small class="form-text text-muted">
                    Kamu dapat memilih beberapa gambar sekaligus.
                    Gambar lama akan tetap tersimpan kecuali kamu memilih
                    opsi "Hapus gambar".
                    Format JPG, JPEG, PNG, WEBP. Maksimal 2 MB per gambar.
                </small>

            </div>


            {{-- PREVIEW GAMBAR BARU --}}

            <div
                id="preview-container"
                class="row"
            >
            </div>


            {{-- AKTIF --}}

            <div class="form-group mt-3">

                <div class="custom-control custom-switch">

                    <input
                        type="checkbox"
                        class="custom-control-input"
                        id="is_active"
                        name="is_active"
                        value="1"
                        {{ $sejarah->is_active ? 'checked' : '' }}
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


        {{-- FOOTER --}}

        <div class="card-footer">

            <a
                href="{{ url('/admin/sejarah') }}"
                class="btn btn-secondary"
            >
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

@endsection


@section('script')

<script>

$('#gambar').on('change', function () {

    const files = this.files;

    $('#preview-container').html('');

    if (!files.length) {
        return;
    }

    Array.from(files).forEach(function (file) {

        const reader = new FileReader();

        reader.onload = function (e) {

            const html = `
                <div class="col-md-3 mb-3">
                    <div class="card h-100">

                        <img
                            src="${e.target.result}"
                            class="card-img-top"
                            style="height:150px; object-fit:cover;"
                        >

                        <div class="card-body p-2">

                            <small class="text-muted d-block text-truncate">
                                ${file.name}
                            </small>

                        </div>

                    </div>
                </div>
            `;

            $('#preview-container').append(html);

        };

        reader.readAsDataURL(file);

    });

    $(this)
        .next('.custom-file-label')
        .html(
            files.length + ' gambar dipilih'
        );

});

</script>

@endsection
