@extends('admin_template')

@section('title page', 'Tambah Produk UMKM')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Tambah Produk
        </h3>
    </div>

    <form
        action="{{ route('admin.umkm.produk.store', $umkm) }}"
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
                    Nama Produk
                </label>

                <input
                    type="text"
                    name="nama"
                    id="nama"
                    class="form-control"
                    value="{{ old('nama') }}"
                    placeholder="Contoh: Batik Tulis"
                    required
                >

            </div>


            <div class="form-group">

                <label for="deskripsi">
                    Deskripsi Produk
                </label>

                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="6"
                    class="form-control"
                    placeholder="Jelaskan produk secara singkat..."
                >{{ old('deskripsi') }}</textarea>

            </div>


            <div class="form-group">

                <label for="harga">
                    Harga
                </label>

                <input
                    type="number"
                    name="harga"
                    id="harga"
                    class="form-control"
                    value="{{ old('harga') }}"
                    min="0"
                    step="1000"
                    placeholder="Contoh: 75000"
                >

                <small class="form-text text-muted">
                    Kosongkan jika harga tidak ingin ditampilkan.
                </small>

            </div>


            <div class="form-group">

                <label for="gambar">
                    Gambar Produk
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
                        Pilih gambar
                    </label>

                </div>

                <small class="form-text text-muted">
                    JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                </small>

            </div>


            <div
                id="preview-container"
                class="mt-3"
            ></div>


            <div class="form-group mt-4">

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


        <div class="card-footer">

            <a
                href="{{ route('admin.umkm.produk.index', $umkm) }}"
                class="btn btn-secondary"
            >
                Kembali
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="fas fa-save"></i>
                Simpan Produk
            </button>

        </div>

    </form>

</div>

@endsection

@section('script')

<script>

$('#gambar').on('change', function () {

    const file = this.files[0]

    if (!file) {
        $('#preview-container').html('')
        return
    }

    $(this)
        .next('.custom-file-label')
        .html(file.name)

    const reader = new FileReader()

    reader.onload = function (e) {

        $('#preview-container').html(`
            <div style="max-width:300px;">
                <img
                    src="${e.target.result}"
                    class="img-fluid rounded"
                >
            </div>
        `)

    }

    reader.readAsDataURL(file)

})

</script>

@endsection
