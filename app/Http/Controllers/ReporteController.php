<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;



class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Movimiento::query()->with('producto');

        if($request->filled('desde')){
            $query->where('fecha', '>=', $request->desde);
        }
        if($request->filled('hasta')){
            $query->where('fecha', '<=', $request->hasta);
        }
        if($request->filled('clasificacion')){
            $query->whereHas('producto', function($q) use ($request) {
                $q->where('clasificacion_id', $request->clasificacion);
            });
        }
        if($request->filled('producto_id')){
            $query->where('producto_id', $request->producto_id);
        }
        if($request->filled('tipo_movimiento')){
            $query->where('tipo_movimiento', $request->tipo_movimiento);
        }

        $movimientos = $query->orderBy('fecha', 'desc')->get();
        $productos = Producto::orderBy('nombre')->get();

        // Obtener clasificaciones únicas desde productos
        $clasificaciones = Producto::select('clasificacion_id')->distinct()->orderBy('clasificacion_id')->get();

        // Filtros de fecha
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

        return view('reportes.index', compact('movimientos', 'productos', 'productosMasUsados', 'clasificaciones'));
    }

    //Funcion para export a PDF
    public function exportarPDF(Request $request){

        $query = Movimiento::query()->with('producto');

        if($request->filled('desde')){
            $query->where('fecha', '>=', $request->desde);
        }
        if($request->filled('hasta')){
            $query->where('fecha', '<=', $request->hasta);
        }
        if($request->filled('clasificacion')){
            $query->whereHas('producto', function($q) use ($request) {
                $q->where('clasificacion', $request->clasificacion);
            });
        }
        if($request->filled('producto_id')){
            $query->where('producto_id', $request->producto_id);
        }
        if($request->filled('tipo_movimiento')){
            $query->where('tipo_movimiento', $request->tipo_movimiento);
        }

        $movimientos = $query->orderBy('fecha', 'desc')->get();

        //Calcular totales
        $totalEntradas = $movimientos->where('tipo_movimiento', 'Entrada')->sum('cantidad');
        $totalSalidas = $movimientos->where('tipo_movimiento', 'Salida')->sum('cantidad');
        $totalDescartes = $movimientos->where('tipo_movimiento', 'Descarte')->sum('cantidad');
        $totalCertificados = $movimientos->where('tipo_movimiento', 'Certificados')->sum('cantidad');

        $data = [
            'movimientos' => $movimientos,
            'desde' => $request->input('desde'),
            'hasta' => $request->input('hasta'),
            'totalEntradas' => $totalEntradas,
            'totalSalidas' => $totalSalidas,
            'totalDescartes' => $totalDescartes,
            'totalCertificados' => $totalCertificados,
            'fechaReporte' => now()->format('d/m/Y H:i:s')
        ];

    $pdf = Pdf::loadView('reportes.pdf', $data);

    return $pdf->download('reporte-movimientos-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportarPDFSeleccionados(Request $request)
    {
        $ids = explode(',', $request->movimientos_ids);
        $movimientos = Movimiento::whereIn('id', $ids)->with('producto', 'responsable')->get();

        // Calcular totales si lo necesitas
        $totalEntradas = $movimientos->where('tipo_movimiento', 'Entrada')->sum('cantidad');
        $totalSalidas = $movimientos->where('tipo_movimiento', 'Salida')->sum('cantidad');
        $totalDescartes = $movimientos->where('tipo_movimiento', 'Descarte')->sum('cantidad');
        $totalCertificados = $movimientos->where('tipo_movimiento', 'Certificado')->sum('cantidad');

        $data = [
            'movimientos' => $movimientos,
            'totalEntradas' => $totalEntradas,
            'totalSalidas' => $totalSalidas,
            'totalDescartes' => $totalDescartes,
            'totalCertificados' => $totalCertificados,
            'fechaReporte' => now()->format('d/m/Y H:i:s'),
            'desde' => null,
            'hasta' => null,
        ];

        $pdf = Pdf::loadView('reportes.pdf', $data);
        return $pdf->download('reporte-movimientos-seleccionados-' . now()->format('Y-m-d') . '.pdf');
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
    public function show(Reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reporte $reporte)
    {
        //
    }
}
