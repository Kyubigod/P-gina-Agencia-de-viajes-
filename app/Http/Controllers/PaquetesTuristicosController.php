<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paquetes_Turisticos;
use Illuminate\Support\Facades\DB;

class PaquetesTuristicosController extends Controller
{
    private array $mensajes = [
        'nombre_paquete.required' => 'El nombre del paquete es obligatorio.',
        'nombre_paquete.string'   => 'El nombre del paquete debe ser texto.',
        'nombre_paquete.max'      => 'El nombre no puede superar los 100 caracteres.',
        'descripcion.required'    => 'La descripción es obligatoria.',
        'descripcion.string'       => 'La descripción debe ser texto.',
        'descripcion.max'         => 'La descripción no puede superar los 500 caracteres.',
        'duracion_dias.required'  => 'La duración en días es obligatoria.',
        'duracion_dias.integer'   => 'La duración debe ser un número entero.',
        'duracion_dias.min'       => 'La duración mínima es de 1 día.',
        'precio_total.required'   => 'El precio total es obligatorio.',
        'precio_total.numeric'    => 'El precio debe ser un valor numérico.',
        'precio_total.min'        => 'El precio mínimo es $0.01.',
    ];

    private function reglas(): array
    {
        return [
            'nombre_paquete' => ['required', 'string', 'max:100'],
            'descripcion'    => ['required', 'string', 'max:500'],
            'duracion_dias'  => ['required', 'integer', 'min:1'],
            'precio_total'   => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
        ];
    }

    private function revisarRelaciones(int $id): ?string
    {
        $tieneHoteles = DB::table('paquete_hoteles')->where('id_paquete', $id)->exists();
        $tieneDestinos = DB::table('paquete_destinos')->where('id_paquete', $id)->exists();

        if ($tieneHoteles || $tieneDestinos) {
            return 'No se puede eliminar el paquete porque tiene hoteles o destinos asociados.';
        }

        return null;
    }

    public function getPaquetesTuristicos() 
    {
        $paquetesturisticos = Paquetes_Turisticos::all();

        return view('paquetesturisticos', compact('paquetesturisticos'));
    }

    public function createPaquetesTuristicos(Request $request) 
    {
        $datos = $request->validate($this->reglas(), $this->mensajes);

        Paquetes_Turisticos::create($datos);

        return back()->with('success', '¡Paquete turístico creado exitosamente!');
    }
    
    public function updatePaquetesTuristicos(Request $request, int $id) 
    {
        $datos = $request->validate($this->reglas(), $this->mensajes);

        $paquetesturisticos = Paquetes_Turisticos::findOrFail($id);
        $paquetesturisticos->update($datos);

        return back()->with('success', '¡Paquete turístico actualizado correctamente!');
    }
    
    public function deletePaquetesTuristicos(int $id) 
    {
        $error = $this->revisarRelaciones($id);
        if ($error) {
            return back()->with('error', $error);
        }

        $paquetesturisticos = Paquetes_Turisticos::findOrFail($id);
        $paquetesturisticos->delete();

        return back()->with('success', '¡Paquete turístico eliminado correctamente!');
    }
}