<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAsetController;
use App\Http\Controllers\DataKepemilikanAsetController;
use App\Http\Controllers\DataKependudukanController;
use App\Http\Controllers\DataKondisiRumahController;
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

    // --- Data Aset ---
    Route::get('/aset',[DataAsetController::class,'index'])->name('aset.index');
    Route::get('/aset/create', function () {
        return view('dataAset.create');
    })->name('aset.create');

});

require __DIR__.'/auth.php';
