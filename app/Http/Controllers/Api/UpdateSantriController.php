<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Http\Request;

class updateSantriController extends Controller
{

    public function index()
    {
        $santris = Santri::all();
        return view('back.updateStatus.index', compact('santris'));
    }

    public function ubahStatus(Request $request)
{
    $request->validate([
        'santri_id' => 'required|exists:santris,id',
        'status' => 'required|in:aktif,alumni,keluar',
    ]);

    $santri = Santri::findOrFail($request->santri_id);
    $santri->status = $request->status;
    $santri->save();

    return response()->json(['message' => 'Status santri berhasil diperbarui.']);
}

}
