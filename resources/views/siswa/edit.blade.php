@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white fw-bold">Edit Data Siswa</div>

                <div class="card-body">

                    <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">

                        @csrf

                        @method('PUT')

                        <div class="mb-3">

                            <label class="form-label">NISN</label>

                            <input type="text" class="form-control @error('nisn') is-invalid @enderror" name="nisn" value="{{ old('nisn', $siswa->nisn) }}">

                            @error('nisn')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Nama Lengkap</label>

                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $siswa->nama) }}">

                            @error('nama')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Jenis Kelamin</label>

                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin">

                                <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>

                                <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>

                            </select>

                            @error('jenis_kelamin')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Kelas</label>

                            <input type="text" class="form-control @error('kelas') is-invalid @enderror" name="kelas" value="{{ old('kelas', $siswa->kelas) }}">

                            @error('kelas')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Alamat</label>

                            <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3">{{ old('alamat', $siswa->alamat) }}</textarea>

                            @error('alamat')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>

                            <button type="submit" class="btn btn-success text-white">Perbarui Data</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection