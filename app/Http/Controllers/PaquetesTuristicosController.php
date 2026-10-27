<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paquetes_Turisticos;
use Illuminate\Support\Facades\DB;

class PaquetesTuristicosController extends Controller
{
    public function getPaquetesTuristicos() 
    {
        $paquetesturisticos = Paquetes_Turisticos::all();
        return view('paquetesturisticos', compact('paquetesturisticos'));
    }

    public function createPaquetesTuristicos(Request $request) 
    {
        $request->validate([
            'nombre_paquete' => 'required|string|max:100',
            'descripcion'    => 'required|string|max:500',
            'duracion_dias'  => 'required|integer|min:1',
            'precio_total'   => 'required|numeric|min:0.01',
        ]);

        Paquetes_Turisticos::create([
            'nombre_paquete' => $request->nombre_paquete,
            'descripcion'    => $request->descripcion,
            'duracion_dias'  => $request->duracion_dias,
            'precio_total'   => $request->precio_total,
        ]);

        return back()->with('success', '¡Paquete turístico creado exitosamente!');
    }
    
    public function updatePaquetesTuristicos(Request $request, $id) 
    {
        $request->validate([
            'nombre_paquete' => 'required|string|max:100',
            'descripcion'    => 'required|string|max:500',
            'duracion_dias'  => 'required|integer|min:1',
            'precio_total'   => 'required|numeric|min:0.01',
        ]);

        $paquetesturisticos = Paquetes_Turisticos::findOrFail($id);
        
        $paquetesturisticos->update([
            'nombre_paquete' => $request->nombre_paquete,
            'descripcion'    => $request->descripcion,
            'duracion_dias'  => $request->duracion_dias,
            'precio_total'   => $request->precio_total,
        ]);

        return back()->with('success', '¡Paquete turístico actualizado correctamente!');
    }
    
    public function deletePaquetesTuristicos($id) 
    {
        $tieneHoteles = DB::table('paquete_hoteles')->where('id_paquete', $id)->exists();
        $tieneDestinos = DB::table('paquete_destinos')->where('id_paquete', $id)->exists();

        if ($tieneHoteles || $tieneDestinos) {
            return back()->with('error', 'No se puede eliminar el paquete porque tiene hoteles o destinos asociados.');
        }

        $paquetesturisticos = Paquetes_Turisticos::findOrFail($id);
        $paquetesturisticos->delete();

        return back()->with('success', '¡Paquete turístico eliminado correctamente!');
    }
}