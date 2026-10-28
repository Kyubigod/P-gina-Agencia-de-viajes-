<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reservaciones;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    private array $mensajes = [
        'reservacion_id.required'    => 'Debes seleccionar una reservación.',
        'reservacion_id.exists'      => 'La reservación seleccionada no es válida.',
        'metodo_pago.required'       => 'El método de pago es obligatorio.',
        'metodo_pago.in'             => 'El método debe ser Efectivo, Tarjeta o Transferencia.',
        'monto.required'             => 'El monto es obligatorio.',
        'monto.numeric'              => 'El monto debe ser un número.',
        'monto.min'                  => 'El monto mínimo es 0.01.',
        'monto.max'                  => 'El monto es demasiado alto.',
        'fecha_pago.required'        => 'La fecha de pago es obligatoria.',
        'fecha_pago.date'            => 'La fecha de pago no es válida.',
        'fecha_pago.before_or_equal' => 'La fecha de pago no puede ser futura.',
    ];

    private function reglas(): array
    {
        return [
            'reservacion_id' => ['required', 'exists:reservaciones,id_reservacion'],
            'metodo_pago'    => ['required', 'in:Efectivo,Tarjeta,Transferencia'],
            'monto'          => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'fecha_pago'     => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    private function revisarReservacion(int $reservacionId, float $monto, ?int $ignorarPagoId = null): ?string
    {
        $reservacion = Reservaciones::where('id_reservacion', $reservacionId)->first();

        if (strtolower($reservacion->estado) === 'cancelada') {
            return 'No se pueden registrar pagos en una reservación cancelada.';
        }

        $pagado = Pago::where('reservacion_id', $reservacionId)
            ->when($ignorarPagoId, fn ($q) => $q->where('id', '!=', $ignorarPagoId))
            ->sum('monto');

        $pendiente = round($reservacion->monto_total - $pagado, 2);

        if ($monto > $pendiente) {
            return 'El monto excede lo que falta por pagar ($' . number_format($pendiente, 2) . ').';
        }

        return null;
    }

    private function actualizarEstadoReservacion(int $reservacionId): void
    {
        $reservacion = Reservaciones::where('id_reservacion', $reservacionId)->first();

        if (!$reservacion || strtolower($reservacion->estado) === 'cancelada') {
            return;
        }

        $totalPagado = Pago::where('reservacion_id', $reservacionId)->sum('monto');

        if ($reservacion->monto_total > 0 && $totalPagado >= $reservacion->monto_total) {
            $reservacion->update(['estado' => 'confirmada']);
        } else {
            $reservacion->update(['estado' => 'pendiente']);
        }
    }

public function getPago()
{
    $pagos = Pago::with('reservacion')->orderByDesc('fecha_pago')->orderByDesc('id')->get();

    $reservaciones = Reservaciones::where('estado', 'pendiente')
        ->with('pagos')
        ->get()
        ->map(function ($reservacion) {
            $totalPagado = $reservacion->pagos->sum('monto');
            $reservacion->saldo_pendiente = max(0, round($reservacion->monto_total - $totalPagado, 2));
            return $reservacion;
        })
        ->filter(function ($reservacion) {
            return $reservacion->saldo_pendiente > 0;
        })
        ->values();

    return view('pagos', compact('pagos', 'reservaciones'));
}

    public function createPago(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes);

        $error = $this->revisarReservacion((int) $datos['reservacion_id'], (float) $datos['monto']);
        if ($error) {
            return back()->with('error', $error)->withInput();
        }

        Pago::create($datos);

        $this->actualizarEstadoReservacion((int) $datos['reservacion_id']);

        return back()->with('success', '¡Pago registrado correctamente!');
    }

    public function updatePago(Request $request, int $id)
{
    $pago = Pago::with('reservacion')->findOrFail($id);

    if ($pago->reservacion && strtolower($pago->reservacion->estado) === 'confirmada') {
        return back()->with('error', 'No se pueden editar pagos de una reservación que ya está confirmada.')->withInput();
    }

    $datos = $request->validate($this->reglas(), $this->mensajes);

    $idReservacionAnterior = $pago->reservacion_id;
    $pago->update($datos);

    $this->actualizarEstadoReservacion((int) $datos['reservacion_id']);
    if ($idReservacionAnterior != $datos['reservacion_id']) {
        $this->actualizarEstadoReservacion((int) $idReservacionAnterior);
    }

    return back()->with('ok', '¡Pago actualizado correctamente!');
}

    public function deletePago(int $id)
{
    $pago = Pago::with('reservacion')->findOrFail($id);

    if ($pago->reservacion && strtolower($pago->reservacion->estado) === 'confirmada') {
        return back()->with('error', 'No se pueden eliminar pagos de una reservación que ya está confirmada.');
    }

    $reservacionId = $pago->reservacion_id;
    $pago->delete();

    $this->actualizarEstadoReservacion((int) $reservacionId);

    return back()->with('success', '¡Pago eliminado correctamente!');
}

}