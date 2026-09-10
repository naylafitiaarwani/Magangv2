@extends('admin_template')

@section('title page', 'Data Budaya')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Budaya</h3>

        <div class="card-tools">
            <a href="{{ url('/admin/budaya/create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Budaya
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
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($budayas as $budaya)
                        <tr>
                            <td>
                                {{ $loop->iteration + ($budayas->currentPage() - 1) * $budayas->perPage() }}
                            </td>

                            <td>
                                @if($budaya->gambar)
                                    <img
                                        src="{{ asset('storage/' . (str_contains($budaya->gambar, '/') ? $budaya->gambar : 'budaya/' . $budaya->gambar)) }}"
                                        alt="{{ $budaya->nama }}"
                                        class="img-thumbnail"
                                        style="width:80px; height:60px; object-fit:cover;"
                                    >
                                @else
                                    <span class="text-muted">Tidak ada gambar</span>
                                @endif
                            </td>

                            <td>{{ $budaya->nama }}</td>

                            <td>{{ $budaya->kategori ?? '-' }}</td>

                            <td>{{ $budaya->lokasi ?? '-' }}</td>

                            <td>
                                @if($budaya->is_active)
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
                                    href="{{ url('/admin/budaya/edit/' . $budaya->id) }}"
                                    class="btn btn-warning btn-sm"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a
                                    href="{{ url('/admin/budaya/delete/' . $budaya->id) }}"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data budaya ini?')"
                                >
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada data budaya.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $budayas->links() }}
        </div>

    </div>
</div>

@endsection
