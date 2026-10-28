<?php

namespace App\Http\Controllers;

use App\Models\PaqueteHotel;
use App\Models\Paquetes_Turisticos;
use App\Models\Hotel;
use Illuminate\Http\Request;

class PaqueteHotelesController extends Controller
{
    private array $mensajes = [
        'id_paquete.required' => 'Debes seleccionar un paquete turístico.',
        'id_paquete.exists'   => 'El paquete turístico seleccionado no existe.',
        'id_hotel.required'   => 'Debes seleccionar un hotel.',
        'id_hotel.exists'     => 'El hotel seleccionado no existe.',
    ];

    private function reglas(): array
    {
        return [
            'id_paquete' => ['required', 'exists:paquetes_turisticos,id_paquete'],
            'id_hotel'   => ['required', 'exists:hoteles,id'],
        ];
    }

    private function revisarDuplicado(int $idPaquete, int $idHotel): ?string
    {
        $existe = PaqueteHotel::where('id_paquete', $idPaquete)
            ->where('id_hotel', $idHotel)
            ->exists();

        if ($existe) {
            return 'Este hotel ya se encuentra asignado a dicho paquete turístico.';
        }

        return null;
    }

    public function getPaqueteHotel()
    {
        $paqueteHoteles = PaqueteHotel::all();
        $paquetes = Paquetes_Turisticos::all();
        $hoteles = Hotel::all();

        return view('paquetes_hoteles', compact('paqueteHoteles', 'paquetes', 'hoteles'));
    }

    public function createPaqueteHotel(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes);

        $error = $this->revisarDuplicado((int) $datos['id_paquete'], (int) $datos['id_hotel']);
        if ($error) {
            return back()->with('error', $error)->withInput();
        }

        PaqueteHotel::create($datos);

        return back()->with('success', '¡Hotel asignado al paquete correctamente!');
    }

    public function deletePaqueteHotel(int $id_paquete, int $id_hotel)
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