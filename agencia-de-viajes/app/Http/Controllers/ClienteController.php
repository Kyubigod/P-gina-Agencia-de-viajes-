<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function getCliente() {
        $clientes = Cliente::all();
        return view ('Clientes.index', compact('clientes'));
    }

    public function createCliente() {
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

    public function updateCliente()
    {
        $clientes = Cliente::all();

    }
}
