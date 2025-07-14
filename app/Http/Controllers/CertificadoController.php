<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use Illuminate\Http\Request;

class CertificadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('certificados.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener el stock total actual
        $stockTotal = Certificado::sum('stock_actual');

        // Obtener los movimientos de salida (certificados usados)
        // Por ahora, consideramos como "usados" los que tienen stock_actual = 0
        $certificadosUsados = Certificado::where('stock_actual', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('certificados.create', compact('certificadosUsados', 'stockTotal'));
    }

    /**
     * Store a newly created resource in storage (movimiento de salida).
     */
    public function store(Request $request)
    {
        $request->validate([
            'evento' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:1',
            'responsable' => 'required|string|max:255',
            'fecha' => 'required|date',
        ]);

        // Verificar si hay stock suficiente
        $stockDisponible = Certificado::sum('stock_actual');

        if ($stockDisponible < $request->cantidad) {
            return redirect()->route('certificados.create')
                ->with('error', 'Stock insuficiente. Solo hay ' . $stockDisponible . ' certificados disponibles.');
        }

        // Crear registro de certificados usados (movimiento de salida)
        $certificado = Certificado::create([
            'evento' => $request->evento,
            'cantidad' => $request->cantidad,
            'responsable' => $request->responsable,
            'fecha' => $request->fecha,
            'stock_actual' => 0, // Los certificados usados no tienen stock
        ]);

        // Descontar del stock general
        $this->descontarDelStockGeneral($request->cantidad);

        return redirect()->route('certificados.create')
            ->with('success', 'Se registraron ' . $request->cantidad . ' certificados usados en el evento.');
    }

    /**
     * Agregar certificados al stock (movimiento de entrada).
     */
    public function agregarStock(Request $request)
    {
        $request->validate([
            'cantidad_agregar' => 'required|integer|min:1',
            'responsable_agregar' => 'required|string|max:255',
            'fecha_agregar' => 'required|date',
        ]);

        // Crear registro de entrada al stock
        $certificado = Certificado::create([
            'evento' => 'Agregado al inventario',
            'cantidad' => $request->cantidad_agregar,
            'responsable' => $request->responsable_agregar,
            'fecha' => $request->fecha_agregar,
            'stock_actual' => $request->cantidad_agregar,
        ]);

        return redirect()->route('certificados.create')
            ->with('success', 'Se agregaron ' . $request->cantidad_agregar . ' certificados al inventario.');
    }

    /**
     * Descontar del stock general
     */
    private function descontarDelStockGeneral($cantidad)
    {
        // Obtener todos los certificados con stock disponible
        $certificadosConStock = Certificado::where('stock_actual', '>', 0)
            ->orderBy('created_at', 'asc') // FIFO - primero en entrar, primero en salir
            ->get();

        $cantidadRestante = $cantidad;

        foreach ($certificadosConStock as $certificado) {
            if ($cantidadRestante <= 0) break;

            $cantidadADescontar = min($certificado->stock_actual, $cantidadRestante);
            $certificado->stock_actual -= $cantidadADescontar;
            $certificado->save();

            $cantidadRestante -= $cantidadADescontar;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificado $certificado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificado $certificado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificado $certificado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificado $certificado)
    {
        //
    }
}
