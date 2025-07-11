<?php

use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\DatoController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\ProductoController;

use Illuminate\Support\Facades\Route;

route::get('/', [MainController::class, 'index'])->name('layouts.app');
Route::resource('producto', ProductoController::class);
Route::get('productos/por-clasificacion/{clasificacion_id}', [ProductoController::class, 'porClasificacion']);
Route::resource('movimiento', MovimientoController::class);
Route::resource('datos', DatoController::class);
Route::resource('certificados', CertificadoController::class);

Route::post('producto/importar', [ProductoController::class, 'importar'])->name('producto.importar');

