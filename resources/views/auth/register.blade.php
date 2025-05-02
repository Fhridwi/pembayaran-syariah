<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Register - SyariahKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="text-center mb-10">
                <div class="flex justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 11c0-2.21 1.79-4 4-4s4 1.79 4 4v1h-2v-1a2 2 0 10-4 0v1h-2v-1zM6 13v-2a6 6 0 1112 0v2m-6 4h6a2 2 0 012 2v1H4v-1a2 2 0 012-2h6z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">SyariahKu</h2>
                <p class="text-gray-600">Silakan registrasi menggunakan data Anda</p>
            </div>

            <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-md">
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <!-- Nama Lengkap -->
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required
                               class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent py-2.5 px-0 text-gray-900 focus:border-green-600 focus:outline-none focus:ring-0"
                               placeholder=" " />
                        <label for="nama_lengkap"
                               class="absolute top-3 -z-10 origin-[0] scale-75 transform text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:text-green-600">
                            Nama Lengkap
                        </label>
                        @error('nama_lengkap')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <!-- Username -->
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="username" id="username" required
                               class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent py-2.5 px-0 text-gray-900 focus:border-green-600 focus:outline-none focus:ring-0"
                               placeholder=" " />
                        <label for="username"
                               class="absolute top-3 -z-10 origin-[0] scale-75 transform text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:text-green-600">
                            Username
                        </label>
                        @error('username')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <!-- Email -->
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="email" name="email" id="email" required
                               class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent py-2.5 px-0 text-gray-900 focus:border-green-600 focus:outline-none focus:ring-0"
                               placeholder=" " />
                        <label for="email"
                               class="absolute top-3 -z-10 origin-[0] scale-75 transform text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:text-green-600">
                            Email
                        </label>
                        @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <!-- Nomor HP -->
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="text" name="no_hp" id="no_hp" required
                               class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent py-2.5 px-0 text-gray-900 focus:border-green-600 focus:outline-none focus:ring-0"
                               placeholder=" " />
                        <label for="no_hp"
                               class="absolute top-3 -z-10 origin-[0] scale-75 transform text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:text-green-600">
                            Nomor HP
                        </label>
                        @error('no_hp')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <!-- Alamat -->
                    <div class="relative z-0 w-full mb-6 group">
                        <textarea name="alamat" id="alamat" required
                                  class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent py-2.5 px-0 text-gray-900 focus:border-green-600 focus:outline-none focus:ring-0"
                                  placeholder=" "></textarea>
                        <label for="alamat"
                               class="absolute top-3 -z-10 origin-[0] scale-75 transform text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:text-green-600">
                            Alamat
                        </label>
                        @error('alamat')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <!-- Password -->
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="password" name="password" id="password" required
                               class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent py-2.5 px-0 text-gray-900 focus:border-green-600 focus:outline-none focus:ring-0"
                               placeholder=" " />
                        <label for="password"
                               class="absolute top-3 -z-10 origin-[0] scale-75 transform text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:text-green-600">
                            Password
                        </label>
                        @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <!-- Konfirmasi Password -->
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent py-2.5 px-0 text-gray-900 focus:border-green-600 focus:outline-none focus:ring-0"
                               placeholder=" " />
                        <label for="password_confirmation"
                               class="absolute top-3 -z-10 origin-[0] scale-75 transform text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:text-green-600">
                            Konfirmasi Password
                        </label>
                        @error('password_confirmation')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <!-- Tombol Daftar -->
                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition duration-300">
                        Daftar
                    </button>
                </form>                
            </div>
        </div>
    </section>

</body>
</html>
