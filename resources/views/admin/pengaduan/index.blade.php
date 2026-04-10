@extends('layouts.admin')

@section('title', 'Data Pengaduan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="ph ph-warning-octagon"></i> Data Pengaduan/Aspirasi</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ph ph-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <!-- Filter Form -->
                <form method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cari</label>
                            <input type="text" name="search" class="form-control" placeholder="Cari lokasi atau keterangan..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ph ph-magnifying-glass"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Tanggal</th>
                                <th width="12%">Pengaju</th>
                                <th width="10%">Kategori</th>
                                <th width="15%">Lokasi</th>
                                <th width="8%">Status</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aspirasi as $index => $item)
                            <tr>
                                <td>{{ $index + $aspirasi->firstItem() }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($item->user && $item->user->siswa)
                                        <strong>{{ $item->user->siswa->nama ?? $item->user->email }}</strong><br>
                                        <small class="text-muted">NIS: {{ $item->user->siswa->nis ?? '-' }}</small>
                                    @else
                                        {{ $item->user->email ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td>
                                    <i class="ph ph-map-pin"></i> {{ Str::limit($item->lokasi, 30) }}
                                </td>
                                <td>
                                    @if($item->status == 'Menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($item->status == 'Proses')
                                        <span class="badge bg-info">Diproses</span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.pengaduan.detail', $item->id_aspirasi) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id_aspirasi }}">
                                        <i class="ph ph-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Modal Delete Admin -->
                            <div class="modal fade" id="deleteModal{{ $item->id_aspirasi }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="ph ph-trash"></i> Hapus Aspirasi
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-3">
                                                <i class="ph ph-warning-circle" style="font-size: 48px; color: #dc2626;"></i>
                                            </div>
                                            <p class="text-center">Apakah Anda yakin ingin menghapus aspirasi ini?</p>
                                            <p><strong>Lokasi:</strong> {{ $item->lokasi }}</p>
                                            <p><strong>Pengaju:</strong> {{ $item->user->siswa->nama ?? $item->user->email ?? '-' }}</p>
                                            @if($item->foto)
                                            <div class="alert alert-warning">
                                                <i class="ph ph-warning"></i> Foto terkait juga akan dihapus.
                                            </div>
                                            @endif
                                            <div class="alert alert-danger">
                                                <i class="ph ph-warning"></i> <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.pengaduan.destroy', $item->id_aspirasi) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="ph ph-trash"></i> Ya, Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="py-4">
                                        <i class="ph ph-chat-circle-text" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="mt-2 text-muted">Belum ada data aspirasi</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $aspirasi->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection