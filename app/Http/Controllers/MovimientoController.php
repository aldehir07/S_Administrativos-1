<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Solicitante;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('movimientos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
        $movimientos = Movimiento::with(['producto', 'solicitante'])->latest()->get();
        // dd($movimientos);
        $clasificaciones = Clasificacion::all();
        $productos = Producto::with('clasificacion')->get();
        $solicitantes = Solicitante::all();
        $producto_id = $request->get('producto_id');
        return view('movimientos.create', compact('clasificaciones', 'productos', 'solicitantes', 'movimientos', 'producto_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        if ($request->tipo_movimiento == "Salida") {
            // Validación para Salida múltiple
            $request->validate([
                'productos_salida' => 'required|array|min:1',
                'cantidades_salida' => 'required|array|min:1',
                'productos_salida.*' => 'required|exists:productos,id',
                'cantidades_salida.*' => 'required|integer|min:1',
            ]);

            $alerta_stock = [];

            foreach ($request->productos_salida as $index => $producto_id) {
                $cantidad_salida = $request->cantidades_salida[$index];
                $cantidad_restante = $cantidad_salida;

                // FIFO: busca lotes disponibles ordenados por fecha de vencimiento
                $movimientos = Movimiento::where('producto_id', $producto_id)
                    ->where('tipo_movimiento', 'Entrada')
                    ->where('fecha_vencimiento', '>=', now())
                    ->orderBy('fecha_vencimiento')
                    ->get();

                foreach ($movimientos as $mov) {
                    // Stock disponible en este lote
                    $stock_lote = Movimiento::where('producto_id', $producto_id)
                        ->where('lote', $mov->lote)
                        ->where('fecha_vencimiento', $mov->fecha_vencimiento)
                        ->selectRaw('SUM(CASE WHEN tipo_movimiento="Entrada" THEN cantidad ELSE -cantidad END) as stock_lote')
                        ->value('stock_lote');

                    if ($stock_lote <= 0) continue;

                    $descontar = min($cantidad_restante, $stock_lote);

                    // Registrar movimiento de salida para este lote
                    Movimiento::create([
                        'tipo_movimiento' => 'Salida',
                        'producto_id' => $producto_id,
                        'cantidad' => $descontar,
                        'fecha' => $request->fecha,
                        'clasificacion_id' => $request->clasificacion_id,
                        'evento' => $request->evento,
                        'lote' => $mov->lote,
                        'fecha_vencimiento' => $mov->fecha_vencimiento,
                        'solicitante_id' => $request->solicitante_id,
                        'responsable' => $request->responsable,
                        'motivo' => $request->motivo,
                        'observaciones' => $request->observaciones,
                    ]);

                    $cantidad_restante -= $descontar;
                    if ($cantidad_restante <= 0) break;
                }

                // Actualiza el stock general del producto
                $producto = Producto::find($producto_id);
                $producto->stock_actual -= $cantidad_salida;
                $producto->save();

                // Notificación si llega al mínimo
                if ($producto->stock_actual <= $producto->stock_minimo) {
                    $alerta_stock[] = "¡Atención! El producto '{$producto->nombre}' ha llegado al stock mínimo. Es necesario hacer un pedido.";
                }
            }

            return redirect()->route('movimiento.create')->with([
                'success' => 'Movimientos de salida registrados y stock actualizado.',
                'alerta_stock' => count($alerta_stock) ? implode(' | ', $alerta_stock) : null
            ]);
        }

        // Para Entrada, Descarte, Certificados (uno solo)
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        Movimiento::create([
            'tipo_movimiento' => $request->tipo_movimiento,
            'producto_id' => $request->producto_id,
            'cantidad' => $request->cantidad,
            'fecha' => $request->fecha,
            'clasificacion_id' => $request->clasificacion_id,
            'evento' => $request->evento,
            'lote' => $request->lote,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'solicitante_id' => $request->solicitante_id,
            'responsable' => $request->responsable,
            'motivo' => $request->motivo,
            'observaciones' => $request->observaciones
        ]);

        // Actualiza el stock
        $producto = Producto::find($request->producto_id);
        if ($request->tipo_movimiento == 'Entrada') {
            $producto->stock_actual += $request->cantidad;
        } else {
            $producto->stock_actual -= $request->cantidad;
        }
        $producto->save();

        $alerta_stock = null;
        if ($producto->stock_actual <= $producto->stock_minimo) {
            $alerta_stock = "¡Atención! El producto '{$producto->nombre}' ha llegado al stock mínimo. Es necesario hacer un pedido.";
        }

        return redirect()->route('movimiento.create')->with([
            'success' => 'Movimiento registrado y stock actualizado.',
            'alerta_stock' => $alerta_stock
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movimiento $movimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movimiento $movimiento)
    {
        $movimientos = Movimiento::with(['producto', 'solicitante'])->latest()->get();
        $clasificaciones = Clasificacion::all();
        $productos = Producto::all();
        $solicitantes = Solicitante::all();
        return view('movimientos.edit', compact('movimiento', 'movimientos', 'clasificaciones', 'productos', 'solicitantes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movimiento $movimiento)
    {
        $movimiento->update($request->all());
        return redirect()->route('movimiento.create')->with('success', 'Movimiento actualizado correctamente!.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movimiento $movimiento)
    {
        $movimiento->delete();
        return redirect()->route('movimiento.create')->with('success', 'Movimiento eliminado del incentario exitosamente!.');
    }

}
