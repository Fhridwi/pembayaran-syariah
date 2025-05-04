<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        return view('back.program.index', compact('programs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_program' => 'required|max:255|unique:programs,nama_program',
            'keterangan' => 'nullable|string',
        ]);

        Program::create($data);

        return redirect()->back()->with('success', 'Program berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);
        $data = $request->validate([
            'nama_program' => 'required|max:255|unique:programs,nama_program,' . $program->id,
            'keterangan' => 'nullable|string',
        ]);

        $program->update($data);

        return redirect()->back()->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Program::destroy($id);
        return redirect()->back()->with('success', 'Program berhasil dihapus.');
    }
}
