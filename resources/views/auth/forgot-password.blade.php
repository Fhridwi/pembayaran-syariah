<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password - SyariahKu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.96 9.96 0 012.264-3.592m2.52-2.002A9.958 9.958 0 0112 5c4.478 0 8.269 2.943 9.543 7a9.966 9.966 0 01-4.293 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h2 class="text-2xl font-bold mt-4 text-gray-800">Lupa Password?</h2>
            <p class="text-gray-600 mt-2 text-sm">Masukkan email Anda dan kami akan kirimkan link untuk reset password Anda.</p>
        </div>

        {{-- Flash Message --}}
        @if (session('status'))
            <div class="mt-4 text-green-600 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-6">
            @csrf

            <div class="relative z-0 w-full mb-6 group">
                <input type="email" name="email" id="email" required
                    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent py-2.5 px-0 text-gray-900 focus:border-green-600 focus:outline-none focus:ring-0"
                    placeholder=" " value="{{ old('email') }}" />
                <label for="email"
                    class="absolute top-3 -z-10 origin-[0] scale-75 transform text-gray-500 duration-300
                    peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100
                    peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:text-green-600">
                    Email
                </label>
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition duration-300">
                Kirim Link Reset
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-green-600 hover:underline">
                    Kembali ke Login
                </a>
            </div>
        </form>
    </div>

</body>
</html>
