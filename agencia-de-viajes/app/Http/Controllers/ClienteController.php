<?php

namespace App\Http\Controllers;
use App\Models\Cliente;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function getCliente() {
        $clientes = Cliente::all();
        return view ('clientes', compact('clientes'));
    }

    public function createCliente(Request $request) {
        $request -> validate ([
            'nombre'=> 'required|string',
            'apellido'=> 'required|string',
            'correo'=> 'required|email|unique:clientes,correo',
            'telefono'=> 'nullable|string'

        ]);

        Cliente::create([
            
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'telefono' => $request->telefono,


        ]);

        return back();
    }

    public function updateCliente(Request $request, Int $id)
    {
        $request -> validate ([
            'nombre'=> 'required|string',
            'apellido'=> 'required|string',
            'correo'=> 'required|email|unique:clientes,correo',
            'telefono'=> 'nullable|string'
        ]);

        $request -> update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'telefono' => $request->telefono
        ]);
        

    }

    public function deleteCliente(Request $request, Int $id) {
        $clientes = Cliente::findOrFail($id);
        $clientes->delete();

        return back();
    }
}
