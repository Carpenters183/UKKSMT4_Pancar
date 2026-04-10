@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Manajemen Data User</h5>
                <div class="card-header-right">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab-admin" role="tab">
                                <i class="ph ph-shield-check"></i> Admin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-guru" role="tab">
                                <i class="ph ph-chalkboard-teacher"></i> Guru
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-siswa" role="tab">
                                <i class="ph ph-users"></i> Siswa
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">

                    <!-- TAB ADMIN -->
                    <div class="tab-pane fade show active" id="tab-admin" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Data Admin</h6>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                                <i class="ph ph-plus"></i> Tambah Admin
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Tanggal Daftar</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admins as $index => $admin)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td><span class="badge bg-primary">Admin</span></td>
                                        <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editAdminModal{{ $admin->id }}">
                                                <i class="ph ph-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteAdminModal{{ $admin->id }}">
                                                <i class="ph ph-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Admin -->
                                    <div class="modal fade" id="editAdminModal{{ $admin->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Admin</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.admin.update', $admin->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Delete Admin -->
                                    <div class="modal fade" id="deleteAdminModal{{ $admin->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Admin</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus admin <strong>{{ $admin->email }}</strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('admin.admin.destroy', $admin->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data admin</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB GURU -->
                    <div class="tab-pane fade" id="tab-guru" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Data Guru</h6>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createGuruModal">
                                <i class="ph ph-plus"></i> Tambah Guru
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Email</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($gurus as $index => $guru)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $guru->guru->nip ?? '-' }}</td>
                                        <td>{{ $guru->guru->nama ?? '-' }}</td>
                                        <td>{{ $guru->guru->mata_pelajaran ?? '-' }}</td>
                                        <td>{{ $guru->guru->jenis_kelamin ?? '-' }}</td>
                                        <td>{{ $guru->email }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#viewGuruModal{{ $guru->guru->id }}">
                                                <i class="ph ph-eye"></i> View
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editGuruModal{{ $guru->guru->id }}">
                                                <i class="ph ph-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteGuruModal{{ $guru->guru->id }}">
                                                <i class="ph ph-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal View Guru -->
                                    <div class="modal fade" id="viewGuruModal{{ $guru->guru->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Guru</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>NIP:</strong> {{ $guru->guru->nip }}</p>
                                                            <p><strong>Nama:</strong> {{ $guru->guru->nama }}</p>
                                                            <p><strong>Mata Pelajaran:</strong> {{ $guru->guru->mata_pelajaran }}</p>
                                                            <p><strong>Jenis Kelamin:</strong> {{ $guru->guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Tanggal Lahir:</strong> {{ \Carbon\Carbon::parse($guru->guru->tanggal_lahir)->format('d/m/Y') }}</p>
                                                            <p><strong>No HP:</strong> {{ $guru->guru->no_hp }}</p>
                                                            <p><strong>Email:</strong> {{ $guru->email }}</p>
                                                            <p><strong>Alamat:</strong> {{ $guru->guru->alamat }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Edit Guru -->
                                    <div class="modal fade" id="editGuruModal{{ $guru->guru->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Guru</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.guru.update', $guru->guru->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">NIP</label>
                                                                <input type="text" name="nip" class="form-control" value="{{ $guru->guru->nip }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Nama</label>
                                                                <input type="text" name="nama" class="form-control" value="{{ $guru->guru->nama }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Mata Pelajaran</label>
                                                                <input type="text" name="mata_pelajaran" class="form-control" value="{{ $guru->guru->mata_pelajaran }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Jenis Kelamin</label>
                                                                <select name="jenis_kelamin" class="form-control" required>
                                                                    <option value="L" {{ $guru->guru->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                                    <option value="P" {{ $guru->guru->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Tanggal Lahir</label>
                                                                @php
                                                                $tanggalLahirGuru = '';
                                                                if($guru->guru->tanggal_lahir) {
                                                                $tanggalLahirGuru = date('Y-m-d', strtotime($guru->guru->tanggal_lahir));
                                                                }
                                                                @endphp
                                                                <input type="date" name="tanggal_lahir" class="form-control" value="{{ $tanggalLahirGuru }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">No HP</label>
                                                                <input type="text" name="no_hp" class="form-control" value="{{ $guru->guru->no_hp }}" required>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label class="form-label">Alamat</label>
                                                                <textarea name="alamat" class="form-control" rows="2" required>{{ $guru->guru->alamat }}</textarea>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" name="email" class="form-control" value="{{ $guru->email }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Delete Guru -->
                                    <div class="modal fade" id="deleteGuruModal{{ $guru->guru->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Guru</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus guru <strong>{{ $guru->guru->nama }}</strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('admin.guru.destroy', $guru->guru->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data guru</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB SISWA -->
                    <div class="tab-pane fade" id="tab-siswa" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Data Siswa</h6>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#createSiswaModal">
                                <i class="ph ph-plus"></i> Tambah Siswa
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Jurusan</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Email</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siswas as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $siswa->siswa->nis ?? '-' }}</td>
                                        <td>{{ $siswa->siswa->nama ?? '-' }}</td>
                                        <td>{{ $siswa->siswa->kelas ?? '-' }}</td>
                                        <td>{{ $siswa->siswa->jurusan ?? '-' }}</td>
                                        <td>{{ $siswa->siswa->jenis_kelamin ?? '-' }}</td>
                                        <td>{{ $siswa->email }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#viewSiswaModal{{ $siswa->siswa->id }}">
                                                <i class="ph ph-eye"></i> View
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editSiswaModal{{ $siswa->siswa->id }}">
                                                <i class="ph ph-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteSiswaModal{{ $siswa->siswa->id }}">
                                                <i class="ph ph-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal View Siswa -->
                                    <div class="modal fade" id="viewSiswaModal{{ $siswa->siswa->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Siswa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>NIS:</strong> {{ $siswa->siswa->nis }}</p>
                                                            <p><strong>Nama:</strong> {{ $siswa->siswa->nama }}</p>
                                                            <p><strong>Kelas:</strong> {{ $siswa->siswa->kelas }}</p>
                                                            <p><strong>Jurusan:</strong> {{ $siswa->siswa->jurusan }}</p>
                                                            <p><strong>Jenis Kelamin:</strong> {{ $siswa->siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Tanggal Lahir:</strong> {{ \Carbon\Carbon::parse($siswa->siswa->tanggal_lahir)->format('d/m/Y') }}</p>
                                                            <p><strong>No HP:</strong> {{ $siswa->siswa->no_hp }}</p>
                                                            <p><strong>Email:</strong> {{ $siswa->email }}</p>
                                                            <p><strong>Alamat:</strong> {{ $siswa->siswa->alamat }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Edit Siswa -->
                                    <div class="modal fade" id="editSiswaModal{{ $siswa->siswa->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Siswa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.siswa.update', $siswa->siswa->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">NIS</label>
                                                                <input type="text" name="nis" class="form-control" value="{{ $siswa->siswa->nis }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Nama</label>
                                                                <input type="text" name="nama" class="form-control" value="{{ $siswa->siswa->nama }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Kelas</label>
                                                                <input type="text" name="kelas" class="form-control" value="{{ $siswa->siswa->kelas }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Jurusan</label>
                                                                <input type="text" name="jurusan" class="form-control" value="{{ $siswa->siswa->jurusan }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Jenis Kelamin</label>
                                                                <select name="jenis_kelamin" class="form-control" required>
                                                                    <option value="L" {{ $siswa->siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                                    <option value="P" {{ $siswa->siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Tanggal Lahir</label>
                                                                @php
                                                                $tanggalLahirSiswa = '';
                                                                if($siswa->siswa->tanggal_lahir) {
                                                                $tanggalLahirSiswa = date('Y-m-d', strtotime($siswa->siswa->tanggal_lahir));
                                                                }
                                                                @endphp
                                                                <input type="date" name="tanggal_lahir" class="form-control" value="{{ $tanggalLahirSiswa }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">No HP</label>
                                                                <input type="text" name="no_hp" class="form-control" value="{{ $siswa->siswa->no_hp }}" required>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label class="form-label">Alamat</label>
                                                                <textarea name="alamat" class="form-control" rows="2" required>{{ $siswa->siswa->alamat }}</textarea>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" name="email" class="form-control" value="{{ $siswa->email }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Delete Siswa -->
                                    <div class="modal fade" id="deleteSiswaModal{{ $siswa->siswa->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Siswa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus siswa <strong>{{ $siswa->siswa->nama }}</strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('admin.siswa.destroy', $siswa->siswa->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada data siswa</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Admin -->
<div class="modal fade" id="createAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Admin Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.admin.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Minimal 6 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Create Guru -->
<div class="modal fade" id="createGuruModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Guru Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.guru.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" name="mata_pelajaran" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="">Pilih</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Create Siswa -->
<div class="modal fade" id="createSiswaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Siswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.siswa.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="kelas" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="">Pilih</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection