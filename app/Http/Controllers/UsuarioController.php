<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuario.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Usuario::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return redirect(route('login'))->with('mensaje', 'Se ha registrado exitosamente!.');
    }

    public function loginfrm(){
        return view('login');
    }

    public function login(Request $request){
        if(Auth::attempt(['name' => $request->name, 'password' => $request->password])){
            session(['role' => Auth::user()->role]);
            return redirect(route('datos.index'));
        }
        return back()->with('mensaje', 'Error al iniciar session, verifique sus credenciales.');
    }

    public function logout(){
        Auth::logout();
        return redirect(route('login'))->with('mensaje', 'Sesion Cerrada.');
    }

    /**
     * 
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        //
    }
}
