<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Santri;
use App\Models\Tagihan;
use App\Models\KategoriTagihan;
use App\Models\Program;
use App\Models\TahunAjaran;

class TagihanMassalController extends Controller
{
    public function index()
    {
        $jenisPembayaran = KategoriTagihan::all(); 
        $tahunAjaran = TahunAjaran::all();  
        $angkatan = TahunAjaran::all(); 
        $program = Program::all();  
    
        return view('back.tagihanMassal.index', compact('jenisPembayaran', 'tahunAjaran', 'angkatan', 'program'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pembayaran' => 'required|exists:kategori_tagihans,id',
            'tahun_ajaran' => 'required|exists:tahun_ajarans,id',
            'periode' => 'required|in:bulanan,bebas',
            'nominal' => 'required|numeric|min:1',
        ]);
    
        // Ambil data kategori tagihan
        $kategoriId = $request->jenis_pembayaran;
        $tahunId = $request->tahun_ajaran;
        $nominal = $request->nominal;
        $periode = $request->periode;
    
        // Cek batas waktu untuk tagihan bulanan
        if ($periode === 'bulanan') {
            $today = now();
            $endOfMonth = $today->copy()->endOfMonth();
            $daysToEnd = $today->diffInDays($endOfMonth, false);
    
            if ($daysToEnd > 2 || $daysToEnd < 0) {
                return back()->with('error', 'Tagihan bulanan hanya dapat dibuat maksimal 3 hari sebelum pergantian bulan.');
            }
        }
    
        // Filter santri
        $santris = Santri::query()
            ->when($request->angkatan, fn($q) => $q->where('angkatan', $request->angkatan))
            ->when($request->program, fn($q) => $q->where('program', $request->program))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->get();
    
        try {
            DB::beginTransaction();
    
            foreach ($santris as $santri) {
                if ($periode === 'bulanan') {
                    foreach ($request->bulan ?? [] as $bulan) {
                        Tagihan::create([
                            'santri_id' => $santri->id,
                            'tahun_id' => $tahunId,
                            'kategori_id' => $kategoriId,
                            'bulan_tagihan' => $bulan . ' ' . date('Y'),
                            'jatuh_tempo' => now()->addDays(30),
                            'status' => 'belum',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // Tagihan bebas/semesteran (tidak berdasarkan bulan)
                    Tagihan::create([
                        'santri_id' => $santri->id,
                        'tahun_id' => $tahunId,
                        'kategori_id' => $kategoriId,
                        'bulan_tagihan' => $request->keterangan,
                        'jatuh_tempo' => $request->jatuh_tempo,
                        'status' => 'belum',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
    
            DB::commit();
            return back()->with('success', 'Tagihan massal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan tagihan massal: ' . $e->getMessage());
        }
    }
    
}
