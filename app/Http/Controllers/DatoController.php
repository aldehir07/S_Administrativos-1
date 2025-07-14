<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use App\Models\Dato;
use App\Models\Producto;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Productos en stock crítico
        $productosCriticos = Producto::with('clasificacion')
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->get();

        // Estadísticas generales
        $totalCertificados = Certificado::count();
        $totalProductos = Producto::count();
        $productosSinStock = Producto::where('stock_actual', 0)->count();
        $productosStockExcesivo = Producto::whereRaw('stock_actual > (stock_minimo * 3)')->count();

        // Estadísticas de certificados
        $stockCertificados = Certificado::sum('stock_actual');
        $certificadosUsados = Certificado::where('stock_actual', 0)->sum('cantidad');
        $certificadosAgregados = Certificado::where('stock_actual', '>', 0)->sum('cantidad');
        $ultimosCertificadosUsados = Certificado::where('stock_actual', 0)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Productos que necesitan reabastecimiento (stock actual <= stock mínimo)
        $productosNecesitanReabastecimiento = $productosCriticos->count();

        // Productos con movimiento reciente (últimos 30 días)
        $productosSinMovimiento = Producto::whereDoesntHave('movimientos', function($query) {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        })->count();

        // Productos por clasificación
        $productosPorClasificacion = Producto::with('clasificacion')
            ->selectRaw('clasificacion_id, COUNT(*) as total, SUM(stock_actual) as stock_total')
            ->groupBy('clasificacion_id')
            ->get();

        // Últimos movimientos
        $ultimosMovimientos = Movimiento::with(['producto', 'clasificacion'])
            ->latest()
            ->take(10)
            ->get();

        return view('datos.index', compact(
            'productosCriticos',
            'totalProductos',
            'productosSinStock',
            'productosStockExcesivo',
            'productosNecesitanReabastecimiento',
            'productosSinMovimiento',
            'productosPorClasificacion',
            'ultimosMovimientos',
            'totalCertificados',
            'stockCertificados',
            'certificadosUsados',
            'certificadosAgregados',
            'ultimosCertificadosUsados'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dato $dato)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dato $dato)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dato $dato)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dato $dato)
    {
        //
    }
}
