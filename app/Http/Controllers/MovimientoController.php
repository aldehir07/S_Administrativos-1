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

        $movimientos = Movimiento::with(['producto', 'solicitante', 'clasificacion'])->latest()->get();
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
            $request->validate([
                'productos_salida' => 'required|array|min:1',
                'cantidades_salida' => 'required|array|min:1',
                'productos_salida.*' => 'required|exists:productos,id',
                'cantidades_salida.*' => 'required|integer|min:1',
            ]);

            $alerta_stock = [];
            $errores_stock = [];

            foreach ($request->productos_salida as $index => $producto_id) {
                $cantidad_salida = $request->cantidades_salida[$index];
                $cantidad_restante = $cantidad_salida;

                $producto = Producto::find($producto_id);

                // Validar stock global antes de procesar lotes
                if ($producto->stock_actual < $cantidad_salida) {
                    $errores_stock[] = "No hay suficiente stock para el producto '{$producto->nombre}'. No se puede realizar la salida solicitada.";
                    continue;
                }

                // Busca lotes disponibles ordenados por fecha de vencimiento
                $movimientos = Movimiento::where('producto_id', $producto_id)
                    ->where('tipo_movimiento', 'Entrada')
                    ->whereNotNull('lote')
                    ->whereNotNull('fecha_vencimiento')
                    ->where('fecha_vencimiento', '>=', now())
                    ->orderBy('fecha_vencimiento')
                    ->get();

                // Si no hay lotes disponibles, notifica error y pasa al siguiente producto
                if ($movimientos->isEmpty()) {
                    $errores_stock[] = "El producto ID $producto_id->nombre no tiene lotes disponibles. Registre primero una entrada con lote.";
                    continue;
                }

                $salida_realizada = false;

                foreach ($movimientos as $mov) {
                    // Stock disponible en este lote
                    $stock_lote = Movimiento::where('producto_id', $producto_id)
                        ->where('lote', $mov->lote)
                        ->where('fecha_vencimiento', $mov->fecha_vencimiento)
                        ->selectRaw("SUM(CASE WHEN tipo_movimiento='Entrada' THEN cantidad ELSE -cantidad END) as stock_lote")
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
                    $salida_realizada = true;
                    if ($cantidad_restante <= 0) break;
                }

                // Si queda cantidad sin cubrir, notifica error
                if ($cantidad_restante > 0) {
                    $errores_stock[] = "No hay suficiente stock para el producto ID $producto_id. Faltan $cantidad_restante unidades.";
                }



                // Solo actualiza el stock si se realizó al menos una salida
                if ($salida_realizada) {
                    $producto = Producto::find($producto_id);
                    if($producto->stock_actual - ($cantidad_salida - $cantidad_restante) < 0) {
                        $errores_stock[] = "No hay suficiente stock para el producto '{$producto->nombre}'. No se puede realizar la salida solicitada. Stock actual: {$producto->stock_actual}";
                        continue;
                    }
                    $producto->stock_actual -= ($cantidad_salida - $cantidad_restante);
                    $producto->save();

                    if ($producto->stock_actual <= $producto->stock_minimo) {
                        $alerta_stock[] = "¡Atención! El producto '{$producto->nombre}' ha llegado al stock mínimo. Es necesario hacer un pedido.";
                    }
                }
            }

            return redirect()->route('movimiento.create')->with([
                'success' => count($errores_stock) == 0 ? 'Movimientos de salida registrados y stock actualizado.' : null,
                'alerta_stock' => count($alerta_stock) ? implode(' | ', $alerta_stock) : null,
                'errores_stock' => count($errores_stock) ? implode(' | ', $errores_stock) : null,
            ]);
        }

        // ---- AQUI VA EL BLOQUE DE DESCARTE ----
        if ($request->tipo_movimiento == 'Descarte') {
            $producto = Producto::find($request->producto_id);

            // Validar stock suficiente
            if ($producto->stock_actual < $request->cantidad) {
                return redirect()->route('movimiento.create')->with([
                    'errores_stock' => "No hay suficiente stock para descartar el producto '{$producto->nombre}'."
                ]);
            }

            Movimiento::create([
                'tipo_movimiento' => 'Descarte',
                'producto_id' => $request->producto_id,
                'cantidad' => $request->cantidad,
                'fecha' => $request->fecha,
                'clasificacion_id' => $request->clasificacion_id,
                'lote' => $request->lote,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'motivo' => $request->motivo,
                'observaciones' => $request->observaciones
            ]);

            $producto->stock_actual -= $request->cantidad;
            $producto->save();

            $alerta_stock = null;
            if ($producto->stock_actual <= $producto->stock_minimo) {
                $alerta_stock = "¡Atención! El producto '{$producto->nombre}' ha llegado al stock mínimo. Es necesario hacer un pedido.";
            }

            return redirect()->route('movimiento.create')->with([
                'success' => 'Descarte registrado y stock actualizado.',
                'alerta_stock' => $alerta_stock
            ]);
        }
        // ---- FIN BLOQUE DESCARTE ----

        if ($request->tipo_movimiento == 'Certificado') {
            $producto = Producto::find($request->producto_id);

            // Validar stock suficiente
            if ($producto->stock_actual < $request->cantidad) {
                return redirect()->route('movimiento.create')->with([
                    'errores_stock' => "No hay suficiente stock para entregar el certificado de '{$producto->nombre}'."
                ]);
            }

            Movimiento::create([
                'tipo_movimiento' => 'Certificado',
                'producto_id' => $request->producto_id,
                'cantidad' => $request->cantidad,
                'fecha' => $request->fecha,
                'responsable' => $request->responsable,
                'evento' => $request->evento,
                'observaciones' => $request->observaciones
            ]);

            $producto->stock_actual -= $request->cantidad;
            $producto->save();

            $alerta_stock = null;
            if ($producto->stock_actual <= $producto->stock_minimo) {
                $alerta_stock = "¡Atención! El producto '{$producto->nombre}' ha llegado al stock mínimo. Es necesario hacer un pedido.";
            }

            return redirect()->route('movimiento.create')->with([
                'success' => 'Certificado registrado y stock actualizado.',
                'alerta_stock' => $alerta_stock
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
