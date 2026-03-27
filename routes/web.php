<?php

use App\Http\Controllers\AdminSPKController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\DetailacController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\JenisacController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MerkacController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportPerbaikanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\SPKAprovalController;
use App\Http\Controllers\SPKController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\HistoryController as SuperadminHistoryController;
use App\Http\Controllers\Superadmin\SPKController as SuperadminSPKController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// Homepage Routes
Route::get('/', [UserController::class, 'index'])->name('homepage');

// Login Routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/autentikasi', [LoginController::class, 'autentikasi'])->name('autentikasi');

// Route Role: Teknisi
Route::middleware(['Role:Teknisi'])->group(function () {
    Route::get('/get-ruangan/{id}', [UserController::class, 'getRuangan'])->name('data.ruangan');
    Route::get('/data-ac-rsal', [UserController::class, 'pagedata'])->name('ac.date.page');
    Route::get('/data-ac', [UserController::class, 'show'])->name('ac.data');
    Route::get('/data-ac/detail/{id}', [UserController::class, 'detail'])->name('detail.ac');
    Route::get('/input-data-spk', [SPKController::class, 'create'])->name('formcreatespk');
    Route::post('/input-data-spk/store', [SPKController::class, 'store'])->name('spk.store');
    // Route::get('/input-data-ac', [UserController::class, 'create'])->name('formcreate');
    // Route::post('/input-data-ac/store', [UserController::class, 'store'])->name('ac.store');

    Route::get('/check-pending-spk', [SPKAprovalController::class, 'checkPendingSpk'])->name('spk.check.pending');
    Route::middleware(['only.spk', 'pending.spk'])->group(function() {
        Route::get('/data-spk', [SPKAprovalController::class, 'index'])->name('spk.index');
        Route::get('/spk/data', [SPKAprovalController::class, 'data'])->name('spk.data.teknisi');
        Route::get('/spk/detail/{id}', [SPKAprovalController::class, 'detail'])->name('spk.detail');
        Route::post('/spk/approve/{id}', [SPKAprovalController::class, 'approve'])->name('spk.approve');
        Route::post('/spk/selesai/{id}', [SPKAprovalController::class, 'selesai'])->name('spk.selesai');
        Route::post('/update-keterangan/{id}', [SPKAprovalController::class, 'updateKeterangan'])->name('spk.update.keterangan');

    });
    
});


Route::middleware(['Role:Superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superadmin'])->name('dashboard.superadmin');
    Route::get('/superadmin/dashboard/chart', [DashboardController::class, 'chartData'])->name('chart.superadmin');
    Route::get('/superadmin/history', [SuperadminHistoryController::class, 'index'])->name('history.superadmin');
    Route::get('/superadmin/history/search', [SuperadminHistoryController::class, 'search'])->name('searching.history');
    Route::get('/superadmin/spk', [SuperadminSPKController::class, 'index'])->name('superadmin.spk');
    Route::get('/superadmin/spk/data', [SuperadminSPKController::class, 'getData'])->name('superadmin.spk.data');
    Route::get('/superadmin/spk/export', [SuperadminSPKController::class, 'exportPdf'])->name('superadmin.spk.export');
    Route::get('/superadmin/spk/create', [SuperadminSPKController::class, 'create'])->name('superadmin.formcreate');
    Route::post('/superadmin/spk/store', [SuperadminSPKController::class, 'store'])->name('superadmin.spk.store');
    Route::get('/superadmin/spk/{id}/edit', [SuperadminSPKController::class, 'edit'])->name('superadmin.spk.edit');
    Route::put('/superadmin/spk/{id}', [SuperadminSPKController::class, 'update'])->name('superadmin.spk.update');
    Route::delete('/superadmin/spk/{id}', [SuperadminSPKController::class, 'destroy'])->name('superadmin.spk.destroy');
    Route::get('/superadmin/spk/detail/{id}', [SuperadminSPKController::class, 'detail'])->name('superadmin.spk.detail');
    Route::get('/superadmin/spk/detail/{id}/download', [SuperadminSPKController::class, 'exportdetail'])->name('superadmin.export.detail');
    
});



// =============================== Admin Routes =================================

// View Admin Dashboard
Route::get('/admin/dashboard', [LoginController::class, 'admin'])->name('dashboard')->middleware('Role:Admin');

// line-chart Dashboard
Route::get('/admin/dashboard/chart', [LoginController::class, 'chartData'])->name('dashboard.chart')->middleware('Role:Admin');

// View History
Route::get('/admin/history', [HistoryController::class, 'index'])->name('history')->middleware('Role:Admin');
Route::get('/admin/history/search', [HistoryController::class, 'search'])->name('history.search')->middleware('Role:Admin');

// View Dashboard
// Route::get('/admin/dashboard', [DashboardController::class, 'show'])->name('dashboard');

// ============================== SPK Routes =================================
Route::get('/admin/spk', [AdminSPKController::class, 'index'])->name('admin.spk')->middleware('Role:Admin');
Route::get('/admin/spk/data', [AdminSPKController::class, 'getData'])->name('spk.data')->middleware('Role:Admin');
Route::post('/admin/spk/{id}/hpp', [AdminSPKController::class, 'storeHpp'])->name('spk.store.hpp')->middleware('Role:Admin');
Route::get('/admin/spk/export-pdf', [AdminSPKController::class, 'exportPdf'])->name('spk.exportPdf')->middleware('Role:Admin');

// Create SPK Route
Route::get('/admin/spk/create', [AdminSPKController::class, 'create'])->name('spk.create')->middleware('Role:Admin');
Route::post('/admin/spk/store', [AdminSPKController::class, 'store'])->name('spkadmin.store')->middleware('Role:Admin');

// Edit SPK Route
Route::get('/admin/spk/{id}/edit', [AdminSPKController::class, 'edit'])->name('spk.edit')->middleware('Role:Admin');
Route::put('/admin/spk/{id}', [AdminSPKController::class, 'update'])->name('spk.update')->middleware('Role:Admin');

// Delete SPK Route
Route::delete('/admin/spk/{id}', [AdminSPKController::class, 'destroy'])->name('spk.destroy')->middleware('Role:Admin');

// Detail SPK Route
Route::get('/admin/spk/detail/{id}', [AdminSPKController::class, 'detail'])->name('spk.detail')->middleware('Role:Admin');
Route::get('/admin/spk/detail/{id}/download', [AdminSPKController::class, 'downloadpdf'])->name('spkdetail.download')->middleware('Role:Admin');

// Generate SPK PDF Route
// Route::get('/admin/spk/{id}/generate-pdf', [AdminSPKController::class, 'generatePdf'])->name('spk.generatePdf')->middleware('Role:Admin');

// ================================ Report Routes =================================
Route::get('/admin/report/dokumentasi', [ReportController::class, 'index'])->name('admin.report')->middleware('Role:Admin');
Route::get('/admin/report/dokumentasi/data', [ReportController::class, 'getDokumentasi'])
    ->name('admin.report.data')->middleware('Role:Admin');

Route::get('/admin/report/dokumentasi/export', [ReportController::class, 'exportPdf'])
    ->name('admin.report.export')->middleware('Role:Admin');

Route::get('/admin/report/perbaikan', [ReportPerbaikanController::class, 'index'])->name('admin.reportperbaikan')->middleware('Role:Admin');
Route::get('/admin/report-perbaikan/get', [ReportPerbaikanController::class, 'getReport'])->name('admin.data.perbaikan')->middleware('Role:Admin');
Route::get('/admin/report/perbaikan/export', [ReportPerbaikanController::class, 'exportPdf'])->name('admin.reportpdf')->middleware('Role:Admin');

Route::get('/admin/report/teknisi', [ReportPerbaikanController::class, 'viewteknisi'])->name('admin.reportteknisi')->middleware('Role:Admin');
Route::get('/admin/report/teknisi/get', [ReportPerbaikanController::class, 'getReportTeknisi'])->name('admin.data.teknisi')->middleware('Role:Admin');
Route::get('/admin/report/teknisi/export', [ReportPerbaikanController::class, 'exportReportTeknisi'])->name('admin.reportteknisipdf')->middleware('Role:Admin');

    // ================================ Merk AC ==================================
// Merk AC Routes
Route::get('/admin/merk-ac', [MerkacController::class, 'index'])->name('merk-ac')->middleware('Role:Admin');
Route::get('/admin/merk-ac/data', [MerkacController::class, 'getData'])->name('merk-ac.data')->middleware('Role:Admin');

// Create Merk AC Form Route
Route::get('/admin/merk-ac/create', [MerkacController::class, 'create'])->name('merk-ac.create')->middleware('Role:Admin');
// Store New Merk AC Route
Route::post('/admin/merk-ac/store', [MerkacController::class, 'store'])->name('merk-ac.store')->middleware('Role:Admin');

// Edit Merk AC Form Route
Route::get('/merk-ac/{id}/edit', [MerkacController::class, 'edit'])->name('merk-ac.edit')->middleware('Role:Admin');
Route::put('/merk-ac/{id}', [MerkacController::class, 'update'])->name('merk-ac.update')->middleware('Role:Admin');

// Delete Merk AC Route
Route::delete('/merk-ac/{id}', [MerkacController::class, 'destroy'])->name('merk-ac.destroy')->middleware('Role:Admin');


// ================================ Jenis AC ==================================
// Jenis AC Routes
Route::get('/admin/jenis-ac', [JenisacController::class, 'index'])->name('jenis-ac')->middleware('Role:Admin');
Route::get('/admin/jenis-ac/data', [JenisacController::class, 'getData'])->name('jenis-ac.data')->middleware('Role:Admin');

// Create Jenis AC Form Route
Route::get('/admin/jenis-ac/create', [JenisacController::class, 'create'])->name('jenis-ac.create')->middleware('Role:Admin');
// Store New Jenis AC Route
Route::post('/admin/jenis-ac/store', [JenisacController::class, 'store'])->name('jenis-ac.store')->middleware('Role:Admin');

// Edit Jenis AC Form Route
Route::get('/jenis-ac/{id}/edit', [JenisacController::class, 'edit'])->name('jenis-ac.edit')->middleware('Role:Admin');
Route::put('/jenis-ac/{id}', [JenisacController::class, 'update'])->name('jenis-ac.update')->middleware('Role:Admin');

// Delete Jenis AC Route
Route::delete('/jenis-ac/{id}', [JenisacController::class, 'destroy'])->name('jenis-ac.destroy')->middleware('Role:Admin');


// ================================ Detail AC ==================================
// Detail AC Routes
Route::get('/admin/detail-ac', [DetailacController::class, 'index'])->name('detail-ac')->middleware('Role:Admin');
Route::get('/admin/detail-ac/data', [DetailacController::class, 'getData'])->name('detail-ac.data')->middleware('Role:Admin');

// Show Detail AC Route
Route::get('/admin/detail-ac/show/{id}', [DetailacController::class, 'show'])->name('detail-ac.show')->middleware('Role:Admin');

// Create Detail AC Form Route
Route::get('/admin/detail-ac/create', [DetailacController::class, 'create'])->name('detail-ac.create')->middleware('Role:Admin');
// Store New Detail AC Route
Route::post('/admin/detail-ac/store', [DetailacController::class, 'store'])->name('detail-ac.store')->middleware('Role:Admin');

// Edit Detail AC Form Route
Route::get('/detail-ac/{id}/edit', [DetailacController::class, 'edit'])->name('detail-ac.edit')->middleware('Role:Admin');
Route::put('/detail-ac/{id}', [DetailacController::class, 'update'])->name('detail-ac.update')->middleware('Role:Admin');

// Delete Detail AC Route
Route::delete('/detail-ac/{id}', [DetailacController::class, 'destroy'])->name('detail-ac.destroy')->middleware('Role:Admin');

// ================================ Departement ==================================
// Departement Routes
Route::get('/admin/departement', [DepartementController::class, 'index'])->name('departement')->middleware('Role:Admin');
Route::get('/admin/departement/data', [DepartementController::class, 'getData'])->name('departement.data')->middleware('Role:Admin');

// Edit Departement Form Route
Route::get('/departement/{id}/edit', [DepartementController::class, 'edit'])->name('departement.edit')->middleware('Role:Admin');
Route::put('/departement/{id}', [DepartementController::class, 'update'])->name('departement.update')->middleware('Role:Admin');

// Create Departement Form Route
Route::get('/admin/departement/create', [DepartementController::class, 'create'])->name('departement.create')->middleware('Role:Admin');
// Store New Departement Route
Route::post('/admin/departement/store', [DepartementController::class, 'store'])->name('departement.store')->middleware('Role:Admin');

// Delete Departement Route
Route::delete('/departement/{id}', [DepartementController::class, 'destroy'])->name('departement.destroy')->middleware('Role:Admin');

// ================================ Ruangan ==================================
// Ruangan Routes
Route::get('/admin/ruangan', [RuanganController::class, 'index'])->name('ruangan')->middleware('Role:Admin');
Route::get('/admin/ruangan/data', [RuanganController::class, 'getData'])->name('ruangan.data');

// Create Ruangan Form Route
Route::get('/admin/ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create')->middleware('Role:Admin');
// Store New Ruangan Route
Route::post('/admin/ruangan/store', [RuanganController::class, 'store'])->name('ruangan.store')->middleware('Role:Admin');

// Edit Ruangan Form Route
Route::get('/ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit')->middleware('Role:Admin');
Route::put('/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update')->middleware('Role:Admin');

// Delete Ruangan Route
Route::delete('/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy')->middleware('Role:Admin');

// ================================ Pengguna ==================================
// Pengguna Routes
Route::get('/admin/pengguna', [PenggunaController::class, 'index'])->name('pengguna')->middleware('Role:Admin');
Route::get('/admin/pengguna/data', [PenggunaController::class, 'getData'])->name('pengguna.data')->middleware('Role:Admin');

// Create Pengguna Form Route
Route::get('/admin/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create')->middleware('Role:Admin');
// Store New Pengguna Route
Route::post('/admin/pengguna/store', [PenggunaController::class, 'store'])->name('pengguna.store')->middleware('Role:Admin');

// Edit Pengguna Form Route
Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit')->middleware('Role:Admin');
Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update')->middleware('Role:Admin');

// Delete Pengguna Route
Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy')->middleware('Role:Admin');

// Logout Route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
