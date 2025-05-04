@extends('back.layouts.app')

@section('content')
<div class="row">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Data Bank Pesantren</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle"></i> Tambah Bank
            </button>
        </div>
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
            <table id="table1" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Bank</th>
                        <th>Nomor Rekening</th>
                        <th>Nama Pemilik</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banks as $bank)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $bank->nama_bank }}</td>
                        <td>{{ $bank->nomor_rekening }}</td>
                        <td>{{ $bank->nama_pemilik }}</td>
                        <td>
                            <span class="badge {{ $bank->is_aktif ? 'bg-success' : 'bg-danger' }}">
                                {{ $bank->is_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $bank->id }}">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>
                            <!-- Tombol Hapus -->
                            <form action="{{ route('bank.destroy', $bank->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bank ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash-fill"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Bank -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('bank.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_bank">Nama Bank</label>
                        <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank') }}" required>
                        @error('nama_bank') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nomor_rekening">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" class="form-control" value="{{ old('nomor_rekening') }}" required>
                        @error('nomor_rekening') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nama_pemilik">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" class="form-control" value="{{ old('nama_pemilik') }}" required>
                        @error('nama_pemilik') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="is_aktif">Status Aktif</label>
                        <select name="is_aktif" class="form-control" required>
                            <option value="1" {{ old('is_aktif') == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_aktif') == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_aktif') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Bank -->
@foreach ($banks as $bank)
<div class="modal fade" id="editModal{{ $bank->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('bank.update', $bank->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_bank">Nama Bank</label>
                        <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank', $bank->nama_bank) }}" required>
                        @error('nama_bank') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nomor_rekening">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" class="form-control" value="{{ old('nomor_rekening', $bank->nomor_rekening) }}" required>
                        @error('nomor_rekening') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nama_pemilik">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" class="form-control" value="{{ old('nama_pemilik', $bank->nama_pemilik) }}" required>
                        @error('nama_pemilik') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="is_aktif">Status Aktif</label>
                        <select name="is_aktif" class="form-control" required>
                            <option value="1" {{ old('is_aktif', $bank->is_aktif) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_aktif', $bank->is_aktif) == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_aktif') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
