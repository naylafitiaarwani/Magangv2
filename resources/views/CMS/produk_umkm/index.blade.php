@extends('admin_template')

@section('title page', 'Produk UMKM')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>
            <h3 class="card-title mb-1">
                Produk {{ $umkm->nama }}
            </h3>

            <small class="text-muted">
                Kelola produk yang dimiliki UMKM ini.
            </small>
        </div>

        <a
            href="{{ route('admin.umkm.produk.create', $umkm) }}"
            class="btn btn-primary"
        >
            <i class="fas fa-plus"></i>
            Tambah Produk
        </a>

    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($produk->count())

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th width="100">Gambar</th>
                            <th>Produk</th>
                            <th width="180">Harga</th>
                            <th width="100">Status</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($produk as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>

                                    @if($item->gambar)

                                        <img
                                            src="{{ asset('storage/' . $item->gambar) }}"
                                            alt="{{ $item->nama }}"
                                            style="
                                                width:80px;
                                                height:60px;
                                                object-fit:cover;
                                                border-radius:8px;
                                            "
                                        >

                                    @else

                                        <span class="text-muted">
                                            Tidak ada
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <strong>
                                        {{ $item->nama }}
                                    </strong>

                                    @if($item->deskripsi)

                                        <div class="small text-muted mt-1">
                                            {{ Str::limit($item->deskripsi, 100) }}
                                        </div>

                                    @endif

                                </td>

                                <td>

                                    @if($item->harga !== null)

                                        Rp
                                        {{ number_format($item->harga, 0, ',', '.') }}

                                    @else

                                        <span class="text-muted">
                                            Tidak dicantumkan
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($item->is_active)

                                        <span class="badge badge-success">
                                            Aktif
                                        </span>

                                    @else

                                        <span class="badge badge-secondary">
                                            Nonaktif
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route('admin.umkm.produk.edit', [$umkm, $item]) }}"
                                        class="btn btn-sm btn-warning"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.umkm.produk.destroy', [$umkm, $item]) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="text-center py-5">

                <i
                    class="fas fa-box-open text-muted"
                    style="font-size:40px;"
                ></i>

                <h5 class="mt-3">
                    Belum ada produk
                </h5>

                <p class="text-muted">
                    Tambahkan produk pertama untuk
                    {{ $umkm->nama }}.
                </p>

                <a
                href="{{ route('admin.umkm.produk.index', $umkm->id) }}"
                class="btn btn-primary"
                >
                    <i class="fas fa-plus"></i>
                    Tambah Produk
                </a>

            </div>

        @endif

    </div>

    <div class="card-footer">

        <a
            href="{{ url('/admin/umkm') }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left"></i>
            Kembali ke UMKM
        </a>

    </div>

</div>

@endsection
