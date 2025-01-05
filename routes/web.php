<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAsetController;
use App\Http\Controllers\DataKepemilikanAsetController;
use App\Http\Controllers\DataKependudukanController;
use App\Http\Controllers\DataKondisiRumahController;
use App\Http\Controllers\DataLayananController;
use App\Http\Controllers\DataProgramController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'dashboard']
)->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {

    // --- Data Kependudukan ---
    Route::get('/data-kependudukan',[DataKependudukanController::class,'index'])->name('penduduk.index');
    Route::get('/data-kependudukan/create',[DataKependudukanController::class,'create'])->name('penduduk.create');
    Route::post('/add-data-kependudukan',[DataKependudukanController::class,'store'])->name('penduduk.store');
    Route::get('/data-kependudukan/{dataKeluarga}/edit',[DataKependudukanController::class,'edit'])->name('penduduk.edit');
    Route::patch('/data-kependudukan/{dataKeluarga}',[DataKependudukanController::class,'update'])->name('penduduk.update');
    Route::delete('/delete-data-kependudukan/{dataKeluarga}',[DataKependudukanController::class,'destroy'])->name('penduduk.destroy');
    Route::get('/export-data-keluarga',[ExportController::class,'exportDataKependudukan'])->name('penduduk.export');

    // --- Data Kondisi Rumah ---
    Route::get('/data-kondisi-rumah',[DataKondisiRumahController::class,'index'])->name('bangunan.index');
    Route::get('/data-kondisi-rumah/create',[DataKondisiRumahController::class,'create'])->name('bangunan.create');
    Route::post('/add-data-kondisi-rumah',[DataKondisiRumahController::class,'store'])->name('bangunan.store');
    Route::get('/data-kondisi-rumah/{dataBangunan}/edit',[DataKondisiRumahController::class,'edit'])->name('bangunan.edit');
    Route::patch('/data-kondisi-rumah/{dataBangunan}',[DataKondisiRumahController::class,'update'])->name('bangunan.update');
    Route::delete('/delete-data-kondisi-rumah/{dataBangunan}',[DataKondisiRumahController::class,'destroy'])->name('bangunan.destroy');
    Route::get('/export-data-bangunan',[ExportController::class,'exportDataKondisiRumah'])->name('bangunan.export');

    // --- Data Program ---
    Route::get('/programbantuan',[DataProgramController::class,'index'])->name('program.index');
    Route::get('/programbantuan/create',[DataProgramController::class,'create'])->name('program.create');
    Route::post('/add-programbantuan',[DataProgramController::class,'store'])->name('program.store');
    Route::get('/programbantuan/{dataProgram}/edit',[DataProgramController::class,'edit'])->name('program.edit');
    Route::patch('/programbantuan/{dataProgram}',[DataProgramController::class,'update'])->name('program.update');
    Route::delete('/delete-programbantuan/{dataProgram}',[DataProgramController::class,'destroy'])->name('program.destroy');
    Route::get('/export-programbantuan',[ExportController::class,'exportDataProgram'])->name('program.export');

    // --- Data Aset ---
    Route::get('/aset',[DataAsetController::class,'index'])->name('aset.index');
    Route::get('/aset/create', [DataAsetController::class, 'create'])->name('aset.create');
    Route::post('/add-aset', [DataAsetController::class, 'store'])->name('aset.store');
    Route::get('/aset/{dataAset}/edit', [DataAsetController::class, 'edit'])->name('aset.edit');
    Route::patch('/aset/{dataAset}', [DataAsetController::class, 'update'])->name('aset.update');
    Route::delete('/delete-aset/{dataAset}', [DataAsetController::class, 'destroy'])->name('aset.destroy');
    Route::get('/export-aset', [ExportController::class, 'exportDataAset'])->name('aset.export');

    // --- Data Layanan Rekening & E-Wallet ---
    Route::get('/layanan',[DataLayananController::class,'index'])->name('layanan.index');
    Route::get('/layanan/create',[DataLayananController::class,'create'])->name('layanan.create');
    Route::post('/add-layanan',[DataLayananController::class,'store'])->name('layanan.store');
    Route::get('/layanan/{dataLayanan}/edit',[DataLayananController::class,'edit'])->name('layanan.edit');
    Route::patch('/layanan/{dataLayanan}',[DataLayananController::class,'update'])->name('layanan.update');
    Route::delete('/delete-layanan/{dataLayanan}',[DataLayananController::class,'destroy'])->name('layanan.destroy');
    Route::get('/export-layanan',[ExportController::class,'exportDataLayanan'])->name('layanan.export');
});

require __DIR__.'/auth.php';
