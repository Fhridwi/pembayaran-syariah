<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\EmailVerificationOTP; // Tambahkan import untuk Mailable
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; // Import Mail

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi inputan dari form
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        // Membuat user baru
        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'role' => 'user',
            'password' => Hash::make($request->password),
        ]);

        // Membuat OTP untuk verifikasi email
        $otp = rand(100000, 999999); // Generate OTP
        $user->otp = $otp;  // Menyimpan OTP di database
        $user->save();

        // Mengirim OTP ke email pengguna
        Mail::to($user->email)->send(new EmailVerificationOTP($otp));

        // Mengirimkan event Registered
        event(new Registered($user));
    
        // Setelah pendaftaran berhasil, redirect ke halaman verifikasi OTP
        return redirect()->view('auth.verify-email');
    }

    // app/Http/Controllers/Auth/RegisteredUserController.php

public function showVerificationPage()
{
    return view('auth.verify-email');
}

public function verifyOTP(Request $request)
{
    // Validasi OTP yang dimasukkan
    $request->validate([
        'otp' => 'required|numeric|digits:6',  // Validasi bahwa OTP harus berupa 6 digit angka
    ]);

    // Ambil user yang sedang login
    $user = Auth::user();

    // Cek apakah OTP yang dimasukkan sesuai dengan OTP yang ada di database
    if ($user->otp == $request->otp) {
        // Verifikasi berhasil, tandai email sebagai terverifikasi
        // Gunakan query builder untuk update
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'email_verified_at' => now(),
                'otp' => null,  // Hapus OTP setelah verifikasi berhasil (opsional)
            ]);

        // Redirect ke halaman dashboard atau halaman yang sesuai
        return redirect()->route('front.dashboard');
    }

    // Jika OTP tidak valid, kembalikan dengan pesan error
    return back()->withErrors(['otp' => 'OTP tidak valid. Silakan coba lagi.']);
}




}
