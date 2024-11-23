<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ShodanController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::post('/scan', [DashboardController::class, 'scan'])->name('scan');
Route::get('/result/{id}', [DashboardController::class, 'showResult'])->name('result');


Route::get('/shodan', [ShodanController::class, 'index'])->name('search.index');
Route::get('/shodan/search', [ShodanController::class, 'search'])->name('shodan.search');

Route::get('/scan', [ScanController::class, 'index'])->name('ipscanner');
Route::post('/shodan/scan', [ScanController::class, 'scan']); // Rute untuk melakukan pemindaian


// Route::post('/scan', [ScanController::class, 'storeScan'])->name('scan.store');


