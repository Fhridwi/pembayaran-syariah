<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class tahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::all();

        return view('back.tahunAjaran.index', compact('tahunAjarans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tahun_ajaran'     => 'required|string|max:10',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'status'           => 'nullable|boolean',
        ]);

        if (!empty($data['status']) && $data['status'] == 1) {
            TahunAjaran::where('status', 1)->update(['status' => 0]);
        }

        TahunAjaran::create($data);

        return redirect()->back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'tahun_ajaran'     => 'required|string|max:10',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'status'           => 'nullable|boolean',
        ]);
    
        $data['status'] = $request->has('status') ? 1 : 0;
    
        // Jika status ingin diaktifkan, maka nonaktifkan semua tahun ajaran lain
        if ($data['status'] == 1) {
            TahunAjaran::where('id', '!=', $id)->update(['status' => 0]);
        }
    
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->update($data);
    
        return redirect()->back()->with('success', 'Tahun ajaran berhasil diperbarui.');
    }
    
    public function destroy($id) 
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        if($tahunAjaran->tagihans()->exists()) {
            return redirect()->back()->with('error', 'Tahun ajaran tidak dapat dihapus karena masih digunakan oleh tagihan.');
        }

        $tahunAjaran->delete();

        return redirect()->back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }

}
