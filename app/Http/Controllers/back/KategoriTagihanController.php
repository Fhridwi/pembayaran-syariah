<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\KategoriTagihan;
use Illuminate\Http\Request;

class KategoriTagihanController extends Controller
{
    public function index()
    {
        $kategoriTagihans = KategoriTagihan::latest()->get();
        return view('back.kategoriTagihan.index', compact('kategoriTagihans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|unique:kategori_tagihans,nama_kategori',
            'nominal'       => 'required|numeric|min:0|max:99999999.99',
            'jenis_tagihan' => 'required|in:bulanan,bebas',
        ]);

        KategoriTagihan::create($data);

        return redirect()->back()->with('success', 'Kategori tagihan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|unique:kategori_tagihans,nama_kategori,' . $id,
            'nominal'       => 'required|numeric|min:0|max:99999999.99',
            'jenis_tagihan' => 'required|in:bulanan,bebas',
        ]);

        $kategori = KategoriTagihan::findOrFail($id);
        $kategori->update($data);

        return redirect()->back()->with('success', 'Kategori tagihan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriTagihan::findOrFail($id);

        if ($kategori->tagihans()->exists()) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh tagihan.');
        }

        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori tagihan berhasil dihapus.');
    }
}
