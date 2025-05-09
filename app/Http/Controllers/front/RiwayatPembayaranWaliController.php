<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use AppategoriTagihan;
use App\Models\Santri;
use App\Models\TahunAjaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPembayaranWaliController extends Controller
{
    public function index(Request $request)
    {
        $waliId = Auth::id();

        $santrisAktif = Santri::with(['tagihans' => function($query) {
            $query->with(['kategori']);
        }])
        ->where('user_id', $waliId)
        ->where('status', 'aktif')
        ->get();

        $activeSantri = $santrisAktif->firstWhere('id', $request->santri_id) ?? $santrisAktif->first();

        $query = Pembayaran::with([
            'tagihan.kategori', 
            'user'
        ])
        ->when($activeSantri, function($q) use ($activeSantri) {
            $q->whereHas('tagihan', function($q) use ($activeSantri) {
                $q->where('santri_id', $activeSantri->id);
            });
        })
        ->latest();

        $activeFilter = $request->filter ?? 'semua';
        $allowedFilters = ['lunas' => 'diterima', 'diproses' => 'pending', 'ditolak' => 'tolak'];
        
        if(array_key_exists($activeFilter, $allowedFilters)) {
            $query->where('status', $allowedFilters[$activeFilter]);
        }

        $riwayat = $query->paginate(6);

        $tahunAjaran = TahunAjaran::where('status', true)->first();

        return view('front.riwayat.index', [
            'santrisAktif' => $santrisAktif,
            'activeSantri' => $activeSantri,
            'tahunAjaran' => $tahunAjaran,
            'riwayat' => $riwayat,
            'activeFilter' => $activeFilter
        ]);
    }
}