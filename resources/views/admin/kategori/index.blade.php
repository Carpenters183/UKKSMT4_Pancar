@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Manajemen Kategori</h4>
            <p class="mb-0">Kelola kategori untuk pengelompokan aspirasi siswa.</p>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                <i class="icon-copy fi-plus"></i> Tambah Kategori
            </button>
        </div>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
    @endif

    {{-- Tabel Kategori --}}
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th width="15%" class="text-center">Total Aspirasi</th>
                    <th width="18%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $index => $kategori)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                    <td>
                        @if($kategori->deskripsi)
                            {{ Str::limit($kategori->deskripsi, 60) }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-info">{{ $kategori->aspirasi_count ?? 0 }} Aspirasi</span>
                    </td>
                    <td class="text-center">
                        {{-- Tombol Edit --}}
                        <button type="button"
                            class="btn btn-warning btn-sm"
                            data-toggle="modal"
                            data-target="#editModal{{ $kategori->id_kategori }}">
                            <i class="icon-copy fi-pencil"></i> Edit
                        </button>

                        {{-- Tombol Hapus --}}
                        <button type="button"
                            class="btn btn-danger btn-sm"
                            data-toggle="modal"
                            data-target="#deleteModal{{ $kategori->id_kategori }}">
                            <i class="icon-copy fi-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="icon-copy fi-tag" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                        Belum ada kategori. Klik tombol <strong>Tambah Kategori</strong> untuk menambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Info --}}
<div class="card-box pd-20">
    <div class="d-flex align-items-start">
        <i class="icon-copy fi-info mr-3 text-primary" style="font-size:1.5rem;margin-top:2px;"></i>
        <div>
            <h6 class="mb-1">Informasi Kategori</h6>
            <p class="mb-0 text-muted" style="font-size:13px;">
                Kategori digunakan untuk mengelompokkan jenis aspirasi/pengaduan.
                Pastikan kategori yang dibuat relevan dengan sarana dan prasarana sekolah.
                Deskripsi kategori akan membantu siswa memahami ruang lingkup kategori tersebut.
            </p>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════
     MODAL TAMBAH KATEGORI
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    <i class="icon-copy fi-plus"></i> Tambah Kategori Baru
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text"
                               name="nama_kategori"
                               class="form-control @error('nama_kategori') is-invalid @enderror"
                               placeholder="Contoh: Fasilitas Kelas, Kebersihan, dll"
                               value="{{ old('nama_kategori') }}"
                               required>
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-0">
                        <label>Deskripsi <span class="text-muted">(opsional)</span></label>
                        <textarea name="deskripsi"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Jelaskan tentang kategori ini...">{{ old('deskripsi') }}</textarea>
                        <small class="text-muted">Deskripsi akan membantu siswa memahami kategori ini.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-copy fi-plus"></i> Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════
     MODAL EDIT & HAPUS — SATU PER KATEGORI
══════════════════════════════════════════════════════ --}}
@foreach($kategoris as $kategori)

    {{-- MODAL EDIT --}}
    <div class="modal fade" id="editModal{{ $kategori->id_kategori }}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="icon-copy fi-pencil"></i> Edit Kategori
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.kategori.update', $kategori->id_kategori) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_kategori"
                                   class="form-control"
                                   value="{{ $kategori->nama_kategori }}"
                                   required>
                        </div>
                        <div class="form-group mb-0">
                            <label>Deskripsi <span class="text-muted">(opsional)</span></label>
                            <textarea name="deskripsi"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Jelaskan tentang kategori ini...">{{ $kategori->deskripsi }}</textarea>
                        </div>

                        @if(($kategori->aspirasi_count ?? 0) > 0)
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="icon-copy fi-alert"></i>
                                <strong>Perhatian:</strong> Kategori ini sudah digunakan dalam
                                {{ $kategori->aspirasi_count }} aspirasi.
                                Perubahan nama akan mempengaruhi semua aspirasi terkait.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="icon-copy fi-pencil"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    <div class="modal fade" id="deleteModal{{ $kategori->id_kategori }}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">
                        <i class="icon-copy fi-trash"></i> Hapus Kategori
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="icon-copy fi-alert" style="font-size:3rem;color:#dc3545;display:block;margin-bottom:12px;"></i>
                    <p class="mb-2">Apakah kamu yakin ingin menghapus kategori:</p>
                    <h5 class="text-danger">"{{ $kategori->nama_kategori }}"</h5>

                    @if(($kategori->aspirasi_count ?? 0) > 0)
                        <div class="alert alert-danger mt-3 text-left">
                            <strong>Peringatan!</strong><br>
                            Kategori ini memiliki <strong>{{ $kategori->aspirasi_count }} aspirasi</strong>.
                            Menghapus kategori akan mengosongkan kategori pada aspirasi tersebut.<br>
                            <small class="text-muted">Disarankan ubah kategori aspirasi terlebih dahulu.</small>
                        </div>
                    @else
                        <p class="text-muted mt-2 mb-0"><small>Kategori ini belum digunakan di aspirasi manapun.</small></p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.kategori.destroy', $kategori->id_kategori) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="icon-copy fi-trash"></i> Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endforeach
@endsection


