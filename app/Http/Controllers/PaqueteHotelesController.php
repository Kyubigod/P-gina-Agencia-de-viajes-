<?php

namespace App\Http\Controllers;

use App\Models\PaqueteHotel;
use App\Models\Paquetes_Turisticos;
use App\Models\Hotel;
use Illuminate\Http\Request;

class PaqueteHotelesController extends Controller
{
    public function getPaqueteHotel()
    {
        $paqueteHoteles = PaqueteHotel::all();
        $paquetes = Paquetes_Turisticos::all();
        $hoteles = Hotel::all();

        return view('paquetes_hoteles', compact('paqueteHoteles', 'paquetes', 'hoteles'));
    }

    public function createPaqueteHotel(Request $request)
    {
        $request->validate([
            'id_paquete' => 'required|exists:paquetes_turisticos,id_paquete',
            'id_hotel'   => 'required|exists:hoteles,id',
        ]);

        $existeRelacion = PaqueteHotel::where('id_paquete', $request->id_paquete)
            ->where('id_hotel', $request->id_hotel)
            ->exists();

        if ($existeRelacion) {
            return back()->with('error', 'Este hotel ya se encuentra asignado a dicho paquete turístico.');
        }

        PaqueteHotel::create([
            'id_paquete' => $request->id_paquete,
            'id_hotel'   => $request->id_hotel,
        ]);

        return back()->with('success', '¡Hotel asignado al paquete correctamente!');
    }

    public function deletePaqueteHotel($id_paquete, $id_hotel)
    {
        $relacion = PaqueteHotel::where('id_paquete', $id_paquete)
            ->where('id_hotel', $id_hotel);

        if (!$relacion->exists()) {
            return back()->with('error', 'La asociación que intentas eliminar no existe.');
        }

        $relacion->delete();

        return back()->with('success', '¡Asociación de paquete y hotel eliminada correctamente!');
    }
}