@extends('admin_template')

@section('title page', 'Data Kuliner')

@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Data Kuliner
        </h3>

        <div class="card-tools">

            <a
                href="{{ url('/admin/kuliner/create') }}"
                class="btn btn-primary"
            >
                <i class="fas fa-plus"></i>
                Tambah Kuliner
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
                        <th>Harga Mulai</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($kuliners as $kuliner)

                        <tr>

                            <td>
                                {{ $loop->iteration + ($kuliners->currentPage() - 1) * $kuliners->perPage() }}
                            </td>

                            <td>

                                @if($kuliner->gambar)

                                    <img
                                        src="{{ asset('storage/' . (str_contains($kuliner->gambar, '/') ? $kuliner->gambar : 'kuliner/' . $kuliner->gambar)) }}"
                                        alt="{{ $kuliner->nama }}"
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
                                {{ $kuliner->nama }}
                            </td>

                            <td>
                                {{ $kuliner->kategori ?? '-' }}
                            </td>

                            <td>

                                @if($kuliner->harga_mulai !== null)

                                    Rp {{ number_format($kuliner->harga_mulai, 0, ',', '.') }}

                                @else

                                    -

                                @endif

                            </td>

                            <td>
                                {{ $kuliner->lokasi ?? '-' }}
                            </td>

                            <td>

                                @if($kuliner->is_active)

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
                                    href="{{ url('/admin/kuliner/edit/' . $kuliner->id) }}"
                                    class="btn btn-warning btn-sm"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a
                                    href="{{ url('/admin/kuliner/delete/' . $kuliner->id) }}"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data kuliner ini?')"
                                >
                                    <i class="fas fa-trash"></i>
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center">
                                Belum ada data kuliner.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $kuliners->links() }}
        </div>

    </div>

</div>

@endsection
