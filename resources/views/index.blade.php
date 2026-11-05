:@extends('layouts.app')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-11">

        

        {{-- Alert Notifikasi --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">

                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

            </div>

        @endif

        {{-- Kartu Utama --}}

        <div class="card shadow-lg border-0 rounded-4">

            <div class="card-header bg-primary bg-gradient text-white p-3 rounded-top-4 d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-people-fill me-2"></i>Data Siswa SMK Fatahillah Cileungsi

                </h5>

                <a href="{{ route('siswa.create') }}" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm fw-semibold">

                    <i class="bi bi-plus-lg me-1"></i> Tambah Siswa

                </a>

            </div>

            

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="text-center px-3" style="width: 5%;">No</th>

                                <th>NISN</th>

                                <th>Nama Lengkap</th>

                                <th>Kelas</th>

                                <th>Jenis Kelamin</th>

                                <th>Alamat</th>

                                <th class="text-center" style="width: 15%;">Aksi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($dataSiswa as $index => $siswa)

                                <tr>

                                    <td class="text-center fw-medium text-muted">{{ $index + 1 }}</td>

                                    <td class="fw-semibold">{{ $siswa->nisn }}</td>

                                    <td>{{ $siswa->nama }}</td>

                                    <td>

                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">

                                            {{ $siswa->kelas }}

                                        </span>

                                    </td>

                                    <td>

                                        @if($siswa->jenis_kelamin == 'L')

                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-3">

                                                <i class="bi bi-gender-male me-1"></i> Laki-laki

                                            </span>

                                        @else

                                            <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle rounded-pill px-3">

                                                <i class="bi bi-gender-female me-1"></i> Perempuan

                                            </span>

                                        @endif

                                    </td>

                                    <td class="text-secondary small">{{ $siswa->alamat }}</td>

                                    <td class="text-center">

                                        <div class="btn-group btn-group-sm" role="group">

                                            <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-outline-warning" title="Edit Data">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>

                                            <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit" class="btn btn-outline-danger" title="Hapus Data">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center py-5 text-muted">

                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>

                                        Belum ada data siswa yang tersimpan.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection