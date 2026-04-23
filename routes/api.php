<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/productos', [ProductoController::class, 'indexApi']);
Route::post('/login', [AuthenticatedSessionController::class, 'loginApi']);
