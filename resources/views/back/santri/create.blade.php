@extends('back.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Tambah Santri</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('santri.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- User -->
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select name="user_id" class="form-control" required>
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama_lengkap }}-{{ $user->email }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- NIS -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="nis">NIS</label>
                            <input type="text" name="nis" class="form-control" placeholder="Masukkan NIS" required value="{{ old('nis') }}">
                            @error('nis')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Nama -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama Santri" required value="{{ old('nama') }}">
                            @error('nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tempat Lahir -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" placeholder="Masukkan Tempat Lahir" required value="{{ old('tempat_lahir') }}">
                            @error('tempat_lahir')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" required value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" class="form-control" placeholder="Masukkan Alamat" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Program -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="program">Program</label>
                            <input type="text" name="program" class="form-control" placeholder="Masukkan Program" value="{{ old('program') }}">
                            @error('program')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Angkatan -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="angkatan">Angkatan</label>
                            <input type="text" name="angkatan" class="form-control" placeholder="Masukkan Angkatan" required value="{{ old('angkatan') }}">
                            @error('angkatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Sekolah -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="sekolah">Sekolah</label>
                            <input type="text" name="sekolah" class="form-control" placeholder="Masukkan Sekolah" value="{{ old('sekolah') }}">
                            @error('sekolah')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Foto -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="foto">Foto</label>
                            <input type="file" name="foto" class="form-control">
                            @error('foto')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="alumni" {{ old('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                <option value="keluar" {{ old('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="col-12 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary me-1 mb-1">Tambah Santri</button>
                    <a href="{{ route('santri.index') }}" class="btn btn-secondary me-1 mb-1">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection
