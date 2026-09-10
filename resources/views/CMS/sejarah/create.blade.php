@extends('admin_template')

@section('title page', 'Tambah Sejarah')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Tambah Data Sejarah
        </h3>
    </div>

    <form
        action="{{ url('/admin/sejarah/store') }}"
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
                    value="{{ old('judul') }}"
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
                    value="{{ old('kategori') }}"
                    placeholder="Contoh: Sejarah Daerah, Tokoh, Bangunan"
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
                    value="{{ old('lokasi') }}"
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
                    rows="6"
                    class="form-control"
                >{{ old('deskripsi') }}</textarea>

            </div>


            {{-- GAMBAR --}}

            <div class="form-group">

                <label for="gambar">
                    Gambar
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
                        Pilih gambar
                    </label>

                </div>

                <small class="form-text text-muted">
                    Kamu dapat memilih beberapa gambar sekaligus.
                    Format JPG, JPEG, PNG, WEBP. Maksimal 2 MB per gambar.
                </small>

            </div>


            {{-- PREVIEW --}}

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
                        checked
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
                Simpan
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

    let fileNames = [];

    Array.from(files).forEach(function (file, index) {

        fileNames.push(file.name);

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
