@extends('admin_template')

@section('title page', 'Manage Wisata')

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Wisata</h3>

            <div class="card-tools">
                <a href="{{ url('/admin/wisata/create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Wisata
                </a>
            </div>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($wisatas as $wisata)
                        <tr>
                            <td>
                                @if($wisata->gambar)

                                    <img
                                        src="{{ asset('storage/' . (str_contains($wisata->gambar, '/') ? $wisata->gambar : 'wisata/' . $wisata->gambar)) }}"
                                        alt="{{ $wisata->nama }}"
                                        style="width:80px; height:60px; object-fit:cover;"
                                        class="img-thumbnail"
                                    >

                                @else

                                    <span class="text-muted">
                                        Tidak ada gambar
                                    </span>

                                @endif
                            </td>
                            <td>
                                {{ $wisatas->firstItem() + $loop->index }}
                            </td>

                            <td>
                                {{ $wisata->nama }}
                            </td>

                            <td>
                                {{ $wisata->lokasi ?? '-' }}
                            </td>

                            <td>
                                {{ $wisata->kategori ?? '-' }}
                            </td>

                            <td>
                                {{ $wisata->harga_mulai !== null
                                    ? 'Rp ' . number_format($wisata->harga_mulai, 0, ',', '.')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $wisata->rating }}
                            </td>

                            <td>
                                @if($wisata->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ url('/admin/wisata/edit/' . $wisata->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="{{ url('/admin/wisata/delete/' . $wisata->id) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin ingin menghapus wisata ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                Belum ada data wisata.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <div class="card-footer">
            {{ $wisatas->links() }}
        </div>
    </div>

</div>

@endsection
