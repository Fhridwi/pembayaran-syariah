@extends('back.layouts.app')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Pengguna</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="bi bi-plus-circle"></i> Tambah User
                </button>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_lengkap }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->no_hp }}</td>
                                    <td>{{ $item->alamat }}</td>
                                    <td>
                                        @if ($item->role === 'admin')
                                            <span class="badge bg-primary">Admin</span>
                                        @elseif ($item->role === 'wali')
                                            <span class="badge bg-secondary">Wali</span>
                                        @elseif ($item->role === 'bendahara')
                                            <span class="badge bg-warning text-dark">Bendahara</span>
                                        @elseif ($item->role === 'pengasuh')
                                            <span class="badge bg-info text-dark">Pengasuh</span>
                                        @else
                                            <span class="badge bg-dark">{{ ucfirst($item->role) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editUserModal{{ $item->id }}" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('user.destroy', $item->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                        <!-- Modal Edit User -->
                                        <div class="modal fade" id="editUserModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="editUserModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <form action="{{ route('user.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit User</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body row g-3">
                                                            <div class="col-md-6">
                                                                <label>Nama Lengkap</label>
                                                                <input type="text" name="nama_lengkap" class="form-control"
                                                                    value="{{ $item->nama_lengkap }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Username</label>
                                                                <input type="text" name="username" class="form-control"
                                                                    value="{{ $item->username }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Email</label>
                                                                <input type="email" name="email" class="form-control"
                                                                    value="{{ $item->email }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>No HP</label>
                                                                <input type="text" name="no_hp" class="form-control"
                                                                    value="{{ $item->no_hp }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Alamat</label>
                                                                <input type="text" name="alamat" class="form-control"
                                                                    value="{{ $item->alamat }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Role</label>
                                                                <select name="role" class="form-select">
                                                                    <option value="admin" {{ $item->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                                    <option value="bendahara" {{ $item->role == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                                                    <option value="pengasuh" {{ $item->role == 'pengasuh' ? 'selected' : '' }}>Pengasuh</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer mt-3">
                                                            <button type="submit" class="btn btn-warning">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserModalLabel">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}"
                                required>
                            @error('nama_lengkap')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                            @error('username')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>No HP</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                            @error('no_hp')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}">
                            @error('alamat')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Role</label>
                            <select name="role" class="form-select" required>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="bendahara" {{ old('role') == 'bendahara' ? 'selected' : '' }}>Bendahara
                                </option>
                                <option value="pengasuh" {{ old('role') == 'pengasuh' ? 'selected' : '' }}>Pengasuh</option>
                            </select>
                            @error('role')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection