@extends('admin_template')

@section('title page', 'Wisata & Tiket Saya')

@section('content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($wisataList->isEmpty())

        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">
                    Kamu belum punya wisata yang disetujui. Ajukan dulu lewat
                    halaman pengajuan di website.
                </p>
            </div>
        </div>

    @else

        {{-- Selector kalau punya lebih dari 1 wisata --}}
        @if($wisataList->count() > 1)
            <div class="mb-3">
                @foreach($wisataList as $w)
                    <a href="{{ url('/pengelola?wisata_id=' . $w->id) }}"
                       class="btn btn-sm {{ $selectedWisata->id == $w->id ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $w->nama }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="row">

            {{-- ================= INFO WISATA ================= --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marked-alt"></i>
                            {{ $selectedWisata->nama }}
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-warning btn-sm"
                                    data-toggle="modal" data-target="#modalEditWisata">
                                <i class="fas fa-edit"></i> Edit Info Wisata
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                @if($selectedWisata->gambar)
                                    <img src="{{ asset('storage/' . $selectedWisata->gambar) }}"
                                         class="img-fluid rounded" alt="{{ $selectedWisata->nama }}">
                                @else
                                    <div class="text-muted">Belum ada gambar</div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <th width="150">Lokasi</th>
                                        <td>: {{ $selectedWisata->lokasi ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td>: {{ $selectedWisata->kategori ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jam Operasional</th>
                                        <td>: {{ $selectedWisata->jam_operasional ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Mulai</th>
                                        <td>:
                                            {{ $selectedWisata->harga_mulai !== null
                                                ? 'Rp ' . number_format($selectedWisata->harga_mulai, 0, ',', '.')
                                                : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <td>: {{ $selectedWisata->deskripsi ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Tampil</th>
                                        <td>:
                                            @if($selectedWisata->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            @endif
                                            <small class="text-muted ml-2">(hanya bisa diubah admin)</small>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Modal Edit Wisata --}}
        <div class="modal fade" id="modalEditWisata" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ url('/pengelola/wisata/update/' . $selectedWisata->id) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Info Wisata</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Wisata</label>
                                <input type="text" name="nama" class="form-control"
                                       value="{{ $selectedWisata->nama }}" required>
                            </div>
                            <div class="form-group">
                                <label>Lokasi</label>
                                <input type="text" name="lokasi" class="form-control"
                                       value="{{ $selectedWisata->lokasi }}">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Kategori</label>
                                    <input type="text" name="kategori" class="form-control"
                                           value="{{ $selectedWisata->kategori }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Jam Operasional</label>
                                    <input type="text" name="jam_operasional" class="form-control"
                                           value="{{ $selectedWisata->jam_operasional }}"
                                           placeholder="mis. 08.00 - 17.00 WIB">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Harga Mulai (Rp)</label>
                                <input type="number" name="harga_mulai" class="form-control"
                                       value="{{ $selectedWisata->harga_mulai }}" min="0">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4">{{ $selectedWisata->deskripsi }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Ganti Gambar (opsional)</label>
                                <input type="file" name="gambar" class="form-control-file" accept="image/*">
                                <small class="text-muted">Kosongkan kalau tidak ingin mengganti gambar.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- ================= KELOLA TIKET ================= --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-ticket-alt"></i>
                            Jenis Tiket — {{ $selectedWisata->nama }}
                        </h3>

                        @if($selectedWisata->is_tiket_aktif)
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm"
                                        data-toggle="modal" data-target="#modalTambahTiket">
                                    <i class="fas fa-plus"></i> Tambah Tiket
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        @if(!$selectedWisata->is_tiket_aktif)
                            <div class="alert alert-warning mb-0">
                                Wisata ini belum mengaktifkan penjualan tiket
                                online. Hubungi admin untuk mengaktifkannya.
                            </div>
                        @else
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Tiket</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th width="100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tiketList as $tiket)
                                        <tr>
                                            <td>{{ $tiket->nama_tiket }}</td>
                                            <td>Rp {{ number_format($tiket->harga, 0, ',', '.') }}</td>
                                            <td>
                                                @if($tiket->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalEditTiket{{ $tiket->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="{{ url('/pengelola/tiket/delete/' . $tiket->id) }}"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Hapus jenis tiket ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        {{-- Modal Edit --}}
                                        <div class="modal fade" id="modalEditTiket{{ $tiket->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ url('/pengelola/tiket/update/' . $tiket->id) }}"
                                                          method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Tiket</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Nama Tiket</label>
                                                                <input type="text" name="nama_tiket" class="form-control"
                                                                       value="{{ $tiket->nama_tiket }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Harga</label>
                                                                <input type="number" name="harga" class="form-control"
                                                                       value="{{ $tiket->harga }}" min="0" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Deskripsi</label>
                                                                <textarea name="deskripsi" class="form-control" rows="2">{{ $tiket->deskripsi }}</textarea>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" name="is_active" value="1"
                                                                       class="form-check-input"
                                                                       id="isActive{{ $tiket->id }}"
                                                                       {{ $tiket->is_active ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="isActive{{ $tiket->id }}">
                                                                    Aktif dijual
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                Batal
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">
                                                                Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Belum ada jenis tiket.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ================= PESANAN MASUK ================= --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-receipt"></i>
                            Pesanan Masuk
                        </h3>
                    </div>

                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Pemesan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesananList as $pesanan)
                                    <tr>
                                        <td>{{ $pesanan->kode_pesanan }}</td>
                                        <td>
                                            {{ $pesanan->nama_pemesan }}<br>
                                            <small class="text-muted">{{ $pesanan->email_pemesan }}</small>
                                        </td>
                                        <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            @if($pesanan->status == 'paid')
                                                <span class="badge badge-success">Lunas</span>
                                            @elseif($pesanan->status == 'pending')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($pesanan->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            Belum ada pesanan masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{ $pesananList->links() ?? '' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Tambah Tiket --}}
        @if($selectedWisata->is_tiket_aktif)
            <div class="modal fade" id="modalTambahTiket" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ url('/pengelola/tiket/store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="wisata_id" value="{{ $selectedWisata->id }}">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Jenis Tiket</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Tiket</label>
                                    <input type="text" name="nama_tiket" class="form-control"
                                           placeholder="mis. Dewasa" required>
                                </div>
                                <div class="form-group">
                                    <label>Harga</label>
                                    <input type="number" name="harga" class="form-control" min="0" required>
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi (opsional)</label>
                                    <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    @endif

</div>

@endsection
