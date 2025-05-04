<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Santri;
use App\Models\TahunAjaran;
use App\Models\KategoriTagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $tagihans = Tagihan::with(['santri', 'tahun', 'kategori'])->latest()->get();
        $santris = Santri::all();
        $tahuns = TahunAjaran::all();
        $kategoris = KategoriTagihan::all();

        return view('back.tagihan.index', compact('tagihans', 'santris', 'tahuns', 'kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'santri_id'     => 'required|exists:santris,id',
            'tahun_id'      => 'required|exists:tahun_ajarans,id',
            'kategori_id'   => 'required|exists:kategori_tagihans,id',
            'bulan_tagihan' => 'required|string|max:20',
            'jatuh_tempo'   => 'required|date',
        ]);
    
        $existing = Tagihan::where('santri_id', $data['santri_id'])
            ->where('tahun_id', $data['tahun_id'])
            ->where('kategori_id', $data['kategori_id'])
            ->where('bulan_tagihan', $data['bulan_tagihan'])
            ->first();
    
        if ($existing) {
            return back()->with('error', 'Tagihan untuk bulan dan kategori tersebut sudah ada.');
        }
    
        $data['status'] = 'belum';
        Tagihan::create($data);
    
        return back()->with('success', 'Tagihan berhasil ditambahkan.');
    }
    

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'santri_id'     => 'required|exists:santris,id',
            'tahun_id'      => 'required|exists:tahun_ajarans,id',
            'kategori_id'   => 'required|exists:kategori_tagihans,id',
            'bulan_tagihan' => 'required|string|max:20',
            'jatuh_tempo'   => 'required|date',
            'status'        => 'required|in:belum,lunas'
        ]);

        $tagihan = Tagihan::findOrFail($id);
        $tagihan->update($data);

        return back()->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->delete();

        return back()->with('success', 'Tagihan berhasil dihapus.');
    }
}