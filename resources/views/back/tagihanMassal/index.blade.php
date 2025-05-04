@extends('back.layouts.app')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5>Tambah Tagihan Massal</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('tagihan-massal.store') }}">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
                        <select id="jenis_pembayaran" name="jenis_pembayaran" class="form-select" required>
                            <option selected disabled>-- Pilih Jenis Pembayaran --</option>
                            @foreach($jenisPembayaran as $item)
                                <option value="{{ $item->id }}" data-nominal="{{ $item->nominal }}">
                                    {{ $item->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                        <select id="tahun_ajaran" name="tahun_ajaran" class="form-select" required>
                            @foreach($tahunAjaran as $item)
                                <option value="{{ $item->id }}">{{ $item->tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="periode" class="form-label">Jenis Periode</label>
                        <select id="periode" name="periode" class="form-select" required>
                            <option value="bulanan">Bulanan</option>
                            <option value="bebas">Bebas</option>
                        </select>
                    </div>
                    <div class="col-md-8" id="bulan-range">
                        <label class="form-label">Pilih Bulan</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bln)
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" id="bulan_{{ $bln }}" name="bulan[]" value="{{ $bln }}">
                                    <label class="form-check-label" for="bulan_{{ $bln }}">{{ $bln }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="angkatan" class="form-label">Filter Angkatan</label>
                        <select id="angkatan" name="angkatan" class="form-select">
                            <option value="">Semua Angkatan</option>
                            @foreach($angkatan as $item)
                                <option value="{{ $item->id }}">{{ $item->tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="program" class="form-label">Filter Program</label>
                        <select id="program" name="program" class="form-select">
                            <option value="">Semua Program</option>
                            @foreach($program as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_program }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status Santri</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="alumni">Alumni</option>
                        </select>
                    </div>
                </div>

                <div class="mb-2 form-check">
                    <input type="checkbox" class="form-check-input" id="ubah_nominal" name="ubah_nominal">
                    <label class="form-check-label" for="ubah_nominal">Ubah Nominal Secara Manual</label>
                </div>

                <div class="mb-3">
                    <label for="nominal" class="form-label">Nominal Tagihan</label>
                    <input type="text" id="nominal" name="nominal" class="form-control" placeholder="Rp" readonly required>
                </div>                

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Simpan Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    // Tampilkan input bulan jika periode = bulanan
    document.getElementById('periode').addEventListener('change', function () {
        document.getElementById('bulan-range').style.display = this.value === 'bulanan' ? 'block' : 'none';
    });

    // Set default visibilitas saat load
    window.addEventListener('DOMContentLoaded', function () {
        const periode = document.getElementById('periode').value;
        document.getElementById('bulan-range').style.display = periode === 'bulanan' ? 'block' : 'none';
    });

    // Isi nominal otomatis saat pilih jenis pembayaran
    document.getElementById('jenis_pembayaran').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const nominal = selected.getAttribute('data-nominal');
        const nominalInput = document.getElementById('nominal');
        const ubahManual = document.getElementById('ubah_nominal');

        if (nominal && !ubahManual.checked) {
            nominalInput.value = nominal;
            nominalInput.setAttribute('readonly', true);
        }
    });

    // Ubah nominal secara manual jika dicentang
    document.getElementById('ubah_nominal').addEventListener('change', function () {
        const nominalInput = document.getElementById('nominal');
        if (this.checked) {
            nominalInput.removeAttribute('readonly');
        } else {
            const selected = document.getElementById('jenis_pembayaran').selectedOptions[0];
            nominalInput.value = selected.getAttribute('data-nominal') || '';
            nominalInput.setAttribute('readonly', true);
        }
    });
</script>
@endsection
