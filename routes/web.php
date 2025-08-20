<?php

use App\Http\Controllers\DatoController;
use App\Http\Controllers\BienesController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Models\Usuario;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('layouts.app');

Route::middleware('auth', 'admin')->group(function() {
    Route::resource('producto', ProductoController::class);
    Route::resource('movimiento', MovimientoController::class);
});

Route::middleware('auth', 'user')->group(function() {
    Route::resource('movimiento', MovimientoController::class);
});

// Route::middleware('auth', 'manual')->group(function(){
//     Route::resource('movimiento', MovimientoController::class);
// });


Route::resource('usuario', UsuarioController::class);
Route::get('productos/por-clasificacion/{clasificacion_id}', [ProductoController::class, 'porClasificacion']);
Route::resource('datos', DatoController::class);
Route::resource('/bienes', BienesController::class);
Route::resource('/materiales', MaterialController::class);
Route::resource('/equipos', EquipoController::class);


route::get('/login', [UsuarioController::class, 'loginfrm'])->name('login');
route::post('/login', [UsuarioController::class, 'login'])->name('loginpost');
route::get('logout', [UsuarioController::class, 'logout'])->name('logout');
route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
route::get('reportes/exportar-pdf', [ReporteController::class, 'exportarPDF'])->name('reportes.exportar-pdf');

Route::post('producto/importar', [ProductoController::class, 'importar'])->name('producto.importar');
