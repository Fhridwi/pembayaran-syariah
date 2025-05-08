<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class updateSantriController extends Controller
{
    public function index()
{
    $santris = Santri::with('tagihans')->get();

    foreach ($santris as $santri) {
        $santri->punya_tunggakan = $santri->tagihans
            ->where('status', 'belum')
            ->isNotEmpty();
    }

    return view('back.updateStatus.index', compact('santris'));
}


    public function ubahStatus(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'status' => 'required|in:aktif,alumni,keluar',
        ]);

        $santri = Santri::findOrFail($request->santri_id);

        
        $hasTunggakan = Tagihan::where('santri_id', $santri->id)
        ->where('status', 'belum')
        ->exists();
        // dd($hasTunggakan);
        
        if ($hasTunggakan) {
            return redirect()->back()->with('error', 'Santri memiliki tunggakan pembayaran, status tidak dapat diubah.');
        }
        $santri->status = $request->status;
        $santri->save();

        return redirect()->back()->with('success', 'Status santri berhasil diperbarui.');
    }
}
