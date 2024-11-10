<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ShodanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/shodan', [ShodanController::class, 'index']);
Route::post('/shodan/scan', [ShodanController::class, 'scan']);

Route::post('/scan', [ScanController::class, 'storeScan'])->name('scan.store');


