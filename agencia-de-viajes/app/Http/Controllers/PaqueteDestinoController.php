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
        $paquetes = Paquetes_Turisticos::all();
        $ciudades = Ciudad::all();

        return view('paquetedestinos', compact('paquetesDestinos', 'paquetes', 'ciudades'));
    }

    public function createPaqueteDestino(Request $request)
    {
        $request->validate([
            'id_paquete' => 'required|integer',
            'id_ciudad' => 'required|integer',
        ]);

        Paquete_Destinos::create([
            'id_paquete' => $request->id_paquete,
            'id_ciudad' => $request->id_ciudad,
        ]);

        return back();
    }

    public function deletePaqueteDestino($id_paquete, $id_ciudad)
    {
        Paquete_Destinos::where('id_paquete', $id_paquete)
            ->where('id_ciudad', $id_ciudad)
            ->delete();

        return back();
    }
}