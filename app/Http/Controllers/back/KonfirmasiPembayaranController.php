<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class KonfirmasiPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $statusFilter = $request->input('status', 'pending');
    
        // Filter berdasarkan bulan dan tahun dari created_at
        $query = Pembayaran::whereYear('created_at', substr($bulan, 0, 4))
                           ->whereMonth('created_at', substr($bulan, 5, 2));
    
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
    
        $baseQuery = clone $query;
    
        $diterima = (clone $query)->where('status', 'diterima')->sum('nominal_bayar');
        $menunggu = (clone $query)->where('status', 'pending')->count();
        $ditolak  = (clone $query)->where('status', 'tolak')->count();
        $total    = (clone $query)->sum('nominal_bayar');
    
        // Data untuk chart
        $chartData = [
            'labels' => ['Diterima', 'Menunggu', 'Ditolak'],
            'data' => [$diterima, $menunggu, $ditolak],
        ];
    
        $pembayarans = $baseQuery->with('santri', 'tagihan')->latest()->get();
    
        return view('back.konfirmasiPembayaran.index', compact(
            'total', 'diterima', 'menunggu', 'ditolak', 'chartData',
            'pembayarans', 'bulan', 'statusFilter'
        ));
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
