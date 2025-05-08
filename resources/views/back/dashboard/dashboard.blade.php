@extends('back.layouts.app')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard Pembayaran Pesantren</h3>
                <p class="text-subtitle text-muted">Monitoring Sistem Pembayaran Terintegrasi</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <!-- Total Pembayaran -->
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldWallet"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Pembayaran</h6>
                            <h6 class="font-extrabold mb-0">Rp 850JT</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Santri -->
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="iconly-boldProfile"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Santri</h6>
                            <h6 class="font-extrabold mb-0">1.240</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total User -->
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="iconly-boldAdd-User"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total User</h6>
                            <h6 class="font-extrabold mb-0">850</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaksi Pending -->
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon red mb-2">
                                <i class="iconly-boldDanger"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Pending</h6>
                            <h6 class="font-extrabold mb-0">45</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Statistik Pembayaran 2024</h4>
                </div>
                <div class="card-body">
                    <div id="chart-pembayaran"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Transaksi Terakhir</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-lg">
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Santri</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>TRX-001</td>
                                    <td>Abdul Rahman</td>
                                    <td>Rp 1.250.000</td>
                                    <td><span class="badge bg-success">Lunas</span></td>
                                </tr>
                                <!-- Tambahkan data lainnya -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Aktivitas Terbaru -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Aktivitas Terbaru</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-light-primary p-2 me-2">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                                <span>Pembayaran SPP Januari</span>
                            </div>
                        </li>
                        <!-- Tambahkan aktivitas lainnya -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Script untuk chart -->
<script>
    // Contoh implementasi chart menggunakan ApexCharts
    var options = {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'Pembayaran',
            data: [4000000, 5500000, 7100000, 4800000, 6200000, 8000000]
        }],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']
        }
    }

    var chart = new ApexCharts(document.querySelector("#chart-pembayaran"), options);
    chart.render();
</script>
@endpush