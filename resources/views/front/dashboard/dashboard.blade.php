<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan - Sistem Pembayaran Pesantren</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        },
                        secondary: {
                            500: '#3b82f6',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        /* Animation for modals */
        #paymentModal, #successModal {
            transition: opacity 0.3s ease;
        }
        
        /* Payment method selection style */
        input[type="radio"]:checked + div {
            border-color: #10b981;
            background-color: #f0fdf4;
        }
        
        /* Hover effect for payment methods */
        label:hover {
            border-color: #a7f3d0;
        }
        
        /* Ensure modal doesn't go behind bottom nav */
        .modal-container {
            z-index: 60;
            padding-bottom: 80px; /* Space for bottom nav */
        }
    </style>
</head>

@if ($errors->any())
    <div 
        id="errorAlert" 
        class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md px-4"
    >
        <div class="bg-red-50 border border-red-300 text-red-800 text-sm rounded-lg p-4 shadow-lg relative">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button 
                    type="button" 
                    class="ml-4 text-red-700 hover:text-red-900" 
                    onclick="document.getElementById('errorAlert').remove()"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif


<body class="bg-gray-50 min-h-screen font-sans">
    <div class="container mx-auto px-4 pb-24 max-w-md">
        <!-- Header dengan gradasi -->
        <header class="bg-gradient-to-r from-primary-600 to-primary-700 p-6 rounded-b-2xl shadow-md mb-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">Tagihan Santri</h1>
                    <p class="text-primary-100">{{ $activeSantri->nama ?? '' }} - {{ $activeSantri->program ?? '' }}</p>
                </div>
                <div class="bg-white/20 p-2 rounded-full">
                  <a href="">{{ $tahunAjaran->tahun_ajaran }}</a>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl mt-6 border border-white/20">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-primary-100">Total Tagihan</span>
                    <span class="text-md font-bold">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                </div>
                @if(($belumLunasCount + $lunasCount) > 0)
                    <div class="w-full bg-white/30 rounded-full h-2 mb-2">
                        <div class="bg-white h-2 rounded-full"
                            style="width: {{ ($lunasCount / ($belumLunasCount + $lunasCount)) * 100 }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-primary-100">
                        <span>{{ $belumLunasCount }} belum lunas</span>
                        <span>{{ $lunasCount }} lunas</span>
                    </div>
                @endif
            </div>
        </header>

        <!-- Filter Section - Updated -->
        <div class="flex space-x-2 mb-4 overflow-x-auto scrollbar-hide pb-2">
            @php
                $baseSantriId = $activeSantri->id ?? '';
            @endphp
            <a href="?filter=semua&santri_id={{ $baseSantriId }}"
                class="px-4 py-2 rounded-full whitespace-nowrap {{ $activeFilter === 'semua' ? 'bg-primary-500 text-white' : 'bg-white border border-gray-200' }}">
                Semua
            </a>
            <a href="?filter=belum&santri_id={{ $baseSantriId }}"
                class="px-4 py-2 rounded-full whitespace-nowrap {{ $activeFilter === 'belum' ? 'bg-primary-500 text-white' : 'bg-white border border-gray-200' }}">
                Belum Lunas
            </a>
            {{-- <a href="?filter=lunas&santri_id={{ $baseSantriId }}"
                class="px-4 py-2 rounded-full whitespace-nowrap {{ $activeFilter === 'lunas' ? 'bg-primary-500 text-white' : 'bg-white border border-gray-200' }}">
                Sudah Lunas
            </a>
            <a href="?filter=bulan-ini&santri_id={{ $baseSantriId }}"
                class="px-4 py-2 rounded-full whitespace-nowrap {{ $activeFilter === 'bulan-ini' ? 'bg-primary-500 text-white' : 'bg-white border border-gray-200' }}">
                Diproses
            </a> --}}
        </div>

        <!-- Santri Selection Dropdown -->
        @if($santri->count() > 1)
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <label class="block text-gray-700 mb-2">Pilih Santri</label>
        <select name="santri_id" id="santriDropdown" 
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-primary-500"
                onchange="handleSantriChange(this)">
            @foreach($santri as $s)
                <option value="{{ $s->id }}" {{ $s->id == $activeSantri->id ? 'selected' : '' }} 
                        data-santri='@json($s)'>
                    {{ $s->nama }} - {{ $s->kelas ?? $s->program }}
                </option>
            @endforeach
        </select>
    </div>
@endif


        <!-- List Tagihan -->
        <div class="space-y-3 mb-20"> <!-- Added margin bottom for floating button -->
            @forelse($activeSantri->tagihans ?? [] as $tagihan)
                <div
                    class="bg-white p-5 rounded-xl shadow-sm border-l-4 {{ $tagihan->status == 'lunas' ? 'border-green-500' : 'border-red-500' }} hover:shadow-md transition-all">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $tagihan->kategori->nama ?? 'Tagihan' }}
                                {{ $tagihan->bulan_tagihan }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">No. Tagihan: {{ $tagihan->id }}</p>
                        </div>
                        <span
                            class="px-2 py-1 rounded-full text-xs font-medium {{ $tagihan->status == 'lunas' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} text-sm">
                            {{ $tagihan->status == 'lunas' ? 'Lunas' : 'Belum' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm mb-3">
                        <div class="flex items-center text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d M Y') }}</span>
                        </div>
                        <span class="font-bold text-gray-800">Rp
                            {{ number_format($tagihan->kategori->nominal ?? 0, 0, ',', '.') }}</span>
                    </div>

                    @if($tagihan->status == 'belum')
                        <div class="flex space-x-2">
                            <button 
                                onclick="openPaymentModal('{{ $tagihan->kategori->nama }} {{ $tagihan->bulan_tagihan }}', '{{ $tagihan->kategori->nominal }}', '{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d M Y') }}', '{{ $tagihan->id }}')"
                                class="flex-1 bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Bayar
                            </button>
                            <button
                                class="w-10 h-10 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    @else
                        <div class="flex items-center text-sm text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Dibayar pada
                                {{ \Carbon\Carbon::parse($tagihan->updated_at)->translatedFormat('d M Y H:i') }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white p-6 rounded-xl text-center">
                    <p class="text-gray-500">Tidak ada tagihan ditemukan</p>
                </div>
            @endforelse
        </div>

        <!-- Floating Action Button -->
        <div class="fixed bottom-24 right-4 z-40">
            <button
                class="bg-primary-500 text-white p-4 rounded-full shadow-lg hover:bg-primary-600 transition transform hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>

        <form id="paymentForm" action="{{ route('wali.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Payment Modal -->
            <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 modal-container">
                <div class="bg-white rounded-xl w-full max-w-md max-h-[80vh] overflow-y-auto">
                    <div class="sticky top-0 bg-white p-6 pb-4 shadow-sm z-10">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-800">Pembayaran Tagihan</h3>
                            <button type="button" onclick="closePaymentModal()" class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 pt-0 space-y-4">
                        <!-- Data Tersembunyi -->
                        <input type="hidden" name="santri_id" id="inputSantriId">
                        <input type="hidden" name="tagihan_id" id="inputTagihanId">
                        <input type="hidden" name="nominal_bayar" id="inputNominalBayar">
        
                        <!-- Informasi Tagihan -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700" id="paymentTitle">SPP Juli 2024</h4>
                            <div class="flex justify-between mt-2">
                                <span class="text-gray-600">Jumlah:</span>
                                <span class="font-bold" id="paymentAmount">Rp 300.000</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-gray-600">Jatuh Tempo:</span>
                                <span id="paymentDueDate">10 Juli 2024</span>
                            </div>
                        </div>
                        
                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-gray-700 mb-2">Metode Pembayaran</label>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 p-3 border rounded-lg hover:border-primary-500 cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" value="transfer" checked class="h-5 w-5 text-primary-500">
                                    <div>
                                        <span class="block font-medium">Transfer Bank</span>
                                        <span class="block text-sm text-gray-500">BCA, BRI, Mandiri, dll</span>
                                    </div>
                                </label>
                                <label class="flex items-center space-x-3 p-3 border rounded-lg hover:border-primary-500 cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" value="qris" class="h-5 w-5 text-primary-500">
                                    <div>
                                        <span class="block font-medium">QRIS</span>
                                        <span class="block text-sm text-gray-500">Scan QR Code</span>
                                    </div>
                                </label>
                                <label class="flex items-center space-x-3 p-3 border rounded-lg hover:border-primary-500 cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" value="tunai" class="h-5 w-5 text-primary-500">
                                    <div>
                                        <span class="block font-medium">Tunai di Kantor</span>
                                        <span class="block text-sm text-gray-500">Bayar langsung ke bendahara</span>
                                    </div>
                                </label>
                            </div>
                        </div>
        
                        <!-- Transfer Section -->
                        <div id="transferDetails" class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-800 mb-4">Transfer Bank:</h4>
                            <div class="text-sm text-blue-700 space-y-2">
                                <p>Bank Tujuan: <span class="font-semibold" id="bankName">BCA</span></p>
                                <p>Nomor Rekening: 
                                    <span class="font-semibold cursor-pointer text-blue-900" id="bankAccountNumber" onclick="copyToClipboard('#bankAccountNumber')">
                                        1234567890
                                    </span>
                                    <button type="button" onclick="copyToClipboard('#bankAccountNumber')" class="ml-2 text-xs bg-blue-200 px-2 py-0.5 rounded">Salin</button>
                                </p>
                                
                                <div class="mt-4">
                                    <label for="bankPengirim" class="block font-medium text-blue-800">Bank Pengirim:</label>
                                    <input type="text" id="bankPengirim" name="bank_pengirim" class="mt-1 block w-full p-2 border border-blue-300 rounded-lg bg-white focus:outline-none">
                                </div>
                                
                                <div class="mt-2">
                                    <label for="namaPengirim" class="block font-medium text-blue-800">Nama Pengirim:</label>
                                    <input type="text" id="namaPengirim" name="nama_pengirim" class="mt-1 block w-full p-2 border border-blue-300 rounded-lg bg-white focus:outline-none">
                                </div>
                                
                                <label for="buktiTransfer" class="block mt-4 font-medium text-blue-800">Upload Bukti Transfer:</label>
                                <input type="file" id="buktiTransfer" name="bukti_bayar" accept="image/*" class="mt-1 block w-full text-sm text-blue-700 border border-blue-300 rounded-lg cursor-pointer bg-white focus:outline-none">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB.</p>
                            </div>
                        </div>
        
                        <!-- QRIS Section -->
                        <div id="qrisDetails" class="hidden bg-green-50 p-4 rounded-lg text-center">
                            <h4 class="font-medium text-green-800 mb-3">Scan QR Code Berikut:</h4>
                            <div class="bg-white p-4 inline-block rounded-lg mb-3">
                                <div class="w-40 h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                                    [QR Code]
                                </div>
                            </div>
                            <p class="text-sm text-green-700">Jumlah: <span id="qrisAmount">Rp 300.000</span></p>
                            <input type="hidden" name="metode_pembayaran_qris" value="qris">
                        </div>
        
                        <!-- Tunai Section -->
                        <div id="cashDetails" class="hidden bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-medium text-yellow-800 mb-3">Pembayaran Tunai</h4>
                            <p class="text-sm text-yellow-700">Silakan datang ke kantor untuk melakukan pembayaran tunai.</p>
                            <input type="hidden" name="metode_pembayaran_cash" value="tunai">
                        </div>
        
                        <!-- Tombol Submit -->
                        <div class="pt-4 sticky bottom-0 pb-4">
                            <button type="submit" onclick="return validatePayment()" class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Konfirmasi Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- <!-- Success Modal -->
        <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 modal-container">
            <div class="bg-white rounded-xl w-full max-w-md p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Pembayaran Berhasil!</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Pembayaran untuk tagihan <span id="successInvoice" class="font-medium">INV202407001</span> telah berhasil diproses.
                </p>
                <button onclick="closeSuccessModal()" class="w-full bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-lg transition duration-200">
                    Tutup
                </button>
            </div>
        </div> --}}

        <!-- Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-around py-3 px-4 z-50">
            <a href="#" class="text-primary-500 flex flex-col items-center">
                <!-- Beranda Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs mt-1">Beranda</span>
            </a>
            <a href="#" class="text-gray-500 flex flex-col items-center">
                <!-- Tagihan Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs mt-1">Tagihan</span>
            </a>
            <a href="#" class="text-gray-500 flex flex-col items-center">
                <!-- Riwayat Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-xs mt-1">Riwayat</span>
            </a>
            <a href="#" class="text-gray-500 flex flex-col items-center">
                <!-- Profil Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs mt-1">Profil</span>
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="text-gray-500 flex flex-col items-center">
                <!-- Logout Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-8V7a2 2 0 114 0v1" />
                </svg>
                <span class="text-xs mt-1">Logout</span>
            </a>

            <!-- Hidden Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </div>

    <script>
        // Dropdown redirect saat pilih santri
        document.querySelector('select')?.addEventListener('change', function () {
            window.location.href = `/wali/dashboard?santri_id=${this.value}`;
        });
        
        // Buka modal pembayaran
        function openPaymentModal(title, amount, dueDate, tagihanId, santriId) {
            // Set teks dan format rupiah
            const formattedAmount = 'Rp ' + parseInt(amount).toLocaleString('id-ID');
            document.getElementById('paymentTitle').textContent = title;
            document.getElementById('paymentAmount').textContent = formattedAmount;
            document.getElementById('qrisAmount').textContent = formattedAmount;
            document.getElementById('paymentDueDate').textContent = dueDate;
        
            // Isi input hidden form
            document.getElementById('inputSantriId').value = santriId;
            document.getElementById('inputTagihanId').value = tagihanId;
            document.getElementById('inputNominalBayar').value = amount;
        
            // Reset metode pembayaran default ke transfer
            document.querySelector('input[name="metode_pembayaran"][value="transfer"]').checked = true;
            showPaymentDetails('transfer');
        
            // Tampilkan modal
            document.getElementById('paymentModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        // Tutup modal
        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Handle perubahan radio button metode pembayaran
        document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
            radio.addEventListener('change', function () {
                showPaymentDetails(this.value);
            });
        });
        
        // Tampilkan bagian detail pembayaran sesuai metode
        function showPaymentDetails(method) {
    const transfer = document.getElementById('transferDetails');
    const qris = document.getElementById('qrisDetails');
    const cash = document.getElementById('cashDetails');

    // Sembunyikan semua bagian pembayaran
    transfer.classList.add('hidden');
    qris.classList.add('hidden');
    cash.classList.add('hidden');

    // Hapus input hidden untuk metode yang tidak dipilih
    let qrisInput = document.querySelector('input[name="metode_pembayaran_qris"]');
    let cashInput = document.querySelector('input[name="metode_pembayaran_cash"]');

    if (qrisInput) {
        qrisInput.remove();
    }

    if (cashInput) {
        cashInput.remove();
    }

    // Tambahkan bagian yang sesuai dengan metode yang dipilih
    if (method === 'transfer') {
        transfer.classList.remove('hidden');
    } else if (method === 'qris') {
        qris.classList.remove('hidden');
        // Tambahkan input hidden untuk QRIS
        const inputQris = document.createElement('input');
        inputQris.type = 'hidden';
        inputQris.name = 'metode_pembayaran_qris';
        inputQris.value = 'qris';
        document.getElementById('paymentForm').appendChild(inputQris);
    } else if (method === 'tunai') {
        cash.classList.remove('hidden');
        // Tambahkan input hidden untuk tunai
        const inputCash = document.createElement('input');
        inputCash.type = 'hidden';
        inputCash.name = 'metode_pembayaran_cash';
        inputCash.value = 'tunai';
        document.getElementById('paymentForm').appendChild(inputCash);
    }
}


        
        // Validasi sebelum submit
        function validatePayment() {
            const metode = document.querySelector('input[name="metode_pembayaran"]:checked').value;
            const bukti = document.getElementById('buktiTransfer');
        
            if (metode === 'transfer' && (!bukti || !bukti.files.length)) {
                alert('Mohon upload bukti transfer terlebih dahulu.');
                return false;
            }
        
            return true; // Lolos validasi, bisa submit
        }
        
        // Salin no rekening ke clipboard
        function copyToClipboard(selector) {
            const el = document.querySelector(selector);
            if (!el) return;
            
            const range = document.createRange();
            range.selectNodeContents(el);
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        
            try {
                document.execCommand('copy');
                alert('Nomor rekening berhasil disalin!');
            } catch (err) {
                alert('Gagal menyalin');
            }
            sel.removeAllRanges();
        }
        
        // Tutup modal jika klik di luar kontennya
        window.addEventListener('click', function (event) {
            const modal = document.getElementById('paymentModal');
            if (event.target === modal) {
                closePaymentModal();
            }
        });

        function handleSantriChange(selectElement) {
        try {
            const selectedValue = selectElement.value;
            const activeFilter = "{{ $activeFilter }}"; // Dari controller
            
            if (!selectedValue || selectedValue === 'undefined') {
                throw new Error('Pilihan santri tidak valid');
            }

            // Redirect dengan parameter filter dan santri_id
            window.location.href = `?filter=${activeFilter}&santri_id=${selectedValue}`;
            
            // Simpan pilihan terakhir di localStorage
            localStorage.setItem('lastSelectedSantri', selectedValue);
            
        } catch (error) {
            console.error('Error handling santri change:', error);
            alert('Terjadi kesalahan saat memilih santri');
            selectElement.value = getCurrentSantriId(); // Reset ke nilai sebelumnya
        }
    }

    // Fungsi untuk mendapatkan santri_id yang aktif
    function getCurrentSantriId() {
        // 1. Cek dari URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const urlSantriId = urlParams.get('santri_id');
        
        // 2. Cek dari localStorage
        const storedSantriId = localStorage.getItem('lastSelectedSantri');
        
        // 3. Cek dari data activeSantri di Blade
        const defaultSantriId = "{{ $activeSantri->id ?? '' }}";
        
        // Prioritas: URL > LocalStorage > Default
        return urlSantriId || storedSantriId || defaultSantriId;
    }

    // Inisialisasi dropdown saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.getElementById('santriDropdown');
        if (dropdown) {
            // Set nilai dropdown berdasarkan current santri_id
            const currentSantriId = getCurrentSantriId();
            if (currentSantriId) {
                dropdown.value = currentSantriId;
            }
            
            // Tambahkan data santri ke global variable untuk akses di fungsi lain
            const selectedOption = dropdown.options[dropdown.selectedIndex];
            if (selectedOption) {
                window.currentSantri = JSON.parse(selectedOption.getAttribute('data-santri'));
            }
        }
    });

    // Fungsi untuk mendapatkan santri_id yang aktif (digunakan di openPaymentModal)
    function getActiveSantriId() {
        const dropdown = document.getElementById('santriDropdown');
        
        // 1. Cek dari dropdown jika ada
        if (dropdown && dropdown.value && dropdown.value !== 'undefined') {
            return dropdown.value;
        }
        
        // 2. Fallback ke cara lain
        return getCurrentSantriId();
    }
        </script>
        
</body>
</html>