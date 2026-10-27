<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reservaciones;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    private array $mensajes = [
        'reservacion_id.required' => 'Debes seleccionar una reservación.',
        'reservacion_id.exists'   => 'La reservación seleccionada no es válida.',
        'metodo_pago.required'    => 'El método de pago es obligatorio.',
        'metodo_pago.in'          => 'El método debe ser Efectivo, Tarjeta o Transferencia.',
        'monto.required'          => 'El monto es obligatorio.',
        'monto.numeric'           => 'El monto debe ser un número.',
        'monto.min'               => 'El monto mínimo es 0.01.',
        'monto.max'               => 'El monto es demasiado alto.',
        'fecha_pago.required'     => 'La fecha de pago es obligatoria.',
        'fecha_pago.date'         => 'La fecha de pago no es válida.',
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

        if ($reservacion->estado === 'cancelada') {
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

    public function getPago()
    {
        $pagos = Pago::with('reservacion')->orderByDesc('fecha_pago')->orderByDesc('id')->get();
        $reservaciones = Reservaciones::where('estado', '!=', 'cancelada')
            ->orderBy('id_reservacion')->get();

        return view('pagos', compact('pagos', 'reservaciones'));
    }

    public function createPago(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes);

        $error = $this->revisarReservacion($datos['reservacion_id'], (float) $datos['monto']);
        if ($error) {
            return back()->withErrors(['monto' => $error])->withInput();
        }

        Pago::create($datos);

        return redirect('/pagos')->with('ok', 'Pago registrado correctamente.');
    }

    public function updatePago(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);
        $datos = $request->validate($this->reglas(), $this->mensajes);

        $error = $this->revisarReservacion($datos['reservacion_id'], (float) $datos['monto'], $pago->id);
        if ($error) {
            return back()->withErrors(['monto' => $error])->withInput();
        }

        $pago->update($datos);

        return redirect('/pagos')->with('ok', 'Pago actualizado correctamente.');
    }

    public function deletePago($id)
    {
        Pago::findOrFail($id)->delete();

        return redirect('/pagos')->with('ok', 'Pago eliminado.');
    }
}