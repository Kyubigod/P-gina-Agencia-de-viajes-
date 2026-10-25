<?php

namespace App\Http\Controllers;
use App\Models\Reservaciones;
use Illuminate\Http\Request;

class ReservacionesController extends Controller
{
   public function getReservaciones() {
    $reservaciones = Reservaciones::all();
    return view('reservaciones', compact('reservaciones'));
}

    public function createReservaciones(Request $request) 
    {
        $request->validate([
        'id_cliente'        => 'required|integer|exists:clientes,id',
        'fecha_reservacion' => 'required|date',
        'estado'            => 'required|in:pendiente,confirmada,cancelada',
        'monto_total'       => 'required|numeric'

    ]);

       Reservaciones::create([
            'id_cliente' => $request->id_cliente,
            'fecha_reservacion' => $request->fecha_reservacion,
            'estado' => $request->estado,
            'monto_total' => $request->monto_total
        ]);

        return back();
    }

    public function updateReservaciones(Request $request, Int $id)
    {
        $request->validate([
        'id_cliente'        => 'required|integer|exists:clientes,id',
        'fecha_reservacion' => 'required|date',
        'estado'            => 'required|in:pendiente,confirmada,cancelada',
        'monto_total'       => 'required|numeric'

    ]);
    $reservaciones = Reservaciones::findOrFail($id);

    $reservaciones->update([
            'id_cliente' => $request->id_cliente,
            'fecha_reservacion' => $request->fecha_reservacion,
            'estado' => $request->estado,
            'monto_total' => $request->monto_total
        ]);

        return back();
    }

    public function deleteReservaciones(Request $request, Int $id)
    {
        $reservaciones = Reservaciones::findOrFail($id);
        $reservaciones -> delete();
    }

}
