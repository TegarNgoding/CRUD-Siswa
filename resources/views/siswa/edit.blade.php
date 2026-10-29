@extends('layouts.app')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-8">

        <div class="card shadow-lg border-0 rounded-4">

            <div class="card-header bg-warning bg-gradient text-dark p-3 rounded-top-4">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-pencil-square me-2"></i>Edit Data Siswa

                </h5>

            </div>

            <div class="card-body p-4">

                <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">

                    @csrf

                    @method('PUT')

                    <div class="mb-3">

                        <label for="nisn" class="form-label fw-semibold">NISN</label>

                        <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn', $siswa->nisn) }}">

                        @error('nisn')

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror

                    </div>

                    <div class="mb-3">

                        <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>

                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $siswa->nama) }}">

                        @error('nama')

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">

                            <label for="kelas" class="form-label fw-semibold">Kelas</label>

                            <input type="text" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" value="{{ old('kelas', $siswa->kelas) }}">

                            @error('kelas')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="col-md-6">

                            <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin</label>

                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin">

                                <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>

                                <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>

                            </select>

                            @error('jenis_kelamin')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                    </div>

                    <div class="mb-4">

                        <label for="alamat" class="form-label fw-semibold">Alamat Lengkap</label>

                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $siswa->alamat) }}</textarea>

                        @error('alamat')

                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror

                    </div>

                    <div class="d-flex justify-content-between">

                        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">

                            <i class="bi bi-arrow-left me-1"></i> Batal

                        </a>

                        <button type="submit" class="btn btn-warning bg-gradient px-4 rounded-pill shadow-sm">

                            <i class="bi bi-check-circle me-1"></i> Perbarui Data

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection