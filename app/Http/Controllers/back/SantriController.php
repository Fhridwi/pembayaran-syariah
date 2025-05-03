<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SantriController extends Controller
{
    public function index()
    {
        $santris = Santri::with('user')->latest()->get();
        $users = User::where('role', 'user')->select('id', 'nama_lengkap')->get();

        return view('back.santri.index', compact('santris', 'users'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('back.santri.create', compact('users'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nis' => 'required|unique:santris,nis',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'program' => 'nullable|string|max:255',
            'angkatan' => 'required|string|max:255',
            'sekolah' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
            'status' => 'required|in:aktif,alumni,keluar',
        ]);

        // Menangani upload foto jika ada
        if ($request->hasFile('foto')) {
            $photoPath = $request->file('foto')->store('santri_fotos', 'public');
        } else {
            $photoPath = null;
        }

        // Menyimpan data santri
        $santri = new Santri();
        $santri->user_id = $validated['user_id'];
        $santri->nis = $validated['nis'];
        $santri->nama = $validated['nama'];
        $santri->jenis_kelamin = $validated['jenis_kelamin'];
        $santri->tempat_lahir = $validated['tempat_lahir'];
        $santri->tanggal_lahir = $validated['tanggal_lahir'];
        $santri->alamat = $validated['alamat'];
        $santri->program = $validated['program'];
        $santri->angkatan = $validated['angkatan'];
        $santri->sekolah = $validated['sekolah'];
        $santri->foto = $photoPath;
        $santri->status = $validated['status'];
        $santri->save();

        return redirect()->route('santri.index')->with('success', 'Santri berhasil ditambahkan!');
    }

    public function show($id)
    {
        // Mengambil santri dengan relasi user, jika tidak ditemukan akan mengarah ke halaman 404
        $santri = Santri::with('user')->findOrFail($id);

        // Mengirim data santri ke view show
        return view('back.santri.show', compact('santri'));
    }
    public function edit($id)
    {
        // Menyediakan data user dan santri yang akan diedit
        $santri = Santri::findOrFail($id);
        $users = User::where('role', 'user')->select('id', 'nama_lengkap')->get();

        return view('back.santri.edit', compact('santri', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nis' => 'required|numeric|unique:santris,nis,' . $id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'program' => 'nullable|string|max:255',
            'angkatan' => 'required|string|max:255',
            'sekolah' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cari santri yang akan diupdate
        $santri = Santri::findOrFail($id);

        // Simpan foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($santri->foto) {
                Storage::delete($santri->foto);
            }

            $filePath = $request->file('foto')->store('public/fotos');
        } else {
            $filePath = $santri->foto;
        }

        $santri->update([
            'user_id' => $request->user_id,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'program' => $request->program,
            'angkatan' => $request->angkatan,
            'sekolah' => $request->sekolah,
            'foto' => $filePath,
            'status' => $request->status,
        ]);

        return redirect()->route('santri.index')->with('success', 'Santri berhasil diperbarui');
    }

    // Menghapus santri
    public function destroy($id)
{
    $santri = Santri::findOrFail($id);

    $hasUnpaid = $santri->tagihans()->where('status', 'belum')->exists();

    if ($hasUnpaid) {
        return redirect()->route('santri.index')->with('error', 'Santri tidak dapat dihapus karena masih memiliki tagihan yang belum lunas.');
    }

    // Hapus foto jika ada
    if ($santri->foto) {
        Storage::delete($santri->foto);
    }

    $santri->delete();

    return redirect()->route('santri.index')->with('success', 'Santri berhasil dihapus');
}

    
}
