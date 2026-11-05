@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-10">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white d-flex justify-content-between align-items-center fw-bold">

                    <span>Daftar Data Siswa</span>

                    <a href="{{ route('siswa.create') }}" class="btn btn-primary btn-sm">Tambah Siswa</a>

                </div>

                <div class="card-body">

                    @if (session('success'))

                        <div class="alert alert-success alert-dismissible fade show" role="alert">

                            {{ session('success') }}

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        </div>

                    @endif

                    <div class="table-responsive">

                        <table class="table table-hover table-striped align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>No</th>

                                    <th>NISN</th>

                                    <th>Nama Lengkap</th>

                                    <th>L/P</th>

                                    <th>Kelas</th>

                                    <th>Alamat</th>

                                    <th class="text-center">Aksi</th>

                                    <th>Foto</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse ($siswas as $key => $siswa)

                                    <tr>

                                        <td>{{ $siswas->firstItem() + $key }}</td>

                                        <td>{{ $siswa->nisn }}</td>

                                        <td>{{ $siswa->nama }}</td>

                                        <td>{{ $siswa->jenis_kelamin }}</td>

                                        <td>{{ $siswa->kelas }}</td>

                                        <td>{{ $siswa->alamat }}</td>

                                        <td class="text-center">
                                            <form
                                                action="{{ route('siswa.destroy', $siswa->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');"
                                            >

                                                <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-warning btn-sm text-white">Edit</a>

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>

                                            </form>

                                        </td>
                                        <td class="text-center">
                                            @if ($siswa->foto)
                                                <img
                                                    src="{{ asset('storage/foto_siswa/' . $siswa->foto) }}"
                                                    class="rounded-circle shadow-sm"
                                                    style="width: 48px; height: 48px; object-fit: cover;"
                                                    alt="Foto {{ $siswa->nama }}"
                                                >
                                            @else
                                                <span class="badge bg-secondary">Tidak ada foto</span>
                                            @endif
                                        </td>
                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="8" class="text-center text-muted">Data siswa belum tersedia.</td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <div class="d-flex justify-content-end mt-3">

                        {{ $siswas->links() }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection