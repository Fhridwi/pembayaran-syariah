@extends('back.layouts.app')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5>Tambah Pembayaran Santri</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form method="POST" action="{{ route('pembayaran.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="santri" class="form-label">Santri</label>
                            <select id="santri" name="santri_id"
                                class="form-select @error('santri_id') is-invalid @enderror" required>
                                <option selected disabled>-- Pilih Santri --</option>
                                @foreach($santris as $item)
                                    <option value="{{ $item->id }}" {{ old('santri_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('santri_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
                            <select id="jenis_pembayaran" name="jenis_pembayaran"
                                class="form-select @error('jenis_pembayaran') is-invalid @enderror" required>
                                <option selected disabled>-- Pilih Jenis Pembayaran --</option>
                                @foreach($jenisPembayaran as $item)
                                    <option value="{{ $item->id }}" data-nominal="{{ $item->nominal }}" {{ old('jenis_pembayaran') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="nominal_pembayaran" class="form-label">Nominal Pembayaran</label>
                            <input type="text" id="nominal_pembayaran" name="nominal_pembayaran"
                                class="form-control @error('nominal_pembayaran') is-invalid @enderror" placeholder="Rp"
                                readonly required value="{{ old('nominal_pembayaran') }}">
                            @error('nominal_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" id="tanggal_pembayaran" name="tanggal_pembayaran"
                                class="form-control @error('tanggal_pembayaran') is-invalid @enderror" required
                                value="{{ old('tanggal_pembayaran') }}">
                            @error('tanggal_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="tagihan-checkboxes" class="mb-3">
                        <!-- Checkbox tagihan akan dimuat di sini via JS -->
                    </div>

                    <div class="mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select id="metode_pembayaran" name="metode_pembayaran"
                            class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                            <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer
                            </option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bukti_transfer" class="form-label">Bukti Transfer (Opsional jika tunai)</label>
                        <input type="file" id="bukti_transfer" name="bukti_transfer"
                            class="form-control @error('bukti_transfer') is-invalid @enderror">
                        @error('bukti_transfer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">Simpan Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('jenis_pembayaran').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const nominal = selectedOption.getAttribute('data-nominal');
            const inputNominal = document.getElementById('nominal_pembayaran');

            inputNominal.value = nominal ? formatRupiah(nominal) : '';
        });

        function formatRupiah(angka) {
            return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
        }

        document.getElementById('santri').addEventListener('change', function () {
            const santriId = this.value;
            if (santriId) {
                fetch(`{{ url('admin/pembayaran/tagihan') }}/${santriId}`)
                    .then(response => response.json())
                    .then(data => {
                        const tagihanCheckboxes = document.getElementById('tagihan-checkboxes');
                        tagihanCheckboxes.innerHTML = '';

                        data.forEach(tagihan => {
                            const checkboxDiv = document.createElement('div');
                            checkboxDiv.classList.add('form-check', 'me-3');

                            const checkboxInput = document.createElement('input');
                            checkboxInput.classList.add('form-check-input');
                            checkboxInput.type = 'checkbox';
                            checkboxInput.id = 'tagihan_' + tagihan.id;
                            checkboxInput.name = 'tagihan[]';  // Mengubah name untuk mendukung banyak tagihan
                            checkboxInput.value = tagihan.id;

                            const checkboxLabel = document.createElement('label');
                            checkboxLabel.classList.add('form-check-label');
                            checkboxLabel.setAttribute('for', 'tagihan_' + tagihan.id);
                            checkboxLabel.textContent = `${tagihan.bulan_tagihan} - ${tagihan.kategori.nama_kategori}`;

                            checkboxDiv.appendChild(checkboxInput);
                            checkboxDiv.appendChild(checkboxLabel);
                            tagihanCheckboxes.appendChild(checkboxDiv);
                        });
                    })
                    .catch(error => console.error('Error fetching tagihan:', error));
            }
        });
    </script>
@endsection