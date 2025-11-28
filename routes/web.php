<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MerkacController;
use App\Http\Controllers\JenisacController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\DetailacController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartementController;




// User Routes
Route::get('/', [UserController::class, 'index'])->name('homepage');

// Route Form Input Data User
Route::get('/get-ruangan/{id}', [UserController::class, 'getRuangan'])->name('data.ruangan')->Middleware('Role:Teknisi');
Route::get('/input-data-ac', [UserController::class, 'create'])->name('formcreate')->Middleware('Role:Teknisi');
Route::post('/input-data-ac/store', [UserController::class, 'store'])->name('ac.store')->Middleware('Role:Teknisi');

// Login Routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/autentikasi', [LoginController::class, 'autentikasi'])->name('autentikasi');

// View Admin Dashboard
Route::get('/admin/dashboard', [LoginController::class, 'admin'])->name('dashboard')->Middleware('Role:Admin');

// View Dashboard
// Route::get('/admin/dashboard', [DashboardController::class, 'show'])->name('dashboard');


// ================================ Merk AC ==================================
// Merk AC Routes
Route::get('/admin/merk-ac', [MerkacController::class, 'index'])->name('merk-ac')->Middleware('Role:Admin');
Route::get('/admin/merk-ac/data', [MerkacController::class, 'getData'])->name('merk-ac.data')->Middleware('Role:Admin');

// Create Merk AC Form Route
Route::get('/admin/merk-ac/create', [MerkacController::class, 'create'])->name('merk-ac.create')->Middleware('Role:Admin');
// Store New Merk AC Route
Route::post('/admin/merk-ac/store', [MerkacController::class, 'store'])->name('merk-ac.store')->Middleware('Role:Admin');

// Edit Merk AC Form Route
Route::get('/merk-ac/{id}/edit', [MerkACController::class, 'edit'])->name('merk-ac.edit')->Middleware('Role:Admin');
Route::put('/merk-ac/{id}', [MerkACController::class, 'update'])->name('merk-ac.update')->Middleware('Role:Admin');

// Delete Merk AC Route
Route::delete('/merk-ac/{id}', [MerkACController::class, 'destroy'])->name('merk-ac.destroy')->Middleware('Role:Admin');


// ================================ Jenis AC ==================================
// Jenis AC Routes
Route::get('/admin/jenis-ac', [JenisacController::class, 'index'])->name('jenis-ac')->Middleware('Role:Admin');
Route::get('/admin/jenis-ac/data', [JenisacController::class, 'getData'])->name('jenis-ac.data')->Middleware('Role:Admin');

// Create Jenis AC Form Route
Route::get('/admin/jenis-ac/create', [JenisacController::class, 'create'])->name('jenis-ac.create')->Middleware('Role:Admin');
// Store New Jenis AC Route
Route::post('/admin/jenis-ac/store', [JenisacController::class, 'store'])->name('jenis-ac.store')->Middleware('Role:Admin');

// Edit Jenis AC Form Route
Route::get('/jenis-ac/{id}/edit', [JenisacController::class, 'edit'])->name('jenis-ac.edit')->Middleware('Role:Admin');
Route::put('/jenis-ac/{id}', [JenisacController::class, 'update'])->name('jenis-ac.update')->Middleware('Role:Admin');

// Delete Jenis AC Route
Route::delete('/jenis-ac/{id}', [JenisacController::class, 'destroy'])->name('jenis-ac.destroy')->Middleware('Role:Admin');


// ================================ Detail AC ==================================
// Detail AC Routes
Route::get('/admin/detail-ac', [DetailacController::class, 'index'])->name('detail-ac')->Middleware('Role:Admin');
Route::get('/admin/detail-ac/data', [DetailacController::class, 'getData'])->name('detail-ac.data')->Middleware('Role:Admin');

// Show Detail AC Route
Route::get('/admin/detail-ac/show/{id}', [DetailacController::class, 'show'])->name('detail-ac.show')->Middleware('Role:Admin');

// Create Detail AC Form Route
Route::get('/admin/detail-ac/create', [DetailacController::class, 'create'])->name('detail-ac.create')->Middleware('Role:Admin');
// Store New Detail AC Route
Route::post('/admin/detail-ac/store', [DetailacController::class, 'store'])->name('detail-ac.store')->Middleware('Role:Admin');

// Edit Detail AC Form Route
Route::get('/detail-ac/{id}/edit', [DetailacController::class, 'edit'])->name('detail-ac.edit')->Middleware('Role:Admin');
Route::put('/detail-ac/{id}', [DetailacController::class, 'update'])->name('detail-ac.update')->Middleware('Role:Admin');

// Delete Detail AC Route
Route::delete('/detail-ac/{id}', [DetailacController::class, 'destroy'])->name('detail-ac.destroy')->Middleware('Role:Admin');

// ================================ Departement ==================================
// Departement Routes
Route::get('/admin/departement', [DepartementController::class, 'index'])->name('departement')->Middleware('Role:Admin');
Route::get('/admin/departement/data', [DepartementController::class, 'getData'])->name('departement.data')->Middleware('Role:Admin');

// Edit Departement Form Route
Route::get('/departement/{id}/edit', [DepartementController::class, 'edit'])->name('departement.edit')->Middleware('Role:Admin');
Route::put('/departement/{id}', [DepartementController::class, 'update'])->name('departement.update')->Middleware('Role:Admin');

// Create Departement Form Route
Route::get('/admin/departement/create', [DepartementController::class, 'create'])->name('departement.create')->Middleware('Role:Admin');
// Store New Departement Route
Route::post('/admin/departement/store', [DepartementController::class, 'store'])->name('departement.store')->Middleware('Role:Admin');

// Delete Departement Route
Route::delete('/departement/{id}', [DepartementController::class, 'destroy'])->name('departement.destroy')->Middleware('Role:Admin');

// ================================ Ruangan ==================================
// Ruangan Routes
Route::get('/admin/ruangan', [RuanganController::class, 'index'])->name('ruangan')->Middleware('Role:Admin');
Route::get('/admin/ruangan/data', [RuanganController::class, 'getData'])->name('ruangan.data');

// Create Ruangan Form Route
Route::get('/admin/ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create')->Middleware('Role:Admin');
// Store New Ruangan Route
Route::post('/admin/ruangan/store', [RuanganController::class, 'store'])->name('ruangan.store')->Middleware('Role:Admin');

// Edit Ruangan Form Route
Route::get('/ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit')->Middleware('Role:Admin');
Route::put('/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update')->Middleware('Role:Admin');

// Delete Ruangan Route
Route::delete('/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy')->Middleware('Role:Admin');

// ================================ Pengguna ==================================
// Pengguna Routes
Route::get('/admin/pengguna', [PenggunaController::class, 'index'])->name('pengguna')->Middleware('Role:Admin');
Route::get('/admin/pengguna/data', [PenggunaController::class, 'getData'])->name('pengguna.data')->Middleware('Role:Admin');

// Create Pengguna Form Route
Route::get('/admin/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create')->Middleware('Role:Admin');
// Store New Pengguna Route
Route::post('/admin/pengguna/store', [PenggunaController::class, 'store'])->name('pengguna.store')->Middleware('Role:Admin');

// Edit Pengguna Form Route
Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit')->Middleware('Role:Admin');
Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update')->Middleware('Role:Admin');

// Delete Pengguna Route
Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy')->Middleware('Role:Admin');

// Logout Route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
