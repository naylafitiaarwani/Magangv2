@extends('admin_template')

@section('title page', 'Data UMKM')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Data UMKM</h3>

        <div class="card-tools">
            <a href="{{ url('/admin/umkm/create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah UMKM
            </a>
        </div>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Harga Mulai</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($umkms as $umkm)

                        <tr>

                            <td>
                                {{ $loop->iteration + ($umkms->currentPage() - 1) * $umkms->perPage() }}
                            </td>

                            <td>
                                @if($umkm->gambar)

                                    <img
                                        src="{{ asset('storage/' . (str_contains($umkm->gambar, '/') ? $umkm->gambar : 'umkm/' . $umkm->gambar)) }}"
                                        alt="{{ $umkm->nama }}"
                                        class="img-thumbnail"
                                        style="width:80px; height:60px; object-fit:cover;"
                                    >

                                @else

                                    <span class="text-muted">
                                        Tidak ada gambar
                                    </span>

                                @endif
                            </td>

                            <td>{{ $umkm->nama }}</td>

                            <td>{{ $umkm->kategori ?? '-' }}</td>

                            <td>{{ $umkm->lokasi ?? '-' }}</td>

                            <td>
                                @if($umkm->harga_mulai !== null)
                                    Rp {{ number_format($umkm->harga_mulai, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $umkm->kontak ?? '-' }}</td>

                            <td>
                                @if($umkm->is_active)

                                    <span class="badge badge-success">
                                        Aktif
                                    </span>

                                @else

                                    <span class="badge badge-secondary">
                                        Tidak Aktif
                                    </span>

                                @endif
                            </td>

                            
                            <td>

                                <a
                                    href="{{ url('/admin/umkm/edit/' . $umkm->id) }}"
                                    class="btn btn-warning btn-sm"
                                    title="Edit UMKM"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a
                                    href="{{ url('/admin/umkm/delete/' . $umkm->id) }}"
                                    class="btn btn-danger btn-sm"
                                    title="Hapus UMKM"
                                    onclick="return confirm('Yakin ingin menghapus data UMKM ini?')"
                                >
                                    <i class="fas fa-trash"></i>
                                </a>

                                <a
                                href="{{ route('admin.umkm.produk.index', $umkm->id) }}"
                                class="btn btn-info btn-sm"
                                    title="Kelola Produk"
                                >
                                    <i class="fas fa-box"></i>
                                </a>

                            </td>



                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="text-center">
                                Belum ada data UMKM.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $umkms->links() }}
        </div>

    </div>

</div>

@endsection
