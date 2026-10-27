<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\RutaTransporte;
use App\Models\TipoTransporte;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RutaTransporteController extends Controller
{
    private function reglas(bool $esNueva): array
    {
        return [
            'tipo_transporte_id' => ['required', 'exists:tipos_transporte,id'],
            'ciudad_origen_id'   => ['required', 'exists:ciudades,id'],
            'ciudad_destino_id'  => ['required', 'exists:ciudades,id', 'different:ciudad_origen_id'],
            'precio_trayecto'    => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'fecha_salida'       => $esNueva
                ? ['required', 'date', 'after:now']
                : ['required', 'date'],
            'duracion_horas'     => ['required', 'numeric', 'min:0.5', 'max:99.9'],
        ];
    }

    private array $mensajes = [
        'tipo_transporte_id.required' => 'Debes seleccionar un tipo de transporte.',
        'tipo_transporte_id.exists'   => 'El tipo de transporte seleccionado no es válido.',
        'ciudad_origen_id.required'   => 'Debes seleccionar la ciudad de origen.',
        'ciudad_origen_id.exists'     => 'La ciudad de origen no es válida.',
        'ciudad_destino_id.required'  => 'Debes seleccionar la ciudad de destino.',
        'ciudad_destino_id.exists'    => 'La ciudad de destino no es válida.',
        'ciudad_destino_id.different' => 'El destino debe ser distinto al origen.',
        'precio_trayecto.required'    => 'El precio del trayecto es obligatorio.',
        'precio_trayecto.numeric'     => 'El precio debe ser un número.',
        'precio_trayecto.min'         => 'El precio no puede ser negativo.',
        'precio_trayecto.max'         => 'El precio es demasiado alto.',
        'fecha_salida.required'       => 'La fecha de salida es obligatoria.',
        'fecha_salida.date'           => 'La fecha de salida no es válida.',
        'fecha_salida.after'          => 'La fecha de salida debe ser futura.',
        'duracion_horas.required'     => 'La duración es obligatoria.',
        'duracion_horas.numeric'      => 'La duración debe ser un número.',
        'duracion_horas.min'          => 'La duración mínima es 0.5 horas.',
        'duracion_horas.max'          => 'La duración es demasiado larga.',
    ];

    public function getRutaTransporte()
    {
        $rutas = RutaTransporte::with(['tipo', 'origen', 'destino'])->orderBy('fecha_salida')->get();
        $tipos = TipoTransporte::orderBy('nombre_tipo')->get();
        $ciudades = Ciudad::with('pais')->orderBy('nombre_ciudad')->get();

        return view('rutas_transporte', compact('rutas', 'tipos', 'ciudades'));
    }

    public function createRutaTransporte(Request $request)
    {
        $datos = $request->validate($this->reglas(true), $this->mensajes);
        RutaTransporte::create($datos);

        return redirect('/rutas')->with('ok', 'Ruta registrada correctamente.');
    }

    public function updateRutaTransporte(Request $request, $id)
    {
        $ruta = RutaTransporte::findOrFail($id);
        $datos = $request->validate($this->reglas(false), $this->mensajes);
        $ruta->update($datos);

        return redirect('/rutas')->with('ok', 'Ruta actualizada correctamente.');
    }

    public function deleteRutaTransporte($id)
    {
        $ruta = RutaTransporte::findOrFail($id);

        try {
            $ruta->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['eliminar' => 'No se puede eliminar: la ruta está siendo usada en una reservación.']);
        }

        return redirect('/rutas')->with('ok', 'Ruta eliminada.');
    }
}