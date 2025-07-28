<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use App\Models\Movimiento;
use App\Models\Producto;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        View::composer('plantilla.header', function ($view) {
            $hoy = Carbon::today();
            $proximos = Movimiento::with('producto')
                ->where('tipo_movimiento', 'Entrada')
                ->whereDate('fecha_vencimiento', '>', $hoy)
                ->whereDate('fecha_vencimiento', '<=', $hoy->copy()->addDays(30))
                ->get();

            $vencidos = Movimiento::with('producto')
                ->where('tipo_movimiento', 'Entrada')
                ->whereDate('fecha_vencimiento', '<', $hoy)
                ->get();

            // $productos = Producto::all();

            $view->with(compact('proximos', 'vencidos'));
        });
    }
}
