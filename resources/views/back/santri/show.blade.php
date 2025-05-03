@extends('back.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Detail Santri</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- User -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="user_id">User</label>
                        <input type="text" class="form-control" value="{{ $santri->user->nama_lengkap }} - {{ $santri->user->email }}" disabled>
                    </div>
                </div>

                <!-- NIS -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="nis">NIS</label>
                        <input type="text" class="form-control" value="{{ $santri->nis }}" disabled>
                    </div>
                </div>

                <!-- Nama -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" value="{{ $santri->nama }}" disabled>
                    </div>
                </div>

                <!-- Jenis Kelamin -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <input type="text" class="form-control" value="{{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}" disabled>
                    </div>
                </div>

                <!-- Tempat Lahir -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" class="form-control" value="{{ $santri->tempat_lahir }}" disabled>
                    </div>
                </div>

                <!-- Tanggal Lahir -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="text" class="form-control" value="{{ $santri->tanggal_lahir }}" disabled>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" disabled>{{ $santri->alamat }}</textarea>
                    </div>
                </div>

                <!-- Program -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="program">Program</label>
                        <input type="text" class="form-control" value="{{ $santri->program }}" disabled>
                    </div>
                </div>

                <!-- Angkatan -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="angkatan">Angkatan</label>
                        <input type="text" class="form-control" value="{{ $santri->angkatan }}" disabled>
                    </div>
                </div>

                <!-- Sekolah -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="sekolah">Sekolah</label>
                        <input type="text" class="form-control" value="{{ $santri->sekolah }}" disabled>
                    </div>
                </div>

                <!-- Foto -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <div>
                            @if($santri->foto)
                                <img src="{{ asset('storage/' . $santri->foto) }}" alt="Foto Santri" class="img-thumbnail" width="150">
                            @else
                                <p>No photo available</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" class="form-control" value="{{ ucfirst($santri->status) }}" disabled>
                    </div>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="col-12 d-flex justify-content-end mt-3">
                <a href="{{ route('santri.index') }}" class="btn btn-secondary me-1 mb-1">Kembali</a>
            </div>
        </div>
    </div>
@endsection
