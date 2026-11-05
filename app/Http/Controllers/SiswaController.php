<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::latest()->paginate(10);
        return view('siswa.index', compact('siswas'));
    }
    public function create()
    {
        return view('siswa.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nisn'          => 'required|unique:siswas,nisn',
            'nama'          => 'required',
            'jenis_kelamin' => 'required',
            'kelas'         => 'required',
            'alamat'        => 'required',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
 
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_siswa', 'public');
            $data['foto'] = basename($data['foto']);
        }

        Siswa::create($data);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa dan foto berhasil ditambahkan!');
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn'          => 'required|unique:siswas,nisn,' . $siswa->id,
            'nama'          => 'required',
            'jenis_kelamin' => 'required',
            'kelas'         => 'required',
            'alamat'        => 'required',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('foto_siswa', 'public');
            $data['foto'] = basename($foto);

            if ($siswa->foto) {
                Storage::disk('public')->delete('foto_siswa/' . $siswa->foto);
            }
        }

        $siswa->update($data);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        if ($siswa->foto) {
            Storage::disk('public')->delete('foto_siswa/' . $siswa->foto);
        }

        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa beserta fotonya berhasil dihapus!');
    }
