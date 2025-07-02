<?php

namespace App\Http\Controllers;

use App\Imports\ProductosImport;
use App\Models\Clasificacion;
use App\Models\Producto;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::all();

        return view('productos.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'clasificacion_id' => 'required',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock_minimo' => 'nullable|integer'
            
        ]);

        Producto::create([
            'nombre' => $request->nombre,
            'clasificacion_id' => $request->clasificacion_id,
            'imagen' => $request->file('imagen') ? $request->file('imagen')->store('productos', 'public') : null,
            'stock_minimo' => $request->stock_minimo,
            'stock_actual' => $request->stock_actual
        ]);

        // return $request;
        return redirect()->route('producto.create')->with('sucess', 'Producto registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $clasificaciones = Clasificacion::all();
        $productos = Producto::all();
        return view('productos.edit', compact('producto' ,'productos', 'clasificaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'clasificacion_id' => 'required',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock_minimo' => 'nullable|integer'
        ]);

        // SI hay imagen nueva, guardala
        if($request->hasFile('imagen')){
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->nombre = $request->nombre;
        $producto->clasificacion_id = $request->clasificacion_id;
        $producto->stock_minimo = $request->stock_minimo;
        $producto->stock_actual = $request->stock_actual;
        $producto->save();

        return redirect()->route('producto.edit', $producto)->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('producto.create')->with('success', 'Producto eliminado exitosamente.');
    }


    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt'
        ]);

        Excel::import(new ProductosImport, $request->file('archivo'));

        return redirect()->route('producto.create')->with('success', 'Productos importados correctamente.');
    }

    public function porClasificacion($clasificacion_id){
        $productos = Producto::where('clasificacion_id', $clasificacion_id)->get();
        return response()->json($productos);
    }
}
