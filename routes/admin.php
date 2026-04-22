<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductoController;

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // =====================
        // DASHBOARD
        // =====================
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // =====================
        // USUARIOS
        // =====================
        Route::get('/usuarios', function () {
            return view('admin.usuarios.index');
        })->name('usuarios');

        // =====================
        // PRODUCTOS
        // =====================
        Route::get('/productos', [ProductoController::class, 'index'])
            ->name('productos');

        // =====================
        // AJAX / SELECTS
        // =====================
        Route::get('/modelos/{categoria}', [ProductoController::class, 'getModelos'])
            ->name('modelos');

        Route::get('/tallas/{modelo}', [ProductoController::class, 'getTallas'])
            ->name('tallas');

        Route::get('/colores/{modelo}', [ProductoController::class, 'getColores'])
            ->name('colores');

        Route::get('/producto-info/{modelo}', [ProductoController::class, 'getProductoInfo'])
            ->name('producto.info');

        // =====================
        // STOCK
        // =====================
        Route::post('/stock-global', [ProductoController::class, 'agregarStockGlobal'])
            ->name('stock.global');

        Route::post('/stock-producto', [ProductoController::class, 'agregarStockProducto'])
            ->name('stock.producto');

    });