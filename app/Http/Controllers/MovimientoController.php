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
            // Validación para Salida
            $request->validate([
                'productos_salida' => 'required|array|min:1',
                'cantidades_salida' => 'required|array|min:1',
                'productos_salida.*' => 'required|exists:productos,id',
                'cantidades_salida.*' => 'required|integer|min:1',
            ]);

            foreach ($request->productos_salida as $index => $producto_id) {
                $cantidad = $request->cantidades_salida[$index];

                // Crea un movimiento por cada producto/cantidad
                Movimiento::create([
                    'tipo_movimiento' => 'Salida',
                    'producto_id' => $producto_id,
                    'cantidad' => $cantidad,
                    'fecha' => $request->fecha,
                    'clasificacion_id' => $request->clasificacion_id,
                    'evento' => $request->evento,
                    'solicitante_id' => $request->solicitante_id,
                    'responsable' => $request->responsable,
                    'observaciones' => $request->observaciones,
                ]);

                // Actualiza el stock del producto
                $producto = Producto::find($producto_id);
                $producto->stock_actual -= $cantidad;
                $producto->save();

                // Notificación si llega al mínimo
                if ($producto->stock_actual <= $producto->stock_minimo) {
                    $alerta_stock[] = "¡Atención! El producto '{$producto->nombre}' ha llegado al stock mínimo. Es necesario hacer un pedido.";
                }
            }
            return redirect()->route('movimiento.create')->with([
                'success' => 'Movimientos de salida registrados y stock actualizado.',
                'alerta_stock' => isset($alerta_stock) ? implode(' | ', $alerta_stock) : null
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
