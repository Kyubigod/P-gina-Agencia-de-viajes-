<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Reservaciones;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    private array $mensajes = [
        'nombre.required'   => 'El nombre del cliente es obligatorio.',
        'nombre.string'     => 'El nombre debe ser un texto válido.',
        'nombre.max'        => 'El nombre no puede exceder los 100 caracteres.',
        'apellido.required' => 'El apellido es obligatorio.',
        'apellido.string'   => 'El apellido debe ser un texto válido.',
        'apellido.max'      => 'El apellido no puede exceder los 100 caracteres.',
        'correo.required'   => 'El correo electrónico es obligatorio.',
        'correo.email'      => 'Ingresa un correo electrónico válido.',
        'correo.unique'     => 'Este correo electrónico ya está registrado por otro cliente.',
        'telefono.string'   => 'El teléfono debe ser un formato de texto válido.',
        'telefono.max'      => 'El teléfono no puede exceder los 20 caracteres.',
    ];

    private function reglas(?int $id = null): array
    {
        return [
            'nombre'   => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'correo'   => ['required', 'email', 'unique:clientes,correo' . ($id ? ',' . $id : '')],
            'telefono' => ['nullable', 'string', 'max:20'],
        ];
    }

    private function revisarReservacionesAsociadas(int $idCliente): ?string
    {
        $tieneReservaciones = Reservaciones::where('id_cliente', $idCliente)->exists();

        if ($tieneReservaciones) {
            return 'No se puede eliminar el cliente porque tiene reservaciones registradas.';
        }

        return null;
    }

    public function getCliente()
    {
        $clientes = Cliente::all();

        return view('clientes', compact('clientes'));
    }

    public function createCliente(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes);

        Cliente::create($datos);

        return back()->with('success', '¡Cliente registrado correctamente!');
    }

    public function updateCliente(Request $request, int $id)
    {
        $cliente = Cliente::findOrFail($id);

        $datos = $request->validate($this->reglas($id), $this->mensajes);

        $cliente->update($datos);

        return back()->with('success', '¡Cliente actualizado correctamente!');
    }

    public function deleteCliente(int $id)
    {
        $error = $this->revisarReservacionesAsociadas($id);
        if ($error) {
            return back()->with('error', $error);
        }

        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return back()->with('success', '¡Cliente eliminado correctamente!');
    }
}