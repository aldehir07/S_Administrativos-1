<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UsuarioController extends Controller
{

    public function index(){

    }
    
    public function create()
    {
        return view('usuario.create');
    }


    public function store(Request $request)
    {

        Usuario::create([
            'nombre' => $request->nombre,
            'password' => bcrypt($request->password),
        ]);
        return redirect()->route('login')->with('success', 'Usuario creado exitosamente');
    }

    
    public function loginfrm(){
        return view('login');
    }


    public function login(Request $request){
        $credentials = $request->only('nombre', 'password');
        
        if(Auth::attempt($credentials)){
            session(['role' => Auth::user()->role]);
            return redirect()->route('datos.index')->with('success', 'Inicio de sesión exitoso');
        }
        return back()->with('error', 'Error al iniciar sesion, verifique sus credenciales.');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('success', 'Sesion cerrada exitosamente');
    }

    public function show(){

    }

    public function edit(){
        
    }
    public function update(){
        
    }

    public function destroy(){
        
    }
}
