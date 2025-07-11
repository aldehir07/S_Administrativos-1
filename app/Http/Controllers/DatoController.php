<?php

namespace App\Http\Controllers;

use App\Models\Dato;
use App\Models\Producto;
use Illuminate\Http\Request;

class DatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productosCriticos = Producto::with('clasificacion')
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->get();

        return view('datos.index', compact('productosCriticos'));
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
