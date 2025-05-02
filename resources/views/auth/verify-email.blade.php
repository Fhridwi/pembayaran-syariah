<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email - SyariahKu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800">Verifikasi Email</h2>
            <p class="text-sm text-gray-600 mt-2">Masukkan 6 digit kode verifikasi yang dikirim ke email Anda.</p>
        </div>

        @if (session('status') == 'verification-code-sent')
            <div class="mt-4 text-sm text-green-600 text-center">
                Kode verifikasi baru telah dikirim ke email Anda.
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 text-sm text-red-600 text-center">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.verify') }}" class="mt-6">
            @csrf

            <div class="flex justify-center gap-2">
                @for ($i = 1; $i <= 6; $i++)
                    <input type="text" name="code[]" maxlength="1"
                        class="w-12 h-12 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-xl"
                        required>
                @endfor
            </div>

            @error('code')
                <p class="text-sm text-red-600 mt-2 text-center">{{ $message }}</p>
            @enderror

            <button type="submit"
                class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">
                Verifikasi
            </button>
        </form>

        <div class="mt-6 text-center text-sm">
            Belum menerima kode? 
            <form action="{{ route('verification.send') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-green-600 hover:underline">Kirim Ulang</button>
            </form>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
            @csrf
            <button type="submit" class="text-gray-600 hover:underline text-sm">Keluar</button>
        </form>
    </div>

</body>
</html>
