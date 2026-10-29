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
        'nombre.regex'      => 'El nombre solo puede contener letras y espacios, sin números ni símbolos.',
        'apellido.required' => 'El apellido es obligatorio.',
        'apellido.string'   => 'El apellido debe ser un texto válido.',
        'apellido.max'      => 'El apellido no puede exceder los 100 caracteres.',
        'apellido.regex'    => 'El apellido solo puede contener letras y espacios, sin números ni símbolos.',
        'correo.required'   => 'El correo electrónico es obligatorio.',
        'correo.email'      => 'Ingresa un correo electrónico válido.',
        'correo.unique'     => 'Este correo electrónico ya está registrado por otro cliente.',
        'correo.regex'      => 'El correo electrónico debe ser de dominio @gmail.com.',
        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.regex'    => 'El teléfono debe contener exactamente 10 dígitos numéricos.',
    ];

    private function reglas(?int $id = null): array
    {
        return [
            'nombre'   => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'apellido' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'correo'   => ['required', 'email', 'unique:clientes,correo' . ($id ? ',' . $id : ''), 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'],
            'telefono' => ['required', 'regex:/^[0-9]{10}$/'],
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