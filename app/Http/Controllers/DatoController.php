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
    public function index(Request $request)
    {
        // Productos en stock crítico
        $productosCriticos = Producto::with('clasificacion')
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->get();

        // Productos vencidos y próximos a vencer
        $productosVencidos = Movimiento::with(['producto.clasificacion'])
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', Carbon::now())
            ->where('tipo_movimiento', 'Entrada')
            ->get()
            ->unique('producto_id')
            ->map(function($movimiento) {
                return $movimiento->producto;
            })
            ->filter();

        $productosProximosVencer = Movimiento::with(['producto.clasificacion'])
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '>=', Carbon::now())
            ->where('fecha_vencimiento', '<=', Carbon::now()->addDays(30))
            ->where('tipo_movimiento', 'Entrada')
            ->get()
            ->unique('producto_id')
            ->map(function($movimiento) {
                return $movimiento->producto;
            })
            ->filter();

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

        //Filtros de fecha
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        
        // Consulta para productos más utilizados
        $productosMasUsados = Movimiento::selectRaw('producto_id, SUM(cantidad) as total_usada')
            ->when($desde, function($query) use ($desde) {
                $query->where('fecha', '>=', $desde);
            })
            ->when($hasta, function($query) use ($hasta) {
                $query->where('fecha', '<=', $hasta);
            })
            ->where('tipo_movimiento', 'Salida') // Solo salidas, puedes ajustar si quieres incluir otros tipos
            ->groupBy('producto_id')
            ->orderByDesc('total_usada')
            ->with('producto')
            ->take(10) // Top 10
            ->get();
        
        //Mostrar el stock por lote y fecha de vencimeinto
        $inventario = Movimiento::select('producto_id', 'lote', 'fecha_vencimiento')
            ->selectRaw('SUM(CASE WHEN tipo_movimiento="Entrada" THEN cantidad ELSE -cantidad END) as stock_lote')
            ->whereNotNull('fecha_vencimiento')
            ->groupBy('producto_id', 'lote', 'fecha_vencimiento')
            ->having('stock_lote', '>', 0)
            ->with('producto')
            ->get();

        return view('datos.index', compact(
            'productosCriticos',
            'productosVencidos',
            'productosProximosVencer',
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
            'ultimosCertificadosUsados',
            'productosMasUsados',
            'inventario'
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
