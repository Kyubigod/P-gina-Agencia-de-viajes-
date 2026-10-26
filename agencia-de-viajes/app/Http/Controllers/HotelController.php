<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Hotel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HotelController extends Controller
{
    private function reglas(Request $request, ?Hotel $hotel = null): array
    {
        return [
            'nombre_hotel' => [
                'required', 'string', 'min:2', 'max:150',
                Rule::unique('hoteles', 'nombre_hotel')
                    ->where('ciudad_id', $request->ciudad_id)
                    ->ignore($hotel?->id),
            ],
            'direccion' => ['required', 'string', 'min:5', 'max:255'],
            'estrellas' => ['required', 'integer', 'between:1,5'],
            'ciudad_id' => ['required', 'exists:ciudades,id'],
        ];
    }

    private array $mensajes = [
        'nombre_hotel.required' => 'El nombre del hotel es obligatorio.',
        'nombre_hotel.min'      => 'El nombre debe tener al menos 2 caracteres.',
        'nombre_hotel.max'      => 'El nombre no puede pasar de 150 caracteres.',
        'nombre_hotel.unique'   => 'Ya existe un hotel con ese nombre en la ciudad seleccionada.',
        'direccion.required'    => 'La dirección es obligatoria.',
        'direccion.min'         => 'La dirección debe tener al menos 5 caracteres.',
        'direccion.max'         => 'La dirección no puede pasar de 255 caracteres.',
        'estrellas.required'    => 'Las estrellas son obligatorias.',
        'estrellas.integer'     => 'Las estrellas deben ser un número entero.',
        'estrellas.between'     => 'Las estrellas deben estar entre 1 y 5.',
        'ciudad_id.required'    => 'Debes seleccionar una ciudad.',
        'ciudad_id.exists'      => 'La ciudad seleccionada no es válida.',
    ];

    public function getHotel()
    {
        $hoteles = Hotel::with('ciudad.pais')->withCount('habitaciones')->orderBy('nombre_hotel')->get();
        $ciudades = Ciudad::with('pais')->orderBy('nombre_ciudad')->get();

        return view('hoteles', compact('hoteles', 'ciudades'));
    }

    public function createHotel(Request $request)
    {
        $datos = $request->validate($this->reglas($request), $this->mensajes);
        Hotel::create($datos);

        return redirect('/hoteles')->with('ok', 'Hotel registrado correctamente.');
    }

    public function updateHotel(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $datos = $request->validate($this->reglas($request, $hotel), $this->mensajes);
        $hotel->update($datos);

        return redirect('/hoteles')->with('ok', 'Hotel actualizado correctamente.');
    }

    public function deleteHotel($id)
    {
        $hotel = Hotel::findOrFail($id);

        if ($hotel->habitaciones()->exists()) {
            return back()->withErrors(['eliminar' => 'No se puede eliminar: el hotel tiene habitaciones registradas.']);
        }

        try {
            $hotel->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['eliminar' => 'No se puede eliminar: el hotel está siendo usado en un paquete.']);
        }

        return redirect('/hoteles')->with('ok', 'Hotel eliminado.');
    }
}