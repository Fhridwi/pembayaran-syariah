@extends('back.layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h3 class="page-title">Statistik Pembayaran</h3>
    <form method="GET" class="d-flex align-items-center">
        <label for="bulan" class="me-2">Bulan:</label>
        <input type="month" name="bulan" id="bulan"
               value="{{ $bulan }}"
               class="form-control me-3"
               onchange="this.form.submit()">

        <label for="filter" class="me-2">Status:</label>
        <select id="filter" name="status" class="form-select me-2" onchange="this.form.submit()">
            <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua</option>
            <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="diterima" {{ $statusFilter == 'diterima' ? 'selected' : '' }}>Diterima</option>
            <option value="tolak" {{ $statusFilter == 'tolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </form>
</div>


    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Pembayaran</h6>
                            <h6 class="font-extrabold mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon blue mb-2">
                                <i class="iconly-boldProfile"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Menunggu</h6>
                            <h6 class="font-extrabold mb-0">{{ $menunggu }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon green mb-2">
                                <i class="iconly-boldAdd-User"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Diterima</h6>
                            <h6 class="font-extrabold mb-0">Rp {{ number_format($diterima, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon red mb-2">
                                <i class="iconly-boldBookmark"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Ditolak</h6>
                            <h6 class="font-extrabold mb-0">{{ $ditolak }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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


    <table class="table table-striped" id="table1">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Santri</th>
                <th>Tagihan</th>
                <th>Nominal</th>
                <th>Tanggal Bayar</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayarans as $pembayaran)
                @php
                    $statusClass = [
                        'pending' => 'warning text-dark',
                        'diterima' => 'success',
                        'tolak' => 'danger'
                    ];
                @endphp
                
                <tr>
                    <td>{{ $pembayaran->nomor_pembayaran }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg me-3">
                                <img src="{{ $pembayaran->santri->foto ? asset('storage/' . $pembayaran->santri->foto) : asset('assets/compiled/jpg/2.jpg') }}"
                                     alt="Foto Santri"
                                     class="rounded-circle"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ $pembayaran->santri->nama }}</span>
                                <span class="text-muted small">{{ $pembayaran->santri->program ?? '-' }}</span>
                            </div>
                        </div>
                    </td>                    
                    <td>{{ $pembayaran->tagihan->kategori->nama_kategori ?? '-' }}</td>
                    <td>Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($pembayaran->created_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') }}</td>
                    <td>
                        <span class="badge bg-{{ $pembayaran->metode_pembayaran == 'tunai' ? 'primary' : 'info' }}">
                            {{ ucfirst($pembayaran->metode_pembayaran) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $statusClass[$pembayaran->status] }}">
                            {{ ucfirst($pembayaran->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2 align-items-center">

                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                data-bs-target="#detailPembayaranModal{{ $pembayaran->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        
                            <!-- Tombol Cetak, Export XLS, Export PDF \-->
                            @if($pembayaran->status !== 'lunas') 
                                <a href="" target="_blank"
                                    class="btn btn-outline-primary {{ $pembayaran->status !== 'diterima' ? 'disabled' : '' }}">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <a href="" target="_blank"
                                    class="btn btn-outline-success {{ $pembayaran->status !== 'diterima' ? 'disabled' : '' }}">
                                    <i class="bi bi-file-earmark-spreadsheet"></i>
                                </a>
                                <a href="" target="_blank"
                                    class="btn btn-outline-danger {{ $pembayaran->status !== 'diterima' ? 'disabled' : '' }}">
                                    <i class="bi bi-file-earmark-pdf"></i> 
                                </a>
                            @endif
                        </div>
                        
                    
                        <form action="{{ route('konfirmasi-pembayaran.store') }}" method="post">
                            @csrf
                            <!-- Tambahkan input tersembunyi untuk pembayaran_id -->
                            <input type="hidden" name="pembayaran_id" value="{{ $pembayaran->id }}">
                            
                            <!-- Modal -->
                            <div class="modal fade" id="detailPembayaranModal{{ $pembayaran->id }}" tabindex="-1"
                                aria-labelledby="detailPembayaranModalLabel{{ $pembayaran->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailPembayaranModalLabel{{ $pembayaran->id }}">
                                                Detail Pembayaran #{{ $pembayaran->nomor_pembayaran }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                        
                                        <div class="modal-body">
                                            <!-- Informasi Santri & Pembayaran -->
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 border-end">
                                                            <h6><strong>Informasi Santri</strong></h6>
                                                            <div class="d-flex mb-3">
                                                                <div class="avatar avatar-lg me-3">
                                                                    <img src="{{ $pembayaran->santri->foto ? asset('storage/' . $pembayaran->santri->foto) : asset('assets/compiled/jpg/2.jpg') }}"
                                                                         alt="Foto Santri"
                                                                         class="rounded-circle"
                                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                                </div>
                                                                <div>
                                                                    <p class="mb-1"><strong>{{ $pembayaran->santri->nama }}</strong></p>
                                                                    <p class="mb-1 text-muted">{{ $pembayaran->santri->program ?? '-' }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <p class="mb-2"><small class="text-muted">NIS</small><br>{{ $pembayaran->santri->nis ?? '-' }}</p>
                                                                    <p class="mb-2"><small class="text-muted">No. HP Wali</small><br>{{ $pembayaran->santri->user->no_hp  ?? '-' }}</p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <p class="mb-2"><small class="text-muted">Wali Santri</small><br>{{ $pembayaran->santri->user->nama_lengkap ?? '-' }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                        
                                                        <!-- Informasi Pembayaran -->
                                                        <div class="col-md-6">
                                                            <h6><strong>Informasi Pembayaran</strong></h6>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <p class="mb-2"><small class="text-muted">Jenis Tagihan</small><br>{{ $pembayaran->tagihan->kategori->nama_kategori ?? '-' }}</p>
                                                                    <p class="mb-2"><small class="text-muted">Jumlah</small><br>Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}</p>
                                                                    <p class="mb-2"><small class="text-muted">Status</small><br>
                                                                        <span class="badge bg-{{ $statusClass[$pembayaran->status] }}">
                                                                            {{ ucfirst($pembayaran->status) }}
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <p class="mb-2"><small class="text-muted">Periode</small><br>{{ $pembayaran->tagihan->tahun->tahun_ajaran ?? '-' }}</p>
                                                                    <p class="mb-2"><small class="text-muted">Metode</small><br>{{ ucfirst($pembayaran->metode_pembayaran) }}</p>
                                                                    <p class="mb-2"><small class="text-muted">Tanggal Bayar</small><br>{{ \Carbon\Carbon::parse($pembayaran->created_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                        
                                            <!-- Bukti Pembayaran -->
                                            @if($pembayaran->bukti_bayar)
                                                @php
                                                    $buktiUrl = asset('storage/' . $pembayaran->bukti_bayar);
                                                    $extension = pathinfo($buktiUrl, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                @endphp
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <h6 class="mb-3"><strong>Bukti Pembayaran</strong></h6>
                                                        @if ($isImage)
                                                            <img src="{{ $buktiUrl }}" alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: contain; width: 100%;">
                                                        @else
                                                            <iframe src="{{ $buktiUrl }}" class="w-100 rounded shadow-sm" style="height: 500px; border: none;" title="Preview Bukti Pembayaran"></iframe>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                        
                                            <!-- Catatan Pembayaran -->
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <h6><strong>Catatan</strong></h6>
                                                    <textarea class="form-control" rows="2" placeholder="Tambahkan catatan jika diperlukan...">{{ $pembayaran->catatan ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                        
                                        <!-- Action Buttons in Modal -->
                                        <div class="d-flex justify-content-between mb-3 p-3 mx-4">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            @if($pembayaran->status === 'pending')
                                                <div>
                                                    <button name="action" value="tolak" class="btn btn-outline-danger me-2">Tolak Pembayaran</button>
                                                    <button name="action" value="terima" class="btn btn-success">Terima Pembayaran</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </form>
                        
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="card mb-4 col-md-6">
        <div class="card-header ">
            <h5>Statistik Status Pembayaran</h5>
        </div>
        <div class="card-body">
            <canvas id="statusChart" height="50px"></canvas>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    data: @json($chartData['data']),
                    backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
@endsection