<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return view('login.index');
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
    Route::get('/dashboard', function () {
        return view('Dashboard.index');
    })->name('dashboard');

    // Admin only routes
    Route::middleware(['role:superAdmin,admin,guru,kurikulum,waliKelas'])->group(function () {
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

        Route::get('/guruMapel', function () {
            return view('GuruMapel.index');
        })->name('guruMapel');

        Route::get('/jadwal', function () {
            return view('Jadwal.index');
        })->name('jadwal');

        Route::get('/kelas', function () {
            return view('Kelas.index');
        })->name('kelas');

        Route::get('/AbsenKelasSiswa', function () {
            return view('KelolaAbsenKelas.index');
        })->name('AbsenKelasSiswa');

        Route::get('/generateQr', function () {
            return view('GenerateQr.index');
        })->name('generateQr');

        Route::get('/kelolaAbsenGerbang', function () {
            return view('KelolaAbsenGerbang.index');
        })->name('kelolaAbsenGerbang');

        Route::get('/AbsenKelas', function () {
            return view('Absen.index');
        })->name('AbsenKelas');
    });


    Route::get('/siswa-absen-kelas', function () {
        return view('SiswaAbsenKelas.index');
    })->name('siswa-absen-kelas');

    Route::get('/AbsenGerbang', function () {
        return view('AbsenGerbang.index');
    })->name('AbsenGerbang');

});
