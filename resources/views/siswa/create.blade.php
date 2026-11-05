@extends('layouts.app')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-8">

        <div class="card shadow-lg border-0 rounded-4">

            <div class="card-header bg-success bg-gradient text-white p-3 rounded-top-4">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Data Siswa Baru

                </h5>

            </div>

            <div class="card-body p-4">

                <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    

                    <div class="mb-3">

                        <label for="nisn" class="form-label fw-semibold">NISN</label>

                        <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn') }}" placeholder="Masukkan 10 digit NISN">

                        @error('nisn')

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror

                    </div>

                    <div class="mb-3">

                        <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>

                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap siswa">

                        @error('nama')

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">

                            <label for="kelas" class="form-label fw-semibold">Kelas</label>

                            <input type="text" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: XII RPL 1">

                            @error('kelas')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="col-md-6">

                            <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin</label>

                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin">

                                <option value="" selected disabled>-- Pilih Jenis Kelamin --</option>

                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>

                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>

                            </select>

                            @error('jenis_kelamin')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                    </div>

                    <div class="mb-4">

                        <label for="alamat" class="form-label fw-semibold">Alamat Lengkap</label>

                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap tempat tinggal">{{ old('alamat') }}</textarea>

                        @error('alamat')

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror

                    </div>
                    <div class="mb-3">
                    <label class="form-label">Foto Profil Siswa</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto">
            @error('foto')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                    <div class="d-flex justify-content-between">

                        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">

                            <i class="bi bi-arrow-left me-1"></i> Kembali

                        </a>

                        <button type="submit" class="btn btn-success bg-gradient px-4 rounded-pill shadow-sm">

                            <i class="bi bi-save me-1"></i> Simpan Data

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection