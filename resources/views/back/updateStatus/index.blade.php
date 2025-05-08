@extends('back.layouts.app')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Update Status Santri</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

            <div class="table-responsive">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Tunggakan</th>
                            <th>Status</th>
                            <th>Update</th>
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
                                <td>
                                    @if ($santri->punya_tunggakan)
                                        <span class="badge bg-danger">Ada Tunggakan</span>
                                    @else
                                        <span class="badge bg-success">Lunas</span>
                                    @endif
                                </td>                                
                                <td>
                                    @php
                                        $badgeClass = match($santri->status) {
                                            'aktif' => 'badge bg-success',
                                            'alumni' => 'badge bg-primary',
                                            'keluar' => 'badge bg-danger',
                                            default => 'badge bg-secondary'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">
                                        {{ ucfirst($santri->status) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('update.statusSantri') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="santri_id" value="{{ $santri->id }}">
                                        <select name="status" class="form-control" onchange="this.form.submit()">
                                            <option value="aktif" {{ $santri->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="alumni" {{ $santri->status === 'alumni' ? 'selected' : '' }}>Alumni</option>
                                            <option value="keluar" {{ $santri->status === 'keluar' ? 'selected' : '' }}>Keluar</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- Tombol WhatsApp --}}
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $santri->user->no_hp) }}" 
                                           target="_blank" class="btn btn-success btn-sm" title="Hubungi via WhatsApp">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                
                                        {{-- Tombol Cetak --}}
                                        <form action="" method="GET" target="_blank">
                                            <button type="submit" class="btn btn-info btn-sm" 
                                                {{ $santri->punya_tunggakan ? 'disabled' : '' }} title="Cetak Data">
                                                <i class="bi bi-printer"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
