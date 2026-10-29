@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white fw-bold">Tambah Data Siswa</div>

                <div class="card-body">

                    <form action="{{ route('siswa.store') }}" method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">NISN</label>

                            <input type="text" class="form-control @error('nisn') is-invalid @enderror" name="nisn" value="{{ old('nisn') }}" placeholder="Masukkan NISN">

                            @error('nisn')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Nama Lengkap</label>

                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap">

                            @error('nama')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Jenis Kelamin</label>

                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin">

                                <option value="">-- Pilih Jenis Kelamin --</option>

                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>

                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>

                            </select>

                            @error('jenis_kelamin')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Kelas</label>

                            <input type="text" class="form-control @error('kelas') is-invalid @enderror" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: XII RPL 1">

                            @error('kelas')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Alamat</label>

                            <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3" placeholder="Masukkan Alamat Lengkap">{{ old('alamat') }}</textarea>

                            @error('alamat')

                                <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>

                            <button type="submit" class="btn btn-primary">Simpan Data</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection