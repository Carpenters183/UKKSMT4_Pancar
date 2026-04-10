<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Guru\AspirasiController as GuruAspirasiController;
use App\Http\Controllers\Siswa\AspirasiController as SiswaAspirasiController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard redirect
Route::get('/dashboard', function() {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'guru') return redirect()->route('guru.dashboard');
        if ($role === 'siswa') return redirect()->route('siswa.dashboard');
    }
    return redirect()->route('login');
})->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [DashboardController::class, 'users'])->name('users');
    
    // CRUD Admin
    Route::post('/admin/store', [DashboardController::class, 'storeAdmin'])->name('admin.store');
    Route::put('/admin/{id}', [DashboardController::class, 'updateAdmin'])->name('admin.update');
    Route::delete('/admin/{id}', [DashboardController::class, 'destroyAdmin'])->name('admin.destroy');
    
    // CRUD Siswa
    Route::get('/siswa/create', [DashboardController::class, 'createSiswa'])->name('siswa.create');
    Route::post('/siswa/store', [DashboardController::class, 'storeSiswa'])->name('siswa.store');
    Route::get('/siswa/{id}/edit', [DashboardController::class, 'editSiswa'])->name('siswa.edit');
    Route::put('/siswa/{id}', [DashboardController::class, 'updateSiswa'])->name('siswa.update');
    Route::delete('/siswa/{id}', [DashboardController::class, 'destroySiswa'])->name('siswa.destroy');
    
    // CRUD Guru
    Route::get('/guru/create', [DashboardController::class, 'createGuru'])->name('guru.create');
    Route::post('/guru/store', [DashboardController::class, 'storeGuru'])->name('guru.store');
    Route::get('/guru/{id}/edit', [DashboardController::class, 'editGuru'])->name('guru.edit');
    Route::put('/guru/{id}', [DashboardController::class, 'updateGuru'])->name('guru.update');
    Route::delete('/guru/{id}', [DashboardController::class, 'destroyGuru'])->name('guru.destroy');
    
    // Kategori Management
    Route::get('/kategori', [DashboardController::class, 'kategori'])->name('kategori');
    Route::post('/kategori', [DashboardController::class, 'storeKategori'])->name('kategori.store');
    Route::put('/kategori/{id}', [DashboardController::class, 'updateKategori'])->name('kategori.update');
    Route::delete('/kategori/{id}', [DashboardController::class, 'destroyKategori'])->name('kategori.destroy');
    
    // Pengaduan/Aspirasi Management
    Route::get('/pengaduan', [DashboardController::class, 'pengaduan'])->name('pengaduan');
    Route::get('/pengaduan/{id}', [DashboardController::class, 'pengaduanDetail'])->name('pengaduan.detail');
    Route::post('/pengaduan/{id}/status', [DashboardController::class, 'updateStatus'])->name('pengaduan.status');
    Route::post('/pengaduan/{id}/feedback', [DashboardController::class, 'storeFeedback'])->name('pengaduan.feedback');
    Route::post('/pengaduan/{id}/progres', [DashboardController::class, 'storeProgres'])->name('pengaduan.progres');
    
    // Sarana (placeholder)
    Route::get('/sarana', function () { return view('admin.sarana.index'); })->name('sarana');
});

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', function () { return view('guru.dashboard'); })->name('dashboard');
    
    // Aspirasi Management
    Route::get('/aspirasi', [GuruAspirasiController::class, 'index'])->name('aspirasi.index');
    Route::get('/aspirasi/{id}', [GuruAspirasiController::class, 'detail'])->name('aspirasi.detail');
    Route::post('/aspirasi/{id}/feedback', [GuruAspirasiController::class, 'storeFeedback'])->name('aspirasi.feedback');
    Route::post('/aspirasi/{id}/progres', [GuruAspirasiController::class, 'storeProgres'])->name('aspirasi.progres');
    Route::get('/history', [GuruAspirasiController::class, 'history'])->name('history');
});

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');
    
    // Aspirasi Management
    Route::get('/aspirasi/create', [App\Http\Controllers\Siswa\AspirasiController::class, 'create'])->name('aspirasi.create');
    Route::post('/aspirasi', [App\Http\Controllers\Siswa\AspirasiController::class, 'store'])->name('aspirasi.store');
    Route::get('/aspirasi/status', [App\Http\Controllers\Siswa\AspirasiController::class, 'status'])->name('aspirasi.status');
    Route::get('/aspirasi/history', [App\Http\Controllers\Siswa\AspirasiController::class, 'history'])->name('aspirasi.history');
    Route::get('/aspirasi/feedback', [App\Http\Controllers\Siswa\AspirasiController::class, 'feedback'])->name('aspirasi.feedback');
    Route::get('/aspirasi/{id}', [App\Http\Controllers\Siswa\AspirasiController::class, 'detail'])->name('aspirasi.detail');
});

// Profile Routes
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/my-account', [App\Http\Controllers\ProfileController::class, 'myAccount'])->name('my-account');
    Route::get('/settings', [App\Http\Controllers\ProfileController::class, 'settings'])->name('settings');
    Route::put('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
    Route::post('/update-photo', [App\Http\Controllers\ProfileController::class, 'updatePhoto'])->name('update-photo');
});

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');
    // ... routes lainnya
});

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');
    // ... routes lainnya
});

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');
    
    // Aspirasi Management
    Route::get('/aspirasi', [App\Http\Controllers\Guru\AspirasiController::class, 'index'])->name('aspirasi.index');
    Route::get('/aspirasi/{id}', [App\Http\Controllers\Guru\AspirasiController::class, 'detail'])->name('aspirasi.detail');
    Route::post('/aspirasi/{id}/feedback', [App\Http\Controllers\Guru\AspirasiController::class, 'storeFeedback'])->name('aspirasi.feedback');
    Route::post('/aspirasi/{id}/progres', [App\Http\Controllers\Guru\AspirasiController::class, 'storeProgres'])->name('aspirasi.progres');
    Route::get('/aspirasi/{id}/selesai', [App\Http\Controllers\Guru\AspirasiController::class, 'updateStatusToSelesai'])->name('aspirasi.selesai');
    Route::get('/history', [App\Http\Controllers\Guru\AspirasiController::class, 'history'])->name('history');
});

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    // ... route lainnya
    Route::delete('/aspirasi/{id}', [App\Http\Controllers\Guru\AspirasiController::class, 'destroy'])->name('aspirasi.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... route lainnya
    Route::delete('/pengaduan/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'destroyAspirasi'])->name('pengaduan.destroy');
});