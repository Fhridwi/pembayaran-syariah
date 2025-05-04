@extends('back.layouts.app')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Tagihan</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-circle"></i> Tambah Tagihan
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
                                <th>Santri</th>
                                <th>Tahun Ajaran</th>
                                <th>Kategori</th>
                                <th>Bulan</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tagihans as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->santri->nama ?? '-' }}</td>
                                    <td>{{ $item->tahun->tahun_ajaran ?? '-' }}</td>
                                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                    <td>{{ $item->bulan_tagihan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($item->status === 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $item->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('tagihan-santri.destroy', $item->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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

                {{-- Render All Modals After Table --}}
                @foreach ($tagihans as $item)
                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="{{ route('tagihan-santri.update', $item->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Tagihan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Santri</label>
                                        <select name="santri_id" class="form-select" required>
                                            @foreach ($santris as $santri)
                                                <option value="{{ $santri->id }}" {{ $santri->id == $item->santri_id ? 'selected' : '' }}>{{ $santri->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Tahun Ajaran</label>
                                        <select name="tahun_id" class="form-select" required>
                                            @foreach ($tahuns as $tahun)
                                                <option value="{{ $tahun->id }}" {{ $tahun->id == $item->tahun_id ? 'selected' : '' }}>{{ $tahun->tahun_ajaran }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Kategori</label>
                                        <select name="kategori_id" class="form-select" required>
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}" {{ $kategori->id == $item->kategori_id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Bulan Tagihan</label>
                                        <input type="text" name="bulan_tagihan" class="form-control"
                                            value="{{ $item->bulan_tagihan }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Jatuh Tempo</label>
                                        <input type="date" name="jatuh_tempo" class="form-control"
                                            value="{{ $item->jatuh_tempo }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="belum" {{ $item->status == 'belum' ? 'selected' : '' }}>Belum</option>
                                            <option value="lunas" {{ $item->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('tagihan-santri.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tagihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Santri</label>
                        <select name="santri_id" class="form-select" required>
                            @foreach ($santris as $santri)
                                <option value="{{ $santri->id }}">{{ $santri->nama }} - {{ $santri->user->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tahun Ajaran</label>
                        <select name="tahun_id" class="form-select" required>
                            @foreach ($tahuns as $tahun)
                                <option value="{{ $tahun->id }}">{{ $tahun->tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="kategori_id" class="form-select" required>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Bulan Tagihan</label>
                        <input type="text" name="bulan_tagihan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jatuh Tempo</label>
                        <input type="date" name="jatuh_tempo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select" required>
                            <option value="belum">Belum</option>
                            <option value="lunas">Lunas</option>
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