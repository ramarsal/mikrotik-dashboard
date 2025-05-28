<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MikroTikController;

Route::get('/mikrotik-dashboard', [MikroTikController::class, 'index']);
