<?php

namespace App\Http\Controllers;

use App\Models\Reservaciones;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ReservacionesController extends Controller
{
    private array $mensajes = [
        'id_cliente.required'        => 'Debes seleccionar un cliente.',
        'id_cliente.exists'          => 'El cliente seleccionado no existe.',
        'fecha_reservacion.required' => 'La fecha de reservación es obligatoria.',
        'fecha_reservacion.date'     => 'La fecha de reservación no es válida.',
        'fecha_reservacion.after_or_equal' => 'La fecha de la reservación no puede ser una fecha pasada.',
        'estado.in'                  => 'El estado seleccionado no es válido.',
    ];

    private function reglasCrear(): array
    {
        return [
            'id_cliente'        => ['required', 'integer', 'exists:clientes,id'],
            'fecha_reservacion' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    private function reglasEditar(): array
    {
        return [
            'id_cliente'        => ['required', 'integer', 'exists:clientes,id'],
            'fecha_reservacion' => ['required', 'date', 'after_or_equal:today'],
            'estado'            => ['nullable', 'in:pendiente,cancelada'], // Solo se permite cancelar manualmente
        ];
    }

    private function revisarEstado(Reservaciones $reservacion): ?string
    {
        if (strtolower($reservacion->estado) !== 'pendiente') {
            return 'No se puede modificar ni eliminar una reservación que ya está ' . $reservacion->estado . '.';
        }

        return null;
    }

    public function getReservaciones()
    {
        $reservaciones = Reservaciones::all();
        $clientes = Cliente::all();

        return view('reservaciones', compact('reservaciones', 'clientes'));
    }

    public function createReservaciones(Request $request)
    {
        $datos = $request->validate($this->reglasCrear(), $this->mensajes);

        $datos['estado'] = 'pendiente';
        $datos['monto_total'] = 0.00;

        Reservaciones::create($datos);

        return redirect('/reservaciones')->with('ok', 'Reservación creada correctamente.');
    }

    public function updateReservaciones(Request $request, int $id)
    {
        $reservacion = Reservaciones::findOrFail($id);

        $error = $this->revisarEstado($reservacion);
        if ($error) {
            return back()->with('error', $error)->withInput();
        }

        $datos = $request->validate($this->reglasEditar(), $this->mensajes);

        if (!isset($datos['estado']) || strtolower($datos['estado']) === 'confirmada') {
            unset($datos['estado']);
        }

        $reservacion->update($datos);

        return redirect('/reservaciones')->with('ok', 'Reservación actualizada correctamente.');
    }

    public function deleteReservaciones(int $id)
    {
        $reservacion = Reservaciones::findOrFail($id);

        $error = $this->revisarEstado($reservacion);
        if ($error) {
            return back()->with('error', $error);
        }

        $reservacion->delete();

        return redirect('/reservaciones')->with('ok', 'Reservación eliminada correctamente.');
    }
}