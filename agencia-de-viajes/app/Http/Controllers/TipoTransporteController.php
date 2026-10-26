<?php

namespace App\Http\Controllers;

use App\Models\TipoTransporte;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoTransporteController extends Controller
{
    private function reglas(?TipoTransporte $tipo = null): array
    {
        return [
            'nombre_tipo' => [
                'required', 'string', 'min:3', 'max:50',
                Rule::unique('tipos_transporte', 'nombre_tipo')->ignore($tipo?->id),
            ],
        ];
    }

    private array $mensajes = [
        'nombre_tipo.required' => 'El nombre del tipo de transporte es obligatorio.',
        'nombre_tipo.min'      => 'El nombre debe tener al menos 3 caracteres.',
        'nombre_tipo.max'      => 'El nombre no puede pasar de 50 caracteres.',
        'nombre_tipo.unique'   => 'Ese tipo de transporte ya está registrado.',
    ];

    public function getTipoTransporte()
    {
        $tipos = TipoTransporte::withCount('rutas')->orderBy('nombre_tipo')->get();
        return view('tipos_transporte', compact('tipos'));
    }

    public function createTipoTransporte(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes);
        TipoTransporte::create($datos);

        return redirect('/tipos-transporte')->with('ok', 'Tipo de transporte registrado correctamente.');
    }

    public function updateTipoTransporte(Request $request, $id)
    {
        $tipo = TipoTransporte::findOrFail($id);
        $datos = $request->validate($this->reglas($tipo), $this->mensajes);
        $tipo->update($datos);

        return redirect('/tipos-transporte')->with('ok', 'Tipo de transporte actualizado correctamente.');
    }

    public function deleteTipoTransporte($id)
    {
        $tipo = TipoTransporte::findOrFail($id);

        if ($tipo->rutas()->exists()) {
            return back()->withErrors(['eliminar' => 'No se puede eliminar: el tipo de transporte tiene rutas registradas.']);
        }

        $tipo->delete();

        return redirect('/tipos-transporte')->with('ok', 'Tipo de transporte eliminado.');
    }
}