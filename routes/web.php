<?php

use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\DatoController;
use App\Http\Controllers\BienesController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('layouts.app');
Route::resource('producto', ProductoController::class);
Route::get('productos/por-clasificacion/{clasificacion_id}', [ProductoController::class, 'porClasificacion']);
Route::resource('movimiento', MovimientoController::class);
Route::resource('datos', DatoController::class);
Route::resource('certificados', CertificadoController::class);
Route::post('certificados/agregar-stock', [CertificadoController::class, 'agregarStock'])->name('certificados.agregar-stock');
Route::resource('/usuario', UsuarioController::class);
Route::resource('/bienes', BienesController::class);
Route::resource('/materiales', MaterialController::class);
Route::resource('/equipos', EquipoController::class);

Route::get('login', [UsuarioController::class, 'loginfrm'])->name('login');
Route::post('login', [UsuarioController::class, 'login'])->name('loginpost');
Route::post('logout', [UsuarioController::class, 'logout'])->name('logout');

Route::post('producto/importar', [ProductoController::class, 'importar'])->name('producto.importar');
