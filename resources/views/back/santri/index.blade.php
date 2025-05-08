@extends('back.layouts.app')

@section('content')

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
@if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
<div class="card">
    <div class="card-header d-flex justify-content-between item">
        <h5 class="card-title">Data Santri</h5>
        <div class="d-flex mb-3 gap-1">
            <a href="{{ route('santri.create') }}" class="btn btn-primary btn-sm mr-2">+ Tambah Santri</a>
            <a href="" class="btn btn-success btn-sm mr-2">
                <i class="bi bi-file-earmark-spreadsheet"></i> Impor XLS
            </a>
            <a href="" class="btn btn-secondary btn-sm">
                <i class="bi bi-cloud-download"></i> Unduh Template XLS
            </a>
        </div>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Angkatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($santris as $santri)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $santri->nis }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg me-3">
                                    <img src="{{ $santri->foto ? asset('storage/' . $santri->foto) : asset('assets/compiled/jpg/2.jpg') }}"
                                         alt="Foto Santri"
                                         class="rounded-circle"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $santri->nama }}</span>
                                    <span class="text-muted small">{{ $santri->program ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $santri->angkatan }}</td>
                        <td>
                            <span class="badge 
                                {{ $santri->status == 'aktif' ? 'bg-success' : 
                                   ($santri->status == 'keluar' ? 'bg-danger' : 'bg-primary') }}">
                                {{ ucfirst($santri->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('santri.show', $santri->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('santri.edit', $santri->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('santri.destroy', $santri->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
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
@endsection
