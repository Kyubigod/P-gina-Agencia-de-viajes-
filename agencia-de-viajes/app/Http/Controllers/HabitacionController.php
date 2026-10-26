<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Hotel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HabitacionController extends Controller
{
    private function reglas(Request $request, ?Habitacion $habitacion = null): array
    {
        return [
            'hotel_id' => ['required', 'exists:hoteles,id'],
            'tipo_habitacion' => [
                'required', Rule::in(['Individual', 'Doble', 'Suite']),
                Rule::unique('habitaciones', 'tipo_habitacion')
                    ->where('hotel_id', $request->hotel_id)
                    ->ignore($habitacion?->id),
            ],
            'precio_noche' => ['required', 'numeric', 'min:1', 'max:999999.99'],
        ];
    }

    private array $mensajes = [
        'hotel_id.required'         => 'Debes seleccionar un hotel.',
        'hotel_id.exists'           => 'El hotel seleccionado no es válido.',
        'tipo_habitacion.required'  => 'El tipo de habitación es obligatorio.',
        'tipo_habitacion.in'        => 'El tipo debe ser Individual, Doble o Suite.',
        'tipo_habitacion.unique'    => 'Ese hotel ya tiene una habitación de ese tipo.',
        'precio_noche.required'     => 'El precio por noche es obligatorio.',
        'precio_noche.numeric'      => 'El precio debe ser un número.',
        'precio_noche.min'          => 'El precio debe ser mayor o igual a 1.',
        'precio_noche.max'          => 'El precio es demasiado alto.',
    ];

    public function getHabitacion()
    {
        $habitaciones = Habitacion::with('hotel')->orderBy('hotel_id')->orderBy('tipo_habitacion')->get();
        $hoteles = Hotel::orderBy('nombre_hotel')->get();

        return view('habitaciones', compact('habitaciones', 'hoteles'));
    }

    public function createHabitacion(Request $request)
    {
        $datos = $request->validate($this->reglas($request), $this->mensajes);
        Habitacion::create($datos);

        return redirect('/habitaciones')->with('ok', 'Habitación registrada correctamente.');
    }

    public function updateHabitacion(Request $request, $id)
    {
        $habitacion = Habitacion::findOrFail($id);
        $datos = $request->validate($this->reglas($request, $habitacion), $this->mensajes);
        $habitacion->update($datos);

        return redirect('/habitaciones')->with('ok', 'Habitación actualizada correctamente.');
    }

    public function deleteHabitacion($id)
    {
        $habitacion = Habitacion::findOrFail($id);

        try {
            $habitacion->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['eliminar' => 'No se puede eliminar: la habitación está siendo usada en una reservación.']);
        }

        return redirect('/habitaciones')->with('ok', 'Habitación eliminada.');
    }
}