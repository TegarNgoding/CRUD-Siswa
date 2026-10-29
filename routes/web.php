<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('siswa.index')
        : redirect()->route('login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::resource('siswa', SiswaController::class);
});

Route::get('/home', function () {
    return redirect()->route('siswa.index');
})->middleware('auth')->name('home');
