<?php

namespace App\Http\Controllers;

use App\Models\DetalleReservacion;
use App\Models\Reservaciones; // O Reservacion según tu proyecto
use App\Models\Paquetes_Turisticos;
use App\Models\Habitacion;
use App\Models\RutaTransporte;
use Illuminate\Http\Request;

class DetallesReservacionController extends Controller
{
    public function getDetalleReservacion()
    {
        $detalles = DetalleReservacion::all();
        $reservaciones = Reservaciones::all();
        $paquetes = Paquetes_Turisticos::all();
        $habitaciones = Habitacion::all();
        $rutas = RutaTransporte::all();

        return view('detalles_reservacion', compact('detalles', 'reservaciones', 'paquetes', 'habitaciones', 'rutas'));
    }

    public function createDetalleReservacion(Request $request)
    {
        $request->validate([
            'id_reservacion' => 'required|exists:reservaciones,id_reservacion',
            'id_paquete'     => 'nullable|exists:paquetes_turisticos,id_paquete',
            'id_habitacion'  => 'nullable|exists:habitaciones,id',
            'id_ruta'        => 'nullable|exists:rutas_transporte,id',
            'fecha_inicio'   => 'nullable|date',
            'fecha_fin'      => 'nullable|date|after_or_equal:fecha_inicio',
            'cantidad'       => 'required|integer|min:1',
            'subtotal'       => 'required|numeric|min:0',
        ]);

        DetalleReservacion::create([
            'id_reservacion' => $request->id_reservacion,
            'id_paquete'     => $request->id_paquete,
            'id_habitacion'  => $request->id_habitacion,
            'id_ruta'        => $request->id_ruta,
            'fecha_inicio'   => $request->fecha_inicio,
            'fecha_fin'      => $request->fecha_fin,
            'cantidad'       => $request->cantidad,
            'subtotal'       => $request->subtotal,
        ]);

        return back()->with('success', '¡Detalle registrado correctamente!');
    }

    public function updateDetalleReservacion(Request $request, $id)
    {
        $request->validate([
            'id_reservacion' => 'required|exists:reservaciones,id_reservacion',
            'id_paquete'     => 'nullable|exists:paquetes_turisticos,id_paquete',
            'id_habitacion'  => 'nullable|exists:habitaciones,id',
            'id_ruta'        => 'nullable|exists:rutas_transporte,id',
            'fecha_inicio'   => 'nullable|date',
            'fecha_fin'      => 'nullable|date|after_or_equal:fecha_inicio',
            'cantidad'       => 'required|integer|min:1',
            'subtotal'       => 'required|numeric|min:0',
        ]);

        $detalle = DetalleReservacion::findOrFail($id);

        $detalle->update([
            'id_reservacion' => $request->id_reservacion,
            'id_paquete'     => $request->id_paquete,
            'id_habitacion'  => $request->id_habitacion,
            'id_ruta'        => $request->id_ruta,
            'fecha_inicio'   => $request->fecha_inicio,
            'fecha_fin'      => $request->fecha_fin,
            'cantidad'       => $request->cantidad,
            'subtotal'       => $request->subtotal,
        ]);

        return back()->with('success', '¡Detalle actualizado correctamente!');
    }

    public function deleteDetalleReservacion($id)
    {
        $detalle = DetalleReservacion::findOrFail($id);
        $detalle->delete();

        return back()->with('success', '¡Detalle eliminado correctamente!');
    }
}