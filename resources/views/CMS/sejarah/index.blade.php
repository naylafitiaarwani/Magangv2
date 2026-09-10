@extends('admin_template')

@section('title page', 'Data Sejarah')

@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Data Sejarah
        </h3>

        <div class="card-tools">

            <a
                href="{{ url('/admin/sejarah/create') }}"
                class="btn btn-primary"
            >
                <i class="fas fa-plus"></i>
                Tambah Sejarah
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
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($sejarahs as $sejarah)

                        <tr>

                            <td>
                                {{ $loop->iteration + ($sejarahs->currentPage() - 1) * $sejarahs->perPage() }}
                            </td>

                            <td>

                                @if($sejarah->gambar)

                                    <img
                                        src="{{ asset('storage/' . (str_contains($sejarah->gambar, '/') ? $sejarah->gambar : 'sejarah/' . $sejarah->gambar)) }}"
                                        alt="{{ $sejarah->judul }}"
                                        class="img-thumbnail"
                                        style="width:80px; height:60px; object-fit:cover;"
                                    >

                                @else

                                    <span class="text-muted">
                                        Tidak ada gambar
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $sejarah->judul }}
                            </td>

                            <td>
                                {{ $sejarah->kategori ?? '-' }}
                            </td>

                            <td>
                                {{ $sejarah->lokasi ?? '-' }}
                            </td>

                            <td>

                                @if($sejarah->is_active)

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
                                    href="{{ url('/admin/sejarah/edit/' . $sejarah->id) }}"
                                    class="btn btn-warning btn-sm"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a
                                    href="{{ url('/admin/sejarah/delete/' . $sejarah->id) }}"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data sejarah ini?')"
                                >
                                    <i class="fas fa-trash"></i>
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center">
                                Belum ada data sejarah.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $sejarahs->links() }}
        </div>

    </div>

</div>

@endsection
