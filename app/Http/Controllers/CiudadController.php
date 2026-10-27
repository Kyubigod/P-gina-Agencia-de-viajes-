<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Pais;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CiudadController extends Controller
{
    private function reglas(Request $request, ?Ciudad $ciudad = null): array
    {
        return [
            'nombre_ciudad' => [
                'required', 'string', 'min:2', 'max:100',
                Rule::unique('ciudades', 'nombre_ciudad')
                    ->where('pais_id', $request->pais_id)
                    ->ignore($ciudad?->id),
            ],
            'pais_id' => ['required', 'exists:paises,id'],
        ];
    }

    private array $mensajes = [
        'nombre_ciudad.required' => 'El nombre de la ciudad es obligatorio.',
        'nombre_ciudad.min'      => 'El nombre debe tener al menos 2 caracteres.',
        'nombre_ciudad.max'      => 'El nombre no puede pasar de 100 caracteres.',
        'nombre_ciudad.unique'   => 'Esa ciudad ya existe en el país seleccionado.',
        'pais_id.required'       => 'Debes seleccionar un país.',
        'pais_id.exists'         => 'El país seleccionado no es válido.',
    ];

    public function getCiudad()
    {
        $ciudades = Ciudad::with('pais')->orderBy('nombre_ciudad')->get();
        $paises = Pais::orderBy('nombre_pais')->get();

        return view('ciudades', compact('ciudades', 'paises'));
    }

    public function createCiudad(Request $request)
    {
        $datos = $request->validate($this->reglas($request), $this->mensajes);
        Ciudad::create($datos);

        return redirect('/ciudades')->with('ok', 'Ciudad registrada correctamente.');
    }

    public function updateCiudad(Request $request, $id)
    {
        $ciudad = Ciudad::findOrFail($id);
        $datos = $request->validate($this->reglas($request, $ciudad), $this->mensajes);
        $ciudad->update($datos);

        return redirect('/ciudades')->with('ok', 'Ciudad actualizada correctamente.');
    }

    public function deleteCiudad($id)
    {
        $ciudad = Ciudad::findOrFail($id);

        try {
            $ciudad->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['eliminar' => 'No se puede eliminar: la ciudad está siendo usada (hoteles, rutas o paquetes).']);
        }

        return redirect('/ciudades')->with('ok', 'Ciudad eliminada.');
    }
}