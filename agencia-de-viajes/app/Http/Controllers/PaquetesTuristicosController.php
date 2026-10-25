<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paquetes_Turisticos;

class PaquetesTuristicosController extends Controller
{
    public function getPaquetesTuristicos() {
        $paquetesturisticos = Paquetes_Turisticos::all();
        return view('paquetesturisticos', compact('paquetesturisticos'));
    }

    public function createPaquetesTuristicos(Request $request) {

    $request -> validate ([
        
    'nombre_paquete' => 'required|string',
    'descripcion' => 'required|string',
    'duracion_dias' => 'required|integer',
    'precio_total' => 'required|numeric',
    ]);

    Paquetes_Turisticos::create([
        'nombre_paquete'=>$request->nombre_paquete,
        'descripcion'=>$request->descripcion,
        'duracion_dias'=>$request->duracion_dias,
        'precio_total'=>$request->precio_total,
    ]);

    return back();
    }
    
    public function updatePaquetesTuristicos(Request $request, $id) {
        $request -> validate ([
        
    'nombre_paquete' => 'required|string',
    'descripcion' => 'required|string',
    'duracion_dias' => 'required|integer',
    'precio_total' => 'required|numeric',
    ]);

    $paquetesturisticos = Paquetes_Turisticos::findOrFail($id);
    
    $paquetesturisticos->update([
        'nombre_paquete'=>$request->nombre_paquete,
        'descripcion'=>$request->descripcion,
        'duracion_dias'=>$request->duracion_dias,
        'precio_total'=>$request->precio_total,
    ]);
    return back();
    }
    
    public function deletePaquetesTuristicos($id) {
        $paquetesturisticos = Paquetes_Turisticos::findOrFail($id);
        $paquetesturisticos->delete();

        return back();
    }

}