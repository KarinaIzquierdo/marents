<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/productos', [ProductoController::class, 'indexApi']);
Route::post('/login', [AuthenticatedSessionController::class, 'loginApi']);
Route::post('/register', [RegisteredUserController::class, 'registerApi']);
Route::get('/users', [UsuarioController::class, 'indexApi']);
Route::delete('/users/{id}', [UsuarioController::class, 'destroyApi']);
