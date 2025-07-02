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
    public function create()
    {
        $movimientos = Movimiento::with(['producto', 'solicitante'])->latest()->get();
        $clasificaciones = Clasificacion::all();
        $productos = Producto::with('clasificacion')->get();
        $solicitantes = Solicitante::all();
        return view('movimientos.create', compact('clasificaciones', 'productos', 'solicitantes', 'movimientos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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


        //Actualizar el stock_actual
        $producto = Producto::find($request->producto_id);

        if ($request->tipo_movimiento == 'Entrada') {
            $producto->stock_actual += $request->cantidad;
        } else {
            $producto->stock_actual -= $request->cantidad;
        }
        $producto->save();

        $alerta_stock = null;
        if ($producto->stock_actual <= $producto->stock_minimo) {
            $alerta_stock = "¡Atencion! El producto '{$producto->nombre}' ha llegado al stock minimo. Es necesario hacer un pedido.";
        }
        return redirect()->route('movimiento.create')->with([
            'success' => 'Movieminto registrado y stock actualizado.',
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
