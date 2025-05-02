<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() 
    {   
        $users = User::where('role', '!=', 'user')->get();

        return view('back.user.index', compact(
            'users'
        ));
    }

    public function store(UserStoreRequest $request) 
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->back()->with('success', 'User telah berhasil ditambahkan');
    }

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'nama_lengkap' => 'required|max:100',
        'username'     => 'required|unique:users,username,' . $user->id,
        'email'        => 'required|email|unique:users,email,' . $user->id,
        'no_hp'        => 'required|unique:users,no_hp,' . $user->id,
        'alamat'       => 'nullable',
        'role'         => 'required|in:admin,wali,bendahara,pengasuh',
    ]);

    $user->update([
        'nama_lengkap' => $request->nama_lengkap,
        'username'     => $request->username,
        'email'        => $request->email,
        'no_hp'        => $request->no_hp,
        'alamat'       => $request->alamat,
        'role'         => $request->role,
    ]);

    return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui.');
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('user.index')->with('success', 'Data pengguna berhasil dihapus.');
}


}
