<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index() 
    {
        $banks = Bank::latest()->get();
        return view('back.bank.index', compact('banks'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_bank' => 'required|string|max:100',
        'nomor_rekening' => 'required|string|max:50',
        'nama_pemilik' => 'required|string|max:100',
        'is_aktif' => 'required|boolean',
    ]);

    Bank::create([
        'nama_bank' => $request->nama_bank,
        'nomor_rekening' => $request->nomor_rekening,
        'nama_pemilik' => $request->nama_pemilik,
        'is_aktif' => $request->is_aktif,  
    ]);

    return redirect()->back()->with('success', 'Data bank berhasil ditambahkan.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_bank' => 'required|string|max:100',
        'nomor_rekening' => 'required|string|max:50',
        'nama_pemilik' => 'required|string|max:100',
        'is_aktif' => 'required|boolean',
    ]);

    $bank = Bank::findOrFail($id);
    $bank->update($request->only(['nama_bank', 'nomor_rekening', 'nama_pemilik', 'is_aktif']));

    return redirect()->back()->with('success', 'Data bank berhasil diperbarui.');
}

public function destroy($id)
{
    $bank = Bank::findOrFail($id);
    $bank->delete();

    return redirect()->route('bank.index')->with('success', 'Bank berhasil dihapus.');
}

}
