<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\back\DashboardController;
use App\Http\Controllers\back\KategoriTagihanController;
use App\Http\Controllers\back\SantriController;
use App\Http\Controllers\back\tahunAjaranController;
use App\Http\Controllers\back\UserController;
use App\Http\Controllers\back\UserWaliController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\back\TagihanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/verify-email', [RegisteredUserController::class, 'showVerificationPage'])->name('verification.notice');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth','role:admin', 'verified'])->group(function() {
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

});

Route::get('/user/dashboard', function() {
    view('front.dashboard.dashboard');
})->name('front.dashboard');

require __DIR__.'/auth.php';
