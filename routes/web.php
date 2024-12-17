<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ShodanController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('leandingpage');
});

Route::get('/login', [AuthController::class, 'index'])->name('login.index');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::post('/scan', [DashboardController::class, 'scan'])->name('scan');
Route::get('/result/{id}', [DashboardController::class, 'showResult'])->name('result');
Route::get('/result/{id}/export', [DashboardController::class, 'exportPdf'])->name('dashboard.exportPdf');

Route::get('/shodan', [ShodanController::class, 'index'])->name('search.index');
Route::get('/shodan/search', [ShodanController::class, 'search'])->name('shodan.search');

Route::get('/scan', [ScanController::class, 'index'])->name('ipscanner');
Route::post('/shodan/scan', [ScanController::class, 'scan']); // Rute untuk melakukan pemindaian

Route::get('/domains', [DomainController::class, 'index'])->name('domains.index');
Route::post('/domains', [DomainController::class, 'fetchAndStoreDomainData'])->name('domains.store');
Route::get('/domains/download-pdf', [DomainController::class, 'downloadPdf'])->name('domains.downloadPdf');


// Route::post('/scan', [ScanController::class, 'storeScan'])->name('scan.store');


