<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardWaliController extends Controller
{
    public function index(Request $request)
    {
        $waliId = Auth::id();
        $filter = $request->query('filter', 'semua');
        $activeSantriId = $request->get('santri_id');

        // Get active academic year
        $tahunAjaranAktif = TahunAjaran::where('status', true)->firstOrFail();

        // Ambil semua santri milik wali dengan tagihan yang difilter berdasarkan tahun ajaran aktif
        $santri = Santri::where('user_id', $waliId)
            ->with(['tagihans' => function($query) use ($filter, $tahunAjaranAktif) {
                $query->with('kategori')
                      ->where('tahun_id', $tahunAjaranAktif->id)
                      ->when($filter === 'belum', function($q) {
                          $q->where('status', 'belum');
                      })
                      ->when($filter === 'lunas', function($q) {
                          $q->where('status', 'lunas');
                      })
                      ->when($filter === 'bulan-ini', function($q) {
                          $q->whereMonth('jatuh_tempo', now()->month)
                              ->whereYear('jatuh_tempo', now()->year);
                      })
                      ->orderBy('jatuh_tempo', 'asc');
            }])
            ->get();

        // Santri aktif berdasarkan ID
        $activeSantri = $santri->firstWhere('id', $activeSantriId) ?? $santri->first();

        // Hitung total tagihan untuk santri aktif
        $totalTagihan = 0;
        $belumLunasCount = 0;
        $lunasCount = 0;

        if ($activeSantri) {
            $tagihans = $activeSantri->tagihans;

            // Filter tagihan yang sesuai dengan filter
            if ($filter === 'semua') {
                $tagihans = $tagihans->take(10); // Ambil 10 tagihan pertama jika tidak ada filter
            } elseif ($filter === 'belum') {
                $tagihans = $tagihans->where('status', 'belum')->take(10);
            } elseif ($filter === 'lunas') {
                $tagihans = $tagihans->where('status', 'lunas')->take(7);
            } elseif ($filter === 'bulan-ini') {
                $tagihans = $tagihans->whereMonth('jatuh_tempo', now()->month)
                                     ->whereYear('jatuh_tempo', now()->year)
                                     ->take(10);
            }

            // Hitung total tagihan dan jumlah tagihan
            foreach ($tagihans as $tagihan) {
                if ($tagihan->status == 'belum') {
                    $totalTagihan += $tagihan->kategori->nominal ?? 0;
                    $belumLunasCount++;
                } else {
                    $lunasCount++;
                }
            }
        }

        $bankDetails = Bank::where('is_aktif', 1)->first();

        return view('front.dashboard.dashboard', [
            'santri' => $santri,
            'totalTagihan' => $totalTagihan,
            'belumLunasCount' => $belumLunasCount,
            'lunasCount' => $lunasCount,
            'activeSantri' => $activeSantri,
            'activeFilter' => $filter,
            'tahunAjaran' => $tahunAjaranAktif,
            'bank'      => $bankDetails
        ]);
    }

    public function storePembayaran(Request $request)
{
    $request->validate([
        'santri_id'         => 'required',
        'tagihan_id'        => 'required|uuid',
        'nominal_bayar'     => 'required|numeric|min:1',
        'metode_pembayaran' => 'required|in:tunai,transfer,qris',
        'bank_pengirim'     => 'nullable|string|max:100',
        'nama_pengirim'     => 'nullable|string|max:100',
        'bukti_bayar'       => 'nullable|file|image|max:5048',
    ]);

    dd($request);

    // Handle upload bukti
    $buktiPath = null;
    if ($request->hasFile('bukti_bayar')) {
        $buktiPath = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');
    }

    // Generate nomor pembayaran unik (misal: PMB20250507123456)
    $nomorPembayaran = 'PMB' . now()->format('YmdHis') . strtoupper(Str::random(4));

    Pembayaran::create([
        'id'                 => (string) Str::uuid(),
        'nomor_pembayaran'  => $nomorPembayaran,
        'tagihan_id'        => $request->tagihan_id,
        'santri_id'         => $request->santri_id,
        'user_id'           => Auth::id(),
        'penerima_id'       => null, 
        'nominal_bayar'     => $request->nominal_bayar,
        'tanggal_bayar'     => now(),
        'metode_pembayaran' => $request->metode_pembayaran,
        'bank_pengirim'     => $request->bank_pengirim,
        'nama_pengirim'     => $request->nama_pengirim,
        'bukti_bayar'       => $buktiPath,
        'status'            => 'pending',
        'keterangan_status' => null,
    ]);

    return redirect()->back()->with('success', 'Pembayaran berhasil dikirim. Menunggu verifikasi.');
}
}