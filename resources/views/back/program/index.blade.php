@extends('back.layouts.app')

@section('content')

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Data Program</h5>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                Tambah Program
            </button>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Program</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programs as $program)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $program->nama_program }}</td>
                            <td>{{ $program->keterangan ?? 'tidak ada deskripsi.' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $program->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('program.destroy', $program->id) }}" method="POST"
                                      style="display:inline-block;" onsubmit="return confirm('Yakin?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</section>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('program.store') }}">
            @csrf
            <div class="modal-header">
                <h5>Tambah Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Program</label>
                    <input type="text" name="nama_program" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modals -->
@foreach ($programs as $program)
<div class="modal fade" id="editModal{{ $program->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('program.update', $program->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5>Edit Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Program</label>
                    <input type="text" name="nama_program" class="form-control" value="{{ $program->nama_program }}" required>
                </div>
                <div class="mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control">{{ $program->keterangan }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection
