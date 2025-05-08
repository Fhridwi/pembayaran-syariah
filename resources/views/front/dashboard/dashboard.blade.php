<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan - Sistem Pembayaran Pesantren</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        .modal {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .modal.hidden {
            opacity: 0;
            transform: translateY(20px);
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
@if (session('success'))
    <div 
        id="success-alert" 
        class="fixed top-5 right-5 z-50 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3"
        role="alert"
    >
        <svg class="w-6 h-6 mt-1 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <div class="flex-1">
            <p class="font-semibold">{{ session('success') }}</p>
        </div>
        <button onclick="document.getElementById('success-alert').remove()" class="text-green-700 hover:text-green-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
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

        <!-- Filter Section -->
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
        </div>

        <!-- Santri Selection Dropdown -->
        @if($santri->count() > 1)
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <label class="block text-gray-700 mb-2">Pilih Santri</label>
                <select name="santri_id" id="santriDropdown" 
                        class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
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
        <div class="space-y-3 mb-20">
            @forelse($activeSantri->tagihans ?? [] as $tagihan)
                @php
                    // Determine status class based on tagihan status
                    $statusClasses = [
                        'lunas' => [
                            'border' => 'border-green-500',
                            'bg' => 'bg-green-100',
                            'text' => 'text-green-600',
                            'label' => 'Lunas'
                        ],
                        'diproses' => [
                            'border' => 'border-yellow-500',
                            'bg' => 'bg-yellow-100',
                            'text' => 'text-yellow-600',
                            'label' => 'Diproses'
                        ],
                        'belum' => [
                            'border' => 'border-red-500',
                            'bg' => 'bg-red-100',
                            'text' => 'text-red-600',
                            'label' => 'Belum'
                        ]
                    ];
                    
                    $status = $tagihan->status;
                    $statusConfig = $statusClasses[$status] ?? $statusClasses['belum'];
                @endphp
        
                <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 {{ $statusConfig['border'] }} hover:shadow-md transition-all">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $tagihan->kategori->nama }}
                                {{ $tagihan->bulan_tagihan }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">No. Tagihan: {{ $tagihan->id }}</p>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} text-sm">
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm mb-3">
                        <div class="flex items-center text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d M Y') }}</span>
                        </div>
                        <span class="font-bold text-gray-800">Rp {{ number_format($tagihan->kategori->nominal ?? 0, 0, ',', '.') }}</span>
                    </div>
        
                    @if($tagihan->status == 'belum')
                        <div class="flex space-x-2">
                            <button onclick="openPaymentModal(this, '{{ $tagihan->kategori->nama }} {{ $tagihan->bulan_tagihan }}', '{{ $tagihan->kategori->nominal }}', '{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d M Y') }}', '{{ $tagihan->id }}')"
                                class="flex-1 bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Bayar
                            </button>
                            <button class="w-10 h-10 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    @elseif($tagihan->status == 'diproses')
                        <div class="flex items-center text-sm text-yellow-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Sedang diproses - {{ \Carbon\Carbon::parse($tagihan->updated_at)->translatedFormat('d M Y H:i') }}</span>
                        </div>
                    @else
                        <div class="flex items-center text-sm text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Dibayar pada {{ \Carbon\Carbon::parse($tagihan->updated_at)->translatedFormat('d M Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white p-6 rounded-xl text-center">
                    <p class="text-gray-500">Tidak ada tagihan ditemukan</p>
                </div>
            @endforelse
        </div>

        <!-- Payment Form Modal -->
        <form id="paymentForm" action="{{ route('wali.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 modal-container modal">
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
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="santri_id" id="inputSantriId">
                        <input type="hidden" name="tagihan_id" id="inputTagihanId">
                        <input type="hidden" name="nominal_bayar" id="inputNominalBayar">

                        <!-- Tagihan Info -->
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
                        
                        <!-- Payment Methods -->
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

                        <!-- Transfer Details -->
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

                        <!-- QRIS Details -->
                        <div id="qrisDetails" class="hidden bg-green-50 p-4 rounded-lg text-center">
                            <h4 class="font-medium text-green-800 mb-3">Scan QR Code Berikut:</h4>
                            <div class="bg-white p-4 inline-block rounded-lg mb-3">
                                <div class="w-40 h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                                    [QR Code]
                                </div>
                            </div>
                            <p class="text-sm text-green-700">Jumlah: <span id="qrisAmount">Rp 300.000</span></p>
                        </div>

                        <!-- Cash Details -->
                        <div id="cashDetails" class="hidden bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-medium text-yellow-800 mb-3">Pembayaran Tunai</h4>
                            <p class="text-sm text-yellow-700">Silakan datang ke kantor untuk melakukan pembayaran tunai.</p>
                        </div>

                        <!-- Submit Button -->
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

        <!-- Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-around py-3 px-4 z-50">
            <a href="{{ route('user.dashboard') }}" class="text-primary-500 flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs mt-1">Beranda</span>
            </a>
            <a href="{{ route('user.riwayat.index') }}" class="text-gray-500 flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-xs mt-1">Riwayat</span>
            </a>
            <a href="#" class="text-gray-500 flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs mt-1">Profil</span>
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-gray-500 flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-8V7a2 2 0 114 0v1" />
                </svg>
                <span class="text-xs mt-1">Logout</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </div>

    <script>
        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initSantriDropdown();
            initPaymentMethods();
        });

        // Santri Dropdown Handling
        function initSantriDropdown() {
            const dropdown = document.getElementById('santriDropdown');
            if (!dropdown) return;

            // Set current santri from URL or default
            const currentSantriId = getCurrentSantriId();
            if (currentSantriId) {
                dropdown.value = currentSantriId;
            }

            // Store selected santri data
            const selectedOption = dropdown.options[dropdown.selectedIndex];
            if (selectedOption) {
                window.currentSantri = JSON.parse(selectedOption.getAttribute('data-santri'));
            }

            // Handle dropdown change
            dropdown.addEventListener('change', function() {
                const selectedValue = this.value;
                if (!selectedValue || selectedValue === 'undefined') {
                    alert('Pilihan santri tidak valid');
                    this.value = currentSantriId;
                    return;
                }

                // Update URL with current filter and new santri_id
                const activeFilter = "{{ $activeFilter }}";
                window.location.href = `?filter=${activeFilter}&santri_id=${selectedValue}`;
            });
        }

        // Get current santri_id from URL or default
        function getCurrentSantriId() {
            const urlParams = new URLSearchParams(window.location.search);
            const urlSantriId = urlParams.get('santri_id');
            return urlSantriId || "{{ $activeSantri->id ?? '' }}";
        }

        // Payment Methods Handling
        function initPaymentMethods() {
            const paymentMethods = document.querySelectorAll('input[name="metode_pembayaran"]');
            paymentMethods.forEach(radio => {
                radio.addEventListener('change', function() {
                    showPaymentDetails(this.value);
                });
            });
        }

        // Open Payment Modal with validation
        function openPaymentModal(button, title, amount, dueDate, tagihanId) {
            try {
                // Get active santri_id
                const santriId = getCurrentSantriId();
                if (!santriId) {
                    throw new Error('Santri tidak terpilih');
                }

                // Format amount
                const formattedAmount = 'Rp ' + parseInt(amount).toLocaleString('id-ID');
                
                // Update modal content
                document.getElementById('paymentTitle').textContent = title;
                document.getElementById('paymentAmount').textContent = formattedAmount;
                document.getElementById('qrisAmount').textContent = formattedAmount;
                document.getElementById('paymentDueDate').textContent = dueDate;

                // Set form values
                document.getElementById('inputSantriId').value = santriId;
                document.getElementById('inputTagihanId').value = tagihanId;
                document.getElementById('inputNominalBayar').value = amount;

                // Reset form
                document.querySelector('input[name="metode_pembayaran"][value="transfer"]').checked = true;
                document.getElementById('bankPengirim').value = '';
                document.getElementById('namaPengirim').value = '';
                document.getElementById('buktiTransfer').value = '';
                showPaymentDetails('transfer');

                // Show modal
                document.getElementById('paymentModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';

            } catch (error) {
                console.error('Error opening payment modal:', error);
                alert(error.message);
            }
        }

        // Show payment details based on selected method
        function showPaymentDetails(method) {
            const sections = {
                transfer: document.getElementById('transferDetails'),
                qris: document.getElementById('qrisDetails'),
                tunai: document.getElementById('cashDetails')
            };

            // Hide all sections
            Object.values(sections).forEach(section => {
                if (section) section.classList.add('hidden');
            });

            // Show selected section
            if (sections[method]) {
                sections[method].classList.remove('hidden');
            }
        }

        // Validate payment before submit
        function validatePayment() {
            try {
                const metode = document.querySelector('input[name="metode_pembayaran"]:checked')?.value;
                if (!metode) {
                    throw new Error('Pilih metode pembayaran terlebih dahulu');
                }

                // Validate santri_id
                const santriId = document.getElementById('inputSantriId')?.value;
                if (!santriId || santriId === 'undefined') {
                    throw new Error('ID Santri tidak valid');
                }

                // Validate based on payment method
                if (metode === 'transfer') {
                    const bukti = document.getElementById('buktiTransfer');
                    const bankPengirim = document.getElementById('bankPengirim')?.value;
                    const namaPengirim = document.getElementById('namaPengirim')?.value;

                    if (!bukti?.files?.length) {
                        throw new Error('Upload bukti transfer terlebih dahulu');
                    }
                    if (!bankPengirim) {
                        throw new Error('Isi nama bank pengirim');
                    }
                    if (!namaPengirim) {
                        throw new Error('Isi nama pengirim');
                    }
                }

                return true;

            } catch (error) {
                alert(`Validasi gagal: ${error.message}`);
                return false;
            }
        }

        // Close payment modal
        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Copy to clipboard function
        function copyToClipboard(selector) {
            try {
                const el = document.querySelector(selector);
                if (!el) throw new Error('Element tidak ditemukan');

                const range = document.createRange();
                range.selectNodeContents(el);
                const sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(range);

                const success = document.execCommand('copy');
                sel.removeAllRanges();

                if (success) {
                    alert('Berhasil disalin!');
                } else {
                    throw new Error('Gagal menyalin');
                }
            } catch (error) {
                console.error('Copy failed:', error);
                alert(error.message);
            }
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('paymentModal');
            if (event.target === modal) {
                closePaymentModal();
            }
        });

        setTimeout(() => {
        const alertBox = document.getElementById('success-alert');
        if (alertBox) alertBox.remove();
    }, 3000);


    </script>
</body>
</html>