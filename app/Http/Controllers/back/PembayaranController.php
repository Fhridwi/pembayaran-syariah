<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\JenisPembayaran; // Mengimpor model JenisPembayaran
use App\Models\KategoriTagihan;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    /**
     * Menampilkan daftar pembayaran.
     */
    public function index()
    {
        $santris = Santri::all();
        $jenisPembayaran = KategoriTagihan::all();

        return view('back.pembayaran.index', compact('santris', 'jenisPembayaran'));
    }

    // Controller PembayaranController

    public function getTagihan($santriId)
{
    $tagihans = Tagihan::with('kategori')
    ->where('santri_id', $santriId)
    ->where('status', '!=', 'lunas')
    ->get();

    return response()->json($tagihans);
}




    /**
     * Menyimpan pembayaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'jenis_pembayaran' => 'required|exists:kategori_tagihans,id',
            'nominal_pembayaran' => 'required',
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|in:tunai,transfer',
            'bukti_transfer' => 'nullable|image|max:2048',
            'tagihan' => 'required|array',
            'tagihan.*' => 'exists:tagihans,id',
        ]);

        $santri = Santri::findOrFail($request->santri_id);
        $userId = $santri->user_id;
        $cleanNominal = preg_replace('/[^\d]/', '', $request->nominal_pembayaran); // hasil: 500000

        foreach ($request->tagihan as $tagihanId) {
            $pembayaran = new Pembayaran();
            $pembayaran->nomor_pembayaran = 'PMB-' . strtoupper(Str::random(8));
            $pembayaran->tagihan_id = $tagihanId;
            $pembayaran->santri_id = $santri->id;
            $pembayaran->user_id = $userId;
            $pembayaran->penerima_id = Auth::id();
            $pembayaran->nominal_bayar = $cleanNominal;
            $pembayaran->tanggal_bayar = $request->tanggal_pembayaran;
            $pembayaran->metode_pembayaran = $request->metode_pembayaran;
            $pembayaran->status = 'diterima';
        
            if ($request->metode_pembayaran === 'transfer' && $request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $path = $file->store('bukti-transfer', 'public');
                $pembayaran->bukti_bayar = $path;
                $pembayaran->bank_pengirim = $request->bank_pengirim;
                $pembayaran->nama_pengirim = $request->nama_pengirim;
            }
        
            $pembayaran->save();
        
            Tagihan::where('id', $tagihanId)->update(['status' => 'lunas']);
        }
        

        return redirect()->back()->with('success', 'Pembayaran berhasil disimpan.');
    }



    // Fungsi update dan destroy sesuai kebutuhan
}
