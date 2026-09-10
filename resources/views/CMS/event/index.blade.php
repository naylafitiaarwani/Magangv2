@extends('admin_template')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Manage Event</h4>
            <p class="text-muted mb-0">
                Kelola event dan agenda di Indramayu.
            </p>
        </div>

        <a href="/admin/event/create" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Event
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th width="100">Gambar</th>
                            <th>Nama Event</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($events as $index => $event)

                        <tr>

                            <td>
                                {{ $events->firstItem() + $index }}
                            </td>

                            <td>
                                @if($event->gambar)

                                    <img
                                        src="{{ asset('storage/' . $event->gambar) }}"
                                        alt="{{ $event->nama }}"
                                        style="
                                            width: 80px;
                                            height: 60px;
                                            object-fit: cover;
                                            border-radius: 6px;
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
                                    {{ $event->nama }}
                                </strong>
                            </td>

                            <td>
                                {{ $event->kategori ?? '-' }}
                            </td>

                            <td>
                                {{ $event->tanggal_mulai?->format('d M Y') }}

                                @if($event->tanggal_selesai)
                                    <br>
                                    <small class="text-muted">
                                        s/d
                                        {{ $event->tanggal_selesai->format('d M Y') }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                {{ $event->lokasi ?? '-' }}
                            </td>

                            <td>

                                @if($event->is_active)

                                    <span class="badge bg-success">
                                        Aktif
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Tidak Aktif
                                    </span>

                                @endif

                            </td>

                            <td>

                                <a
                                    href="/admin/event/edit/{{ $event->id }}"
                                    class="btn btn-sm btn-warning"
                                >
                                    Edit
                                </a>

                                <a
                                    href="/admin/event/delete/{{ $event->id }}"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus event ini?')"
                                >
                                    Hapus
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center py-4">
                                Belum ada data event.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $events->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
