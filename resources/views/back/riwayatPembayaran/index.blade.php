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
            <select name="santri_id" class="form-select">
                <option value="">Pilih Santri</option>
                @foreach ($santris as $santri)
                <option value="{{ $santri->id }}" {{ request('santri_id') == $santri->id ? 'selected' : '' }}>
                    {{ $santri->nama }}
                </option>
                @endforeach
            </select>

            <select name="status" class="form-select">
                <option value="">Pilih Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="tolak" {{ request('status') == 'tolak' ? 'selected' : '' }}>Tolak</option>
            </select>

            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <!-- Tabel Riwayat Pembayaran -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Pembayaran</th>
                        <th>Santri</th>
                        <th>Status</th>
                        <th>Tanggal Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayats as $pembayaran)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pembayaran->nomor_pembayaran }}</td>
                        <td>{{ $pembayaran->santri->nama ?? '-' }}</td>
                        <td>
                            <span class="badge
                                {{ $pembayaran->status == 'pending' ? 'bg-warning' : 
                                   ($pembayaran->status == 'diterima' ? 'bg-success' : 'bg-danger') }}">
                                {{ ucfirst($pembayaran->status) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y H:i') }}</td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
