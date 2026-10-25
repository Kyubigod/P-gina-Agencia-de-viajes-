<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaisController extends Controller
{
    private function reglas(?Pais $pais = null): array
    {
        return [
            'nombre_pais' => [
                'required', 'string', 'min:2', 'max:100',
                Rule::unique('paises', 'nombre_pais')->ignore($pais?->id),
            ],
        ];
    }

    private array $mensajes = [
        'nombre_pais.required' => 'El nombre del país es obligatorio.',
        'nombre_pais.min'      => 'El nombre debe tener al menos 2 caracteres.',
        'nombre_pais.max'      => 'El nombre no puede pasar de 100 caracteres.',
        'nombre_pais.unique'   => 'Ese país ya está registrado.',
    ];

    public function getPais()
    {
        $paises = Pais::withCount('ciudades')->orderBy('nombre_pais')->get();
        return view('paises', compact('paises'));
    }

    public function createPais(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes);
        Pais::create($datos);

        return redirect('/paises')->with('ok', 'País registrado correctamente.');
    }

    public function updatePais(Request $request, $id)
    {
        $pais = Pais::findOrFail($id);
        $datos = $request->validate($this->reglas($pais), $this->mensajes);
        $pais->update($datos);

        return redirect('/paises')->with('ok', 'País actualizado correctamente.');
    }

    public function deletePais($id)
    {
        $pais = Pais::findOrFail($id);

        if ($pais->ciudades()->exists()) {
            return back()->withErrors(['eliminar' => 'No se puede eliminar: el país tiene ciudades registradas.']);
        }

        $pais->delete();

        return redirect('/paises')->with('ok', 'País eliminado.');
    }
}