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

<div class="card">
    <div class="card-header d-flex justify-content-between item">
        <h5 class="card-title">Riwayat Pembayaran</h5>
    </div>

    <div class="card-body">
        <!-- Form Filter -->
        <form method="GET" action="{{ route('riwayat-pembayaran.index') }}" class="d-flex gap-2 mb-3">
            <select name="santri_id" class="form-select" onchange="this.form.submit()">
                <option value="">Pilih Santri</option>
                @foreach ($santris as $santri)
                <option value="{{ $santri->id }}" {{ request('santri_id') == $santri->id ? 'selected' : '' }}>
                    {{ $santri->nama }}
                </option>
                @endforeach
            </select>
        
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Pilih Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="tolak" {{ request('status') == 'tolak' ? 'selected' : '' }}>Tolak</option>
            </select>
        
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" onchange="this.form.submit()">
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" onchange="this.form.submit()">
        </form>
        
        <!-- Tabel Riwayat -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor</th>
                        <th>Santri</th>
                        <th>Jenis Pembayaran</th>
                        <th>Bulan Tagihan</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayats as $pembayaran)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pembayaran->nomor_pembayaran }}</td>
                        <td>{{ $pembayaran->santri->nama ?? '-' }}</td>
                        <td>{{ $pembayaran->tagihan->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $pembayaran->tagihan->bulan_tagihan }}</td>
                        <td>Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge 
                                {{ $pembayaran->status == 'pending' ? 'bg-warning' : 
                                   ($pembayaran->status == 'diterima' ? 'bg-success' : 'bg-danger') }}">
                                {{ ucfirst($pembayaran->status) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y H:i') }}</td>
                        <td>
                            @if ($pembayaran->bukti_bayar)
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $pembayaran->id }}">
                                    Lihat
                                </button>
                        
                                <!-- Modal -->
                                <div class="modal fade" id="modalBukti{{ $pembayaran->id }}" tabindex="-1" aria-labelledby="modalBuktiLabel{{ $pembayaran->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalBuktiLabel{{ $pembayaran->id }}">Bukti Pembayaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" class="img-fluid rounded shadow" alt="Bukti Pembayaran">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        
                        <td>
                            @if ($pembayaran->status === 'diterima')
                                <a href="" class="btn btn-sm btn-primary">Cetak</a>
                            @elseif ($pembayaran->status === 'tolak')
                                <span class="text-danger">{{ $pembayaran->keterangan_status ?? 'Tidak ada alasan' }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
