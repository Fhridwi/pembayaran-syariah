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

    if ($request->has('santri_id') && $request->santri_id != '') {
        $query->where('santri_id', $request->santri_id);
    }

    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    if ($request->has('start_date') && $request->start_date != '') {
        $query->whereDate('tanggal_bayar', '>=', $request->start_date);
    }

    if ($request->has('end_date') && $request->end_date != '') {
        $query->whereDate('tanggal_bayar', '<=', $request->end_date);
    }
    $riwayats = $query->get();

    $santris = Santri::all();

    return view('back.riwayatPembayaran.index', compact('riwayats', 'santris'));
}

}
