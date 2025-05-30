<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MikroTikController;

//Route::get('/mikrotik-dashboard', [MikroTikController::class, 'index']);
Route::get('/mikrotik-dashboard', [MikroTikController::class, 'index'])->name('mikrotik.dashboard');
Route::get('/mikrotik-dashboard/json', [MikroTikController::class, 'json'])->name('mikrotik.dashboard.json');
Route::get('/mikrotik-traffic', [MikroTikController::class, 'traffic']);
Route::get('/', [MikroTikController::class, 'index']);


