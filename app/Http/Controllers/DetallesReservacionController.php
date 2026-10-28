<?php

namespace App\Http\Controllers;

use App\Models\DetalleReservacion;
use App\Models\Reservaciones;
use App\Models\Paquetes_Turisticos;
use App\Models\Habitacion;
use App\Models\RutaTransporte;
use Illuminate\Http\Request;

class DetallesReservacionController extends Controller
{
    private array $mensajes = [
        'id_reservacion.required'  => 'Debes seleccionar una reservación.',
        'id_reservacion.exists'    => 'La reservación seleccionada no existe.',
        'id_paquete.exists'        => 'El paquete turístico seleccionado no existe.',
        'id_habitacion.exists'     => 'La habitación seleccionada no existe.',
        'id_ruta.exists'           => 'La ruta de transporte seleccionada no existe.',
        'fecha_inicio.date'        => 'La fecha de inicio no es válida.',
        'fecha_fin.date'           => 'La fecha de fin no es válida.',
        'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        'cantidad.required'        => 'La cantidad es obligatoria.',
        'cantidad.integer'         => 'La cantidad debe ser un número entero.',
        'cantidad.min'             => 'La cantidad mínima es 1.',
        'subtotal.required'        => 'El subtotal es obligatorio.',
        'subtotal.numeric'         => 'El subtotal debe ser un número.',
        'subtotal.min'             => 'El subtotal no puede ser negativo.',
    ];

    private function reglas(): array
    {
        return [
            'id_reservacion' => ['required', 'exists:reservaciones,id_reservacion'],
            'id_paquete'     => ['nullable', 'exists:paquetes_turisticos,id_paquete'],
            'id_habitacion'  => ['nullable', 'exists:habitaciones,id'],
            'id_ruta'        => ['nullable', 'exists:rutas_transporte,id'],
            'fecha_inicio'   => ['nullable', 'date'],
            'fecha_fin'      => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'cantidad'       => ['required', 'integer', 'min:1'],
            'subtotal'       => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    private function revisarEstadoReservacion(int $idReservacion): ?string
    {
        $reservacion = Reservaciones::where('id_reservacion',$idReservacion)->first();

        if ($reservacion && strtolower($reservacion->estado) !== 'pendiente') {
            return 'No se pueden modificar detalles en una reservación que está ' . $reservacion->estado . '.';
        }

        return null;
    }

    private function recalcularMontoTotal(int $idReservacion): void
    {
        $reservacion = Reservaciones::where('id_reservacion',$idReservacion)->first();

        if (!$reservacion) {
            return;
        }

        $montoTotal = DetalleReservacion::where('id_reservacion',$idReservacion)->sum('subtotal');
        $totalPagado = \App\Models\Pago::where('reservacion_id',$idReservacion)->sum('monto');

        $nuevoEstado =$reservacion->estado;
        if (strtolower($reservacion->estado) !== 'cancelada') {
            if ($montoTotal > 0 &&$totalPagado >= $montoTotal) {$nuevoEstado = 'confirmada';
            } else {
                $nuevoEstado = 'pendiente';
            }
        }

        $reservacion->update([
            'monto_total' => $montoTotal,
            'estado'      => $nuevoEstado,
        ]);
    }

    private function calcularSubtotal(Request $request): float
    {
        $subtotal = 0;

        if ($request->filled('id_paquete')) {$paquete = Paquetes_Turisticos::find($request->id_paquete);$subtotal += $paquete ? $paquete->precio_total : 0;
        }

        if ($request->filled('id_ruta')) {
            $ruta = RutaTransporte::find($request->id_ruta);
            $subtotal +=$ruta ? ($ruta->precio_trayecto ?? $ruta->precio ?? 0) : 0;
        }

        if ($request->filled('id_habitacion') && $request->filled('fecha_inicio') &&$request->filled('fecha_fin')) {
            $habitacion = Habitacion::find($request->id_habitacion);
            $precioNoche =$habitacion ? ($habitacion->precio_noche ?? $habitacion->precio ?? 0) : 0;
            $dias = \Carbon\Carbon::parse($request->fecha_inicio)->diffInDays(\Carbon\Carbon::parse($request->fecha_fin)) ?: 1;
            $subtotal += ($precioNoche * $dias);
        }

        $cantidad = max((int)$request->cantidad, 1);

        return round($subtotal * $cantidad, 2);
    }

    public function getDetalleReservacion()
    {
        $detalles = DetalleReservacion::all();
        $reservaciones = Reservaciones::where('estado', 'pendiente')->get();$paquetes = Paquetes_Turisticos::all();
        $habitaciones = Habitacion::all();$rutas = RutaTransporte::all();

        return view('detalles_reservacion', compact('detalles', 'reservaciones', 'paquetes', 'habitaciones', 'rutas'));
    }

    public function createDetalleReservacion(Request $request)
    {
        $datos =$request->validate($this->reglas(),$this->mensajes);

        $errorEstado = $this->revisarEstadoReservacion((int)$datos['id_reservacion']);
        if ($errorEstado) {
            return back()->with('error', $errorEstado)->withInput();
        }

        if (!$request->filled('id_paquete') && !$request->filled('id_habitacion') && !$request->filled('id_ruta')) {
            return back()->with('error', 'Debes seleccionar al menos un paquete, habitación o ruta de transporte.')->withInput();
        }

         if ($request->filled('id_habitacion') && (!$request->filled('fecha_inicio') || !$request->filled('fecha_fin'))) {
            return back()->with('error', 'Debes ingresar fecha de inicio y fin para calcular el hospedaje de la habitación.')->withInput();
        }

        $datos['subtotal'] = $this->calcularSubtotal($request);

        DetalleReservacion::create($datos);

        $this->recalcularMontoTotal((int)$datos['id_reservacion']);

        return back()->with('success', '¡Detalle agregado con subtotal de $' . number_format($datos['subtotal'], 2) . '!');
    }

    public function updateDetalleReservacion(Request $request, int$id)
    {
        $detalle = DetalleReservacion::findOrFail($id);
        $datos =$request->validate($this->reglas(),$this->mensajes);

        $error = $this->revisarEstadoReservacion((int)$datos['id_reservacion']);
        if ($error) {
            return back()->with('error', $error)->withInput();
        }

        if (!$request->filled('id_paquete') && !$request->filled('id_habitacion') && !$request->filled('id_ruta')) {
            return back()->with('error', 'Debes seleccionar al menos un paquete, habitación o ruta de transporte.')->withInput();
        }

        if ($request->filled('id_habitacion') && (!$request->filled('fecha_inicio') || !$request->filled('fecha_fin'))) {
            return back()->with('error', 'Debes ingresar fecha de inicio y fin para calcular el hospedaje de la habitación.')->withInput();
        }

        $datos['subtotal'] = $this->calcularSubtotal($request);

        $idAnterior =$detalle->id_reservacion;
        $detalle->update($datos);

        $this->recalcularMontoTotal((int)$datos['id_reservacion']);

        if ($idAnterior !=$datos['id_reservacion']) {
            $this->recalcularMontoTotal($idAnterior);
        }

        return back()->with('success', '¡Detalle de reservación actualizado correctamente!');
    }

    public function deleteDetalleReservacion(int $id)
    {
        $detalle = DetalleReservacion::findOrFail($id);
        $idReservacion =$detalle->id_reservacion;

        $error = $this->revisarEstadoReservacion($idReservacion);
        if ($error) {
            return back()->with('error', $error);
        }

        $detalle->delete();

        $this->recalcularMontoTotal($idReservacion);

        return back()->with('success', '¡Detalle de reservación eliminado correctamente!');
    }
}