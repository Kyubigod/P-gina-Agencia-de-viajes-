<?php

namespace App\Http\Controllers;

use App\Models\Reservaciones;
use App\Models\Cliente; 
use Illuminate\Http\Request;

class ReservacionesController extends Controller
{
    public function getReservaciones() 
    {
        $reservaciones = Reservaciones::all();
        $clientes = Cliente::all(); 

        return view('reservaciones', compact('reservaciones', 'clientes'));
    }

    public function createReservaciones(Request $request) 
{
    $request->validate([
        'id_cliente'        => 'required|integer|exists:clientes,id',
        'fecha_reservacion' => 'required|date|after_or_equal:today', 
        'estado'            => 'required|in:pendiente,confirmada,cancelada',
        'monto_total'       => 'required|numeric|min:0'
    ], [
        'fecha_reservacion.after_or_equal' => 'La fecha de la reservación no puede ser una fecha pasada.'
    ]);

    Reservaciones::create([
        'id_cliente'        => $request->id_cliente,
        'fecha_reservacion' => $request->fecha_reservacion,
        'estado'            => $request->estado,
        'monto_total'       => $request->monto_total
    ]);

    return back()->with('success', '¡Reservación creada correctamente!');
}

    public function updateReservaciones(Request $request, int $id)
    {
        $reservacion = Reservaciones::findOrFail($id);

        if (strtolower($reservacion->estado) !== 'pendiente') {
            return back()->with('error', 'No se puede modificar una reservación que ya está ' . $reservacion->estado . '.');
        }

        $request->validate([
            'id_cliente'        => 'required|integer|exists:clientes,id',
            'fecha_reservacion' => 'required|date',
            'estado'            => 'required|in:pendiente,confirmada,cancelada',
            'monto_total'       => 'required|numeric|min:0'
        ]);

        $reservacion->update([
            'id_cliente'        => $request->id_cliente,
            'fecha_reservacion' => $request->fecha_reservacion,
            'estado'            => $request->estado,
            'monto_total'       => $request->monto_total
        ]);

        return back()->with('success', '¡Reservación actualizada correctamente!');
    }

    public function deleteReservaciones(Request $request, int $id)
    {
        $reservacion = Reservaciones::findOrFail($id);

        if (strtolower($reservacion->estado) !== 'pendiente') {
            return back()->with('error', 'No se puede eliminar una reservación que ya está ' . $reservacion->estado . '.');
        }

        $reservacion->delete();

        return back()->with('success', '¡Reservación eliminada correctamente!');
    }
}