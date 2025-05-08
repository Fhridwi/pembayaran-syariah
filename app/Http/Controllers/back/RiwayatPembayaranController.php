<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Santri;
use Illuminate\Http\Request;

class RiwayatPembayaranController extends Controller
{
    public function index(Request $request)
{
    $query = Pembayaran::query();

    // Filter berdasarkan Santri
    if ($request->has('santri_id') && $request->santri_id != '') {
        $query->where('santri_id', $request->santri_id);
    }

    // Filter berdasarkan Status
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // Filter berdasarkan Tanggal Mulai
    if ($request->has('start_date') && $request->start_date != '') {
        $query->whereDate('tanggal_bayar', '>=', $request->start_date);
    }

    // Filter berdasarkan Tanggal Selesai
    if ($request->has('end_date') && $request->end_date != '') {
        $query->whereDate('tanggal_bayar', '<=', $request->end_date);
    }

    // Ambil semua data riwayat pembayaran berdasarkan filter
    $riwayats = $query->get();

    // Ambil data Santri untuk dropdown
    $santris = Santri::all();

    // Kirim data ke view
    return view('back.riwayatPembayaran.index', compact('riwayats', 'santris'));
}

}
