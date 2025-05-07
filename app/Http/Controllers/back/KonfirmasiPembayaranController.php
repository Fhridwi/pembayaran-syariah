<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class KonfirmasiPembayaranController extends Controller
{
    public function index()
    {
        $diterima = Pembayaran::where('status', 'terima')->sum('nominal_bayar');
        $menunggu = Pembayaran::where('status', 'pending')->count();
        $ditolak  = Pembayaran::where('status', 'tolak')->count();
        $total    = Pembayaran::sum('nominal_bayar');
    
        // Data untuk chart
        $chartData = [
            'labels' => ['Diterima', 'Menunggu', 'Ditolak'],
            'data' => [$diterima, $menunggu, $ditolak],
        ];
    
        $pembayarans = Pembayaran::with('santri', 'tagihan')->latest()->get();
    
        return view('back.konfirmasiPembayaran.index', compact('total', 'diterima', 'menunggu', 'ditolak', 'chartData', 'pembayarans'));
    }


    public function show($id)
    {
        $pembayaran = Pembayaran::with('santri', 'tagihan.jenisPembayaran')->findOrFail($id);
        return view('back.konfirmasiPembayaran.show', compact('pembayaran'));
    }

    public function store(Request $request)
    {
        // Validasi input untuk memastikan action adalah "terima" atau "tolak"
        $request->validate([
            'action' => 'required|in:tolak,terima',
            'pembayaran_id' => 'required|exists:pembayarans,id', 
        ]);
    
        $pembayaran = Pembayaran::findOrFail($request->pembayaran_id);
    
        $tagihan = $pembayaran->tagihan;

    
        if ($request->action === 'terima') {
            $pembayaran->status = 'diterima';
                $tagihan->status = 'lunas';
        } elseif ($request->action === 'tolak') {
            $pembayaran->status = 'tolak';
                $tagihan->status = 'belum';
        }
    
        $pembayaran->save();
            $tagihan->save();
    
        return redirect()->back()->with('success', 'Pembayaran berhasil diperbarui.');
    }
    
    
}
