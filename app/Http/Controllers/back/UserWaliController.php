<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserWaliController extends Controller
{
    private $folderRoute = 'back.userWali';

    public function index() 
    {

        $users = User::where('role', 'user')->latest()->get();

        return view($this->folderRoute . '.index', compact('users'));
    }

    public function store(Request $request)
    {   
        $request->validate([
            'nama_lengkap' => 'required|max:100',
            'username'     => 'required|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'no_hp'        => 'required|unique:users,no_hp',
            'alamat'       => 'nullable',
            'password'     => 'required|min:8',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
            'password'     => Hash::make($request->password),
            'role'         => 'user'
        ]);

        return redirect()->back()->with('success', 'Berhail menambahkan user wali.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username,' . $id,
        'email' => 'nullable|email|unique:users,email,' . $id,
        'no_hp' => 'nullable|string|max:20',
        'alamat' => 'nullable|string|max:255',
    ]);

    $user = User::findOrFail($id);

    $user->update([
        'nama_lengkap' => $request->nama_lengkap,
        'username' => $request->username,
        'email' => $request->email,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat,
    ]);

    return redirect()->back()->with('success', 'User berhasil diperbarui.');
}

public function destroy($id)
{
    $user = User::findOrFail($id);

    if ($user->santri()->exists()) {
        return redirect()->back()->with('error', 'User tidak dapat dihapus karena sudah memiliki santri.');
    }

    $user->delete();

    return redirect()->back()->with('success', 'User berhasil dihapus.');
}


}
