@extends('back.layouts.app')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Kategori Tagihan</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori
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
                                <th>Nama Kategori</th>
                                <th>Nominal</th>
                                <th>Jenis Tagihan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kategoriTagihans as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_kategori }}</td>
                                    <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                    <td>{{ ucfirst($item->jenis_tagihan) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $item->id }}"><i class="bi bi-pencil"></i></button>

                                        <form action="{{ route('kategori-tagihan.destroy', $item->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form class="modal-content" method="POST" action="{{ route('kategori-tagihan.update', $item->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Kategori</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nama Kategori</label>
                                                    <input type="text" name="nama_kategori" class="form-control" value="{{ $item->nama_kategori }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Nominal</label>
                                                    <input type="number" name="nominal" step="0.01" class="form-control" value="{{ $item->nominal }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Jenis Tagihan</label>
                                                    <select name="jenis_tagihan" class="form-control" required>
                                                        <option value="bulanan" {{ $item->jenis_tagihan == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                                        <option value="bebas" {{ $item->jenis_tagihan == 'bebas' ? 'selected' : '' }}>Bebas</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Tambah -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('kategori-tagihan.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nominal</label>
                        <input type="number" name="nominal" step="0.01" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jenis Tagihan</label>
                        <select name="jenis_tagihan" class="form-control" required>
                            <option value="bulanan">Bulanan</option>
                            <option value="bebas">Bebas</option>
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
