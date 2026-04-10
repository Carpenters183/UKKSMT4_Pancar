@extends('layouts.admin')

@section('title', 'Detail Aspirasi')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ph ph-file-text"></i> Detail Aspirasi</h5>
            </div>
            <div class="card-body">
                <!-- Status Badge -->
                <div class="text-end mb-3">
                    @if($aspirasi->status == 'Menunggu')
                        <span class="badge bg-warning text-dark p-2">Menunggu</span>
                    @elseif($aspirasi->status == 'Proses')
                        <span class="badge bg-info p-2">Diproses</span>
                    @else
                        <span class="badge bg-success p-2">Selesai</span>
                    @endif
                </div>
                
                <!-- Detail Aspirasi -->
                <div class="mb-3">
                    <label class="fw-bold">Tanggal Pengajuan</label>
                    <p>{{ $aspirasi->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="fw-bold">Kategori</label>
                    <p>{{ $aspirasi->kategori->nama_kategori ?? '-' }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="fw-bold">Lokasi</label>
                    <p><i class="ph ph-map-pin"></i> {{ $aspirasi->lokasi }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="fw-bold">Keterangan</label>
                    <p class="border rounded p-2 bg-light">{{ nl2br($aspirasi->keterangan) }}</p>
                </div>
                
                @if($aspirasi->foto)
                <div class="mb-3">
                    <label class="fw-bold">Foto</label>
                    <div>
                        <a href="{{ asset('storage/' . $aspirasi->foto) }}" target="_blank">
                            <img src="{{ asset('storage/' . $aspirasi->foto) }}" 
                                 alt="Foto Aspirasi" class="img-fluid rounded" style="max-width: 100%;">
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Riwayat Progres -->
                <div class="mt-4">
                    <h6><i class="ph ph-clock-counter-clockwise"></i> Riwayat Progres & Feedback</h6>
                    <hr>
                    @forelse($aspirasi->progres->sortByDesc('created_at') as $progres)
                        <div class="alert alert-light border-start 
                            @if(str_contains($progres->keterangan_progres, 'Feedback:')) border-primary
                            @else border-success
                            @endif" 
                            style="border-left-width: 4px !important;">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <strong>
                                    @if(str_contains($progres->keterangan_progres, 'Feedback:'))
                                        <i class="ph ph-chat-dots text-primary"></i> Feedback
                                    @else
                                        <i class="ph ph-progress text-success"></i> Progres
                                    @endif
                                    @if($progres->user)
                                        - {{ $progres->user->role == 'guru' ? 'Guru' : 'Admin' }}
                                    @endif
                                </strong>
                                <small class="text-muted">{{ $progres->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="mb-0 mt-2">
                                {{ str_replace('Feedback: ', '', $progres->keterangan_progres) }}
                            </p>
                        </div>
                    @empty
                        <div class="alert alert-secondary text-center">
                            <i class="ph ph-info"></i> Belum ada progres atau feedback
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('siswa.aspirasi.status') }}" class="btn btn-secondary w-100 w-md-auto">
                    <i class="ph ph-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection