<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/error', function () {
    return view('errors.404');
})->name('404');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'auth'])->name('login.auth');
});

Route::middleware(['auth'])->group(function () {
    // Logout route
    Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');

    // Dashboard route (accessible by all authenticated users)
    // Route::get('/dashboard', function () {
    //     return view('dashboard.index');
    // })->name('dashboard');

    // Admin only routes
    Route::middleware(['role:superAdmin,admin'])->group(function () {
        Route::get('/users', function () {
            return view('users.index');
        })->name('users');

        Route::get('/siswa', function () {
            return view('Siswa.index');
        })->name('siswa');

        Route::get('/karyawan', function () {
            return view('Karyawan.index');
        })->name('karyawan');

        Route::get('/mapel', function () {
            return view('Mapel.index');
        })->name('mapel');

        Route::get('/kelas', function () {
            return view('Kelas.index');
        })->name('kelas');
    });




});
