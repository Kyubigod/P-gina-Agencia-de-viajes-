<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paquete_Destinos;
use App\Models\Paquetes_Turisticos;
use App\Models\Ciudad;

class PaqueteDestinoController extends Controller
{
    public function getPaqueteDestinos()
    {
        $paquetesDestinos = Paquete_Destinos::all();
        $paquetes = Paquetes_Turisticos::all();$ciudades = Ciudad::all();

        return view('paquetedestinos', compact('paquetesDestinos', 'paquetes', 'ciudades'));
    }

    public function createPaqueteDestino(Request $request)
    {
        $request->validate([
            'id_paquete' => 'required|exists:paquetes_turisticos,id_paquete',
            'id_ciudad'  => 'required|exists:ciudades,id', 
        ]);

        $existe = Paquete_Destinos::where('id_paquete',$request->id_paquete)
            ->where('id_ciudad', $request->id_ciudad)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Esta ciudad ya se encuentra asignada al paquete turístico seleccionado.');
        }

        Paquete_Destinos::create([
            'id_paquete' => $request->id_paquete,
            'id_ciudad'  => $request->id_ciudad,
        ]);

        return back()->with('success', '¡Destino asignado al paquete correctamente!');
    }

    public function deletePaqueteDestino($id_paquete,$id_ciudad)
    {
        $relacion = Paquete_Destinos::where('id_paquete',$id_paquete)
            ->where('id_ciudad', $id_ciudad);

        if (!$relacion->exists()) {
            return back()->with('error', 'La asociación que intentas eliminar no existe.');
        }

        $relacion->delete();

        return back()->with('success', '¡Destino desasociado del paquete correctamente!');
    }
}