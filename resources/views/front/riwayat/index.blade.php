<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran - Sistem Pembayaran Pesantren</title>
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
        
        .modal {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .modal.hidden {
            opacity: 0;
            transform: translateY(20px);
        }
        
        input[type="radio"]:checked + div {
            border-color: #10b981;
            background-color: #f0fdf4;
        }
        
        label:hover {
            border-color: #a7f3d0;
        }
        
        .modal-container {
            z-index: 60;
            padding-bottom: 80px;
        }
        
        .status-badge {
            @apply px-2 py-1 rounded-full text-xs font-medium;
        }
        
        .filter-tab {
            @apply px-4 py-2 rounded-full whitespace-nowrap transition-colors duration-200;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen font-sans">
    <div class="container mx-auto px-4 pb-24 max-w-md">
        <!-- Header -->
        <header class="bg-gradient-to-r from-primary-600 to-primary-700 p-6 rounded-b-2xl shadow-md mb-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">Riwayat Pembayaran</h1>
        
                    @if(count($santrisAktif) > 1)
                        <div class="mt-2 relative">
                            <select name="santri_id" 
                                    onchange="window.location.href = this.value"
                                    class="w-full bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all appearance-none pr-8">
                                @foreach ($santrisAktif as $santri)
                                    <option value="{{ route('user.riwayat.index', ['santri_id' => $santri->id]) }}" 
                                        {{ $activeSantri->id === $santri->id ? 'selected' : '' }}
                                        class="bg-primary-700 text-white">
                                        {{ $santri->nama }} - {{ $santri->program ?? 'Program tidak tersedia' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    @else
                        <p class="text-primary-100 mt-2">{{ $activeSantri->nama ?? '' }} - {{ $activeSantri->program ?? '' }}</p>
                    @endif
                </div>
        
                <div class="bg-white/20 p-2 rounded-full flex items-center">
                    <span class="text-sm">{{ $tahunAjaran->tahun_ajaran }}</span>
                </div>
            </div>
        </header>
        
       <!-- Filter Section -->
<div class="flex space-x-2 mb-4 overflow-x-auto scrollbar-hide pb-2">
    @php
        $baseSantriId = $activeSantri->id ?? '';
    @endphp

    <a href="?filter=semua&santri_id={{ $baseSantriId }}"
        class="filter-tab px-4 py-2 rounded-full whitespace-nowrap {{ $activeFilter === 'semua' ? 'bg-primary-500 text-white' : 'bg-white border border-gray-200 hover:bg-gray-50' }}">
        Semua
    </a>
    <a href="?filter=diproses&santri_id={{ $baseSantriId }}"
        class="filter-tab px-4 py-2 rounded-full whitespace-nowrap {{ $activeFilter === 'diproses' ? 'bg-primary-500 text-white' : 'bg-white border border-gray-200 hover:bg-gray-50' }}">
        pending
    </a>
    <a href="?filter=ditolak&santri_id={{ $baseSantriId }}"
        class="filter-tab px-4 py-2 rounded-full whitespace-nowrap {{ $activeFilter === 'ditolak' ? 'bg-primary-500 text-white' : 'bg-white border border-gray-200 hover:bg-gray-50' }}">
        Ditolak
    </a>
</div>


        <!-- Daftar Riwayat -->
        <div class="space-y-3 mb-20">
            @forelse($riwayat as $pembayaran)
                @php
                    $statusClasses = [
                        'diterima   ' => [
                            'border' => 'border-green-500',
                            'bg' => 'bg-green-100',
                            'text' => 'text-green-600',
                            'icon' => 'M5 13l4 4L19 7'
                        ],
                        'pending' => [
                            'border' => 'border-yellow-500',
                            'bg' => 'bg-yellow-100',
                            'text' => 'text-yellow-600',
                            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                        ],
                        'ditolak' => [
                            'border' => 'border-red-500',
                            'bg' => 'bg-red-100',
                            'text' => 'text-red-600',
                            'icon' => 'M6 18L18 6M6 6l12 12'
                        ]
                    ];
                    $status = $pembayaran->status;
                    $statusConfig = $statusClasses[$status] ?? $statusClasses['pending'];
                @endphp

                <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 {{ $statusConfig['border'] }} hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                {{ $pembayaran->tagihan->bulan_tagihan ?? 'Kategori Tidak Ditemukan' }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                No. Transaksi: {{ $pembayaran->kode_pembayaran }}
                            </p>
                        </div>
                        <span class="status-badge {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center text-sm mb-3">
                        <div class="text-gray-600">
                            <div class="flex items-center mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusConfig['icon'] }}" />
                                </svg>
                                <span>{{ $pembayaran->created_at->translatedFormat('d F Y, H:i') }}</span>
                            </div>
                            <p class="text-xs">
                                {{ strtoupper($pembayaran->metode_pembayaran) }}
                                @if($pembayaran->bank_pengirim)
                                    • {{ $pembayaran->bank_pengirim }}
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-gray-800 block">
                                Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}
                            </span>
                            @if($pembayaran->bukti_bayar)
                                <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" 
                                   target="_blank"
                                   class="text-primary-500 text-xs mt-1 flex items-center justify-end hover:text-primary-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Lihat Bukti
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    @if($pembayaran->status === 'ditolak' && $pembayaran->catatan_admin)
                        <div class="pt-3 border-t border-gray-100">
                            <div class="text-xs text-red-600 space-y-1">
                                <p class="font-medium">Catatan Admin:</p>
                                <p>{{ $pembayaran->catatan_admin }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white p-6 rounded-xl text-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500 mt-2">Tidak ada riwayat pembayaran</p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($riwayat->hasPages())
                <div class="mt-6">
                    {{ $riwayat->onEachSide(1)->links() }}
                </div>
            @endif
        </div>

        <!-- Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-around py-3 px-4 z-50">
            <a href="{{ route('user.dashboard') }}" class="text-gray-500 hover:text-primary-500 flex flex-col items-center transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs mt-1">Beranda</span>
            </a>
            <a href="#" class="text-primary-500 flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-xs mt-1">Riwayat</span>
            </a>
            <a href="#" class="text-gray-500 hover:text-primary-500 flex flex-col items-center transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs mt-1">Profil</span>
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-gray-500 hover:text-primary-500 flex flex-col items-center transition-colors duration-200">
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
        // Tambahkan interaksi jika diperlukan
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll untuk pagination
            document.querySelectorAll('.pagination a').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                    window.history.pushState(null, null, this.href);
                });
            });
        });
    </script>
</body>
</html>