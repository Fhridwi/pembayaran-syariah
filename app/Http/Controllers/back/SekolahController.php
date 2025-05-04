<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sekolahs = Sekolah::all();
        return view('back.sekolah.index', compact('sekolahs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_sekolah'  => 'required|max:30|unique:sekolahs,nama_sekolah',
            'jenjang'       => 'required|in:SD,SLTP,SLTA,PERGURUAN TINGGI',
        ]);

        Sekolah::create($data);

        return redirect()->back()->with('sucess', 'Berhasil menambahkan sekolah baru.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        $data = $request->validate([
            'nama_sekolah' => 'required|max:30|unique:sekolahs,nama_sekolah,' . $sekolah->id,
            'jenjang' => 'required|in:SD,SLTP,SLTA,PERGURUAN TINGGI',
        ]);

        $sekolah->update($data);

        return redirect()->back()->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $sekolah->delete();

        return redirect()->back()->with('success', 'Data sekolah berhasil dihapus.');
    }
}
