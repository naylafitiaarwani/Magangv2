@extends('admin_template')

@section('title page', 'Edit Kuliner')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Edit Data Kuliner
        </h3>
    </div>

    <form
        action="{{ url('/admin/kuliner/update/' . $kuliner->id) }}"
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

            <div class="form-group">

                <label for="nama">
                    Nama Kuliner
                </label>

                <input
                    type="text"
                    name="nama"
                    id="nama"
                    class="form-control"
                    value="{{ old('nama', $kuliner->nama) }}"
                    required
                >

            </div>

            <div class="form-group">

                <label for="kategori">
                    Kategori
                </label>

                <input
                    type="text"
                    name="kategori"
                    id="kategori"
                    class="form-control"
                    value="{{ old('kategori', $kuliner->kategori) }}"
                >

            </div>

            <div class="form-group">

                <label for="harga_mulai">
                    Harga Mulai
                </label>

                <input
                    type="number"
                    name="harga_mulai"
                    id="harga_mulai"
                    class="form-control"
                    value="{{ old('harga_mulai', $kuliner->harga_mulai) }}"
                    min="0"
                >

            </div>

            <div class="form-group">

                <label for="lokasi">
                    Lokasi
                </label>

                <input
                    type="text"
                    name="lokasi"
                    id="lokasi"
                    class="form-control"
                    value="{{ old('lokasi', $kuliner->lokasi) }}"
                >

            </div>

            <div class="form-group">

                <label for="deskripsi">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="5"
                    class="form-control"
                >{{ old('deskripsi', $kuliner->deskripsi) }}</textarea>

            </div>

            @if($kuliner->gambar)

                <div class="form-group">

                    <label>
                        Gambar Saat Ini
                    </label>

                    <div>

                        <img
                            src="{{ asset('storage/' . (str_contains($kuliner->gambar, '/') ? $kuliner->gambar : 'kuliner/' . $kuliner->gambar)) }}"
                            alt="{{ $kuliner->nama }}"
                            class="img-thumbnail"
                            style="max-width:300px; max-height:200px; object-fit:cover;"
                        >

                    </div>

                </div>

            @endif

            <div class="form-group">

                <label for="gambar">
                    Ganti Gambar
                </label>

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
                    Maksimal 2 MB.
                </small>

            </div>

            <div class="form-group">

                <img
                    id="preview-gambar"
                    src="#"
                    alt="Preview"
                    class="img-thumbnail"
                    style="display:none; max-width:300px; max-height:200px; object-fit:cover;"
                >

            </div>

            <div class="form-group">

                <div class="custom-control custom-switch">

                    <input
                        type="checkbox"
                        class="custom-control-input"
                        id="is_active"
                        name="is_active"
                        value="1"
                        {{ $kuliner->is_active ? 'checked' : '' }}
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
                href="{{ url('/admin/kuliner') }}"
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
