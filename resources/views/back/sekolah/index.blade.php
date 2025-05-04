@extends('back.layouts.app')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Data Sekolah</h5>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle"></i> Tambah Sekolah
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

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sekolah</th>
                            <th>Jenjang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sekolahs as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_sekolah }}</td>
                                <td>{{ $item->jenjang }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $item->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('sekolah.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @foreach ($sekolahs as $item)
                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('sekolah.update', $item->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Sekolah</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Nama Sekolah</label>
                                    <input type="text" name="nama_sekolah" class="form-control" value="{{ $item->nama_sekolah }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Jenjang</label>
                                    <select name="jenjang" class="form-control" required>
                                        <option value="SD" {{ $item->jenjang == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SLTP" {{ $item->jenjang == 'SLTP' ? 'selected' : '' }}>SLTP</option>
                                        <option value="SLTA" {{ $item->jenjang == 'SLTA' ? 'selected' : '' }}>SLTA</option>
                                        <option value="PERGURUAN TINGGI" {{ $item->jenjang == 'PERGURUAN TINGGI' ? 'selected' : '' }}>Perguruan Tinggi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('sekolah.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Sekolah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Jenjang</label>
                    <select name="jenjang" class="form-control" required>
                        <option value="">-- Pilih Jenjang --</option>
                        <option value="SD">SD</option>
                        <option value="SLTP">SLTP</option>
                        <option value="SLTA">SLTA</option>
                        <option value="PERGURUAN TINGGI">Perguruan Tinggi</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection