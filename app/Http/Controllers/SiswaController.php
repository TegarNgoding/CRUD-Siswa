<?php

namespace App\Http\Controllers;

use App\Models\Siswa;

use Illuminate\Http\Request;

class SiswaController extends Controller

{

    // Menampilkan daftar seluruh data siswa

    public function index()

    {

        $siswas = Siswa::latest()->paginate(10);

        return view('siswa.index', compact('siswas'));

    }

    // Menampilkan formulir tambah siswa

    public function create()

    {

        return view('siswa.create');

    }

    // Menyimpan data siswa baru ke database

    public function store(Request $request)

    {

        $request->validate([

            'nisn'          => 'required|unique:siswas,nisn',

            'nama'          => 'required',

            'jenis_kelamin' => 'required',

            'kelas'         => 'required',

            'alamat'        => 'required',

        ]);

        Siswa::create($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');

    }

    // Menampilkan detail siswa (Opsional jika dibutuhkan)

    public function show(Siswa $siswa)

    {

        return view('siswa.show', compact('siswa'));

    }

    // Menampilkan formulir edit data siswa

    public function edit(Siswa $siswa)

    {

        return view('siswa.edit', compact('siswa'));

    }

    // Perbarui data siswa di database

    public function update(Request $request, Siswa $siswa)

    {

        $request->validate([

            'nisn'          => 'required|unique:siswas,nisn,' . $siswa->id,

            'nama'          => 'required',

            'jenis_kelamin' => 'required',

            'kelas'         => 'required',

            'alamat'        => 'required',

        ]);

        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');

    }

    // Menghapus data siswa dari database

    public function destroy(Siswa $siswa)

    {

        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus!');

    }
    // 1. Metode Store (Menyimpan Foto Baru)
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

    $data = $request->all();

    if ($request->hasFile('foto')) {
        $image = $request->file('foto');
        $image->storeAs('public/foto_siswa', $image->hashName());
        $data['foto'] = $image->hashName();
    }

    Siswa::create($data);
    return redirect()->route('siswa.index')->with('success', 'Data siswa dan foto berhasil ditambahkan!');
}

// 2. Metode Update (Memperbarui Foto dan Menghapus Foto Lama)
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

    $data = $request->all();

    if ($request->hasFile('foto')) {
        $image = $request->file('foto');
        $image->storeAs('public/foto_siswa', $image->hashName());

        // Hapus foto lama jika ada
        if ($siswa->foto) {
            Storage::delete('public/foto_siswa/' . $siswa->foto);
        }

        $data['foto'] = $image->hashName();
    }

    $siswa->update($data);
    return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
}

// 3. Metode Destroy (Menghapus Data Sekaligus Foto Terkait)
public function destroy(Siswa $siswa)
{
    if ($siswa->foto) {
        Storage::delete('public/foto_siswa/' . $siswa->foto);
    }

    $siswa->delete();
    return redirect()->route('siswa.index')->with('success', 'Data siswa beserta fotonya berhasil dihapus!');
}


}
