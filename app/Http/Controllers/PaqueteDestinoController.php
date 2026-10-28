<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paquete_Destinos;
use App\Models\Paquetes_Turisticos;
use App\Models\Ciudad;

class PaqueteDestinoController extends Controller
{
    private array $mensajes = [
        'id_paquete.required' => 'Debes seleccionar un paquete turístico.',
        'id_paquete.exists'   => 'El paquete turístico seleccionado no existe.',
        'id_ciudad.required'  => 'Debes seleccionar una ciudad.',
        'id_ciudad.exists'    => 'La ciudad seleccionada no existe.',
    ];

    private function reglas(): array
    {
        return [
            'id_paquete' => ['required', 'exists:paquetes_turisticos,id_paquete'],
            'id_ciudad'  => ['required', 'exists:ciudades,id'],
        ];
    }

    private function revisarDuplicado(int $idPaquete, int $idCiudad): ?string
    {
        $existe = Paquete_Destinos::where('id_paquete', $idPaquete)
            ->where('id_ciudad', $idCiudad)
            ->exists();

        if ($existe) {
            return 'Esta ciudad ya se encuentra asignada al paquete turístico seleccionado.';
        }

        return null;
    }

    public function getPaqueteDestinos()
    {
        $paquetesDestinos = Paquete_Destinos::all();
        $paquetes = Paquetes_Turisticos::all();
        $ciudades = Ciudad::all();

        return view('paquetedestinos', compact('paquetesDestinos', 'paquetes', 'ciudades'));
    }

    public function createPaqueteDestino(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes);

        $error = $this->revisarDuplicado((int) $datos['id_paquete'], (int) $datos['id_ciudad']);
        if ($error) {
            return back()->with('error', $error)->withInput();
        }

        Paquete_Destinos::create($datos);

        return back()->with('success', '¡Destino asignado al paquete correctamente!');
    }

    public function deletePaqueteDestino(int $id_paquete, int $id_ciudad)
    {
        $relacion = Paquete_Destinos::where('id_paquete', $id_paquete)
            ->where('id_ciudad', $id_ciudad);

        if (!$relacion->exists()) {
            return back()->with('error', 'La asociación que intentas eliminar no existe.');
        }

        $relacion->delete();

        return back()->with('success', '¡Destino desasociado del paquete correctamente!');
    }
}