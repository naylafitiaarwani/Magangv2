@extends('admin_template')

@section('title page', 'Kelola Pengajuan Wisata')

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pengajuan Wisata</h3>

            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ url('/admin/pengajuan-wisata') }}"
                       class="btn btn-sm {{ request('status') ? 'btn-outline-secondary' : 'btn-secondary' }}">
                        Semua
                    </a>
                    <a href="{{ url('/admin/pengajuan-wisata?status=pending') }}"
                       class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                        Menunggu
                    </a>
                    <a href="{{ url('/admin/pengajuan-wisata?status=approved') }}"
                       class="btn btn-sm {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                        Disetujui
                    </a>
                    <a href="{{ url('/admin/pengajuan-wisata?status=rejected') }}"
                       class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                        Ditolak
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Wisata</th>
                        <th>Pengaju</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $pengajuan)
                        <tr>
                            <td>{{ $pengajuans->firstItem() + $loop->index }}</td>
                            <td>{{ $pengajuan->nama_wisata }}</td>
                            <td>
                                {{ $pengajuan->nama_pengaju }}<br>
                                <small class="text-muted">{{ $pengajuan->jabatan_pengaju }}</small>
                            </td>
                            <td>
                                {{ $pengajuan->email_pengaju }}<br>
                                <small class="text-muted">{{ $pengajuan->no_hp_pengaju }}</small>
                            </td>
                            <td>
                                @if($pengajuan->status == 'pending')
                                    <span class="badge badge-warning">Menunggu</span>
                                @elseif($pengajuan->status == 'approved')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-info"
                                        data-toggle="modal"
                                        data-target="#modalDetail{{ $pengajuan->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </td>
                        </tr>

                        {{-- ================= MODAL DETAIL ================= --}}
                        <div class="modal fade" id="modalDetail{{ $pengajuan->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $pengajuan->nama_wisata }}</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="180">Alamat</th>
                                                <td>: {{ $pengajuan->alamat_wisata }}</td>
                                            </tr>
                                            <tr>
                                                <th>Deskripsi</th>
                                                <td>: {{ $pengajuan->deskripsi_wisata }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Pengaju</th>
                                                <td>: {{ $pengajuan->nama_pengaju }} ({{ $pengajuan->jabatan_pengaju }})</td>
                                            </tr>
                                            <tr>
                                                <th>Kontak</th>
                                                <td>: {{ $pengajuan->email_pengaju }} · {{ $pengajuan->no_hp_pengaju }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status Entitas</th>
                                                <td>: {{ ucfirst($pengajuan->status_entitas) }}</td>
                                            </tr>

                                            @if($pengajuan->status_entitas === 'organisasi')
                                                <tr>
                                                    <th>Nama Organisasi</th>
                                                    <td>: {{ $pengajuan->nama_organisasi ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jual Tiket Online</th>
                                                    <td>: {{ $pengajuan->active_online_tiket ?? '-' }}</td>
                                                </tr>
                                            @endif

                                            @if($pengajuan->status === 'rejected')
                                                <tr>
                                                    <th>Alasan Ditolak</th>
                                                    <td>: {{ $pengajuan->alasan_penolakan }}</td>
                                                </tr>
                                            @endif
                                        </table>

                                        <hr>

                                        <p class="font-weight-bold mb-2">Dokumen</p>
                                        <div>
                                            @if(is_array($pengajuan->foto_lokasi_wisata))
                                                @foreach($pengajuan->foto_lokasi_wisata as $i => $foto)
                                                    <a href="{{ asset('storage/' . $foto) }}" target="_blank"
                                                       class="btn btn-outline-secondary btn-sm mb-1">
                                                        Foto Lokasi {{ $i + 1 }}
                                                    </a>
                                                @endforeach
                                            @endif

                                            @if($pengajuan->status_entitas === 'perorangan')
                                                @if($pengajuan->foto_ktp)
                                                    <a href="{{ asset('storage/' . $pengajuan->foto_ktp) }}" target="_blank"
                                                       class="btn btn-outline-secondary btn-sm mb-1">
                                                        Foto KTP
                                                    </a>
                                                @endif
                                                @if($pengajuan->foto_selfie)
                                                    <a href="{{ asset('storage/' . $pengajuan->foto_selfie) }}" target="_blank"
                                                       class="btn btn-outline-secondary btn-sm mb-1">
                                                        Foto Selfie
                                                    </a>
                                                @endif
                                            @else
                                                @if(is_array($pengajuan->dokumen_nib))
                                                    @foreach($pengajuan->dokumen_nib as $i => $nib)
                                                        <a href="{{ asset('storage/' . $nib) }}" target="_blank"
                                                           class="btn btn-outline-secondary btn-sm mb-1">
                                                            Dokumen NIB {{ $i + 1 }}
                                                        </a>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    @if($pengajuan->status === 'pending')
                                        <div class="modal-footer justify-content-between">

                                            <form action="{{ url('/admin/pengajuan-wisata/update-status/' . $pengajuan->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Setujui pengajuan ini? Data wisata akan otomatis dibuat.')">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                            </form>

                                            <button type="button"
                                                    class="btn btn-danger"
                                                    data-toggle="modal"
                                                    data-target="#modalReject{{ $pengajuan->id }}">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        {{-- ================= MODAL REJECT (alasan) ================= --}}
                        @if($pengajuan->status === 'pending')
                            <div class="modal fade" id="modalReject{{ $pengajuan->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ url('/admin/pengajuan-wisata/update-status/' . $pengajuan->id) }}"
                                              method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="reject">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak Pengajuan</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <label>Alasan penolakan</label>
                                                <textarea name="alasan_penolakan" rows="3"
                                                          class="form-control" required></textarea>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Batal
                                                </button>
                                                <button type="submit" class="btn btn-danger">
                                                    Tolak Pengajuan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada pengajuan wisata.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $pengajuans->links() }}
        </div>
    </div>
</div>

@endsection
