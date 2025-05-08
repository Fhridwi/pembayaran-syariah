<?php

use App\Http\Controllers\back\updateSantriController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\back\BankController;
use App\Http\Controllers\back\DashboardController;
use App\Http\Controllers\back\KategoriTagihanController;
use App\Http\Controllers\back\KonfirmasiPembayaranController;
use App\Http\Controllers\back\PembayaranController;
use App\Http\Controllers\back\ProgramController;
use App\Http\Controllers\back\RiwayatPembayaranController;
use App\Http\Controllers\back\SantriController;
use App\Http\Controllers\back\SekolahController;
use App\Http\Controllers\back\tahunAjaranController;
use App\Http\Controllers\back\UserController;
use App\Http\Controllers\back\UserWaliController;
use App\Http\Controllers\front\RiwayatPembayaranWaliController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\back\TagihanController;
use App\Http\Controllers\back\TagihanMassalController;
use App\Http\Controllers\front\DashboardWaliController;
use App\Http\Controllers\front\TagihanWaliController;
use Illuminate\Support\Facades\Route;

Route::get('/verify-email', [RegisteredUserController::class, 'showVerificationPage'])->name('verification.notice');


Route::middleware(['auth', 'log.activity'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth','role:admin'])->group(function() {
    Route::get('dashboard', [DashboardController::class, 'index' ])->name('admin.dashboard');
    Route::resource('user', UserController::class)->only(['index', 'store', 'update', 'destroy', ]);
    Route::get('user/wali', [UserWaliController::class, 'index'])->name('user.wali');
    Route::post('user/wali', [UserWaliController::class, 'store'])->name('user.wali.store');
    Route::put('user/wali/{id}', [UserWaliController::class, 'update'])->name('user.wali.update');
    Route::delete('user/wali/{id}', [UserWaliController::class, 'destroy'])->name('user.wali.destroy');
    Route::resource('tahun-ajaran', tahunAjaranController::class)->only(['index', 'store', 'update', 'destroy', ]);
    Route::resource('kategori-tagihan', KategoriTagihanController::class)->only(['index', 'store', 'update', 'destroy', ]);
    Route::resource('tagihan-santri', TagihanController::class)->only(['index', 'store', 'update', 'destroy', ]);
    Route::resource('santri', SantriController::class);
    Route::resource('update-status', updateSantriController::class)->only(['index']);
    Route::post('/update-status', [updateSantriController::class, 'ubahStatus'])->name('update.statusSantri');
    Route::resource('bank', BankController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('sekolah', SekolahController::class)->only(['index', 'store', 'update', 'destroy', ]);
    Route::resource('program', ProgramController::class)->only(['index', 'store', 'update', 'destroy', ]);
    Route::resource('tagihan-massal', TagihanMassalController::class)->only(['index', 'store', 'update', 'destroy', ]);
    Route::resource('pembayaran', PembayaranController::class)->only(['index', 'store' ]);
    Route::get('pembayaran/tagihan/{santri_id}', [PembayaranController::class, 'getTagihan'])->name('pembayaran.getTagihanBySantri');
    Route::resource('riwayat-pembayaran', RiwayatPembayaranController::class)->only(['index']);
    Route::get('konfirmasi-pembayaran', [KonfirmasiPembayaranController::class, 'index'])->name('konfirmasi.pembayaran.index');
    Route::get('konfirmasi-pembayaran/{id}', [KonfirmasiPembayaranController::class, 'show'])->name('konfirmasi-pembayaran.show');
    Route::post('konfirmasi-pembayaran/store', [KonfirmasiPembayaranController::class, 'store'])->name('konfirmasi-pembayaran.store');

});

Route::prefix('wali')->middleware(['auth', 'role:user'])->group(function() {
    Route::get('dashboard', [DashboardWaliController::class, 'index'])->name('user.dashboard');
    Route::post('pembayaran/store', [DashboardWaliController::class, 'storePembayaran'])->name('wali.pembayaran.store');
    Route::get('riwayat-menu', [RiwayatPembayaranWaliController::class, 'index'])->name('user.riwayat.index');
    
});



require __DIR__.'/auth.php';
