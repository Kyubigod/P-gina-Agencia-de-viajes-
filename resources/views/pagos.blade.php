<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Pagos</title>
</head>
<body>
    @include('partials.menu')

    <div class="container mt-4">
        @include('partials.alertas')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Lista de Pagos</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Registrar Pago
            </button>
        </div>

        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reservación</th>
                    <th>Método</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th class="text-center" style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagos as $pago)
                @php
                    $esConfirmada = strtolower($pago->reservacion->estado ?? '') === 'confirmada';
                @endphp
                <tr>
                    <td>{{ $pago->id }}</td>
                    <td>
                        <strong>#{{ $pago->reservacion_id }}</strong> 
                        @if($pago->reservacion)
                            <span class="badge {{ $esConfirmada ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($pago->reservacion->estado) }}
                            </span>
                            <small class="text-muted d-block">Total: ${{ number_format($pago->reservacion->monto_total, 2) }}</small>
                        @endif
                    </td>
                    <td>{{ $pago->metodo_pago }}</td>
                    <td>${{ number_format($pago->monto, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                    <td class="text-center">
                        @if(!$esConfirmada)
                            <div class="d-flex justify-content-center gap-1">
                                <form action="/pagos/{{ $pago->id }}" method="POST" onsubmit="return confirm('¿Eliminar este pago?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>

                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $pago->id }}">
                                    Editar
                                </button>
                            </div>

                            <div class="modal fade text-start" id="modalEditar{{ $pago->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Pago #{{ $pago->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="/pagos/{{ $pago->id }}" method="POST" novalidate>
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Reservación</label>
                                                    <select name="reservacion_id" class="form-select @error('reservacion_id') is-invalid @enderror">
                                                        @foreach($reservaciones as $r)
                                                            @php
                                                                $restante = round($r->saldo_pendiente ?? ($r->monto_total - $r->pagos->sum('monto')), 2);
                                                            @endphp
                                                            <option value="{{ $r->id_reservacion }}" {{ old('reservacion_id', $pago->reservacion_id) == $r->id_reservacion ? 'selected' : '' }}>
                                                                Reservación #{{ $r->id_reservacion }} - Restante: ${{ number_format($restante, 2) }} (Total: ${{ number_format($r->monto_total, 2) }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('reservacion_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Método de pago</label>
                                                    <select name="metodo_pago" class="form-select @error('metodo_pago') is-invalid @enderror">
                                                        @foreach(['Efectivo', 'Tarjeta', 'Transferencia'] as $m)
                                                            <option value="{{ $m }}" {{ old('metodo_pago', $pago->metodo_pago) == $m ? 'selected' : '' }}>{{ $m }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('metodo_pago')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Monto</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" name="monto" value="{{ old('monto', $pago->monto) }}" class="form-control @error('monto') is-invalid @enderror" step="0.01" min="0.01">
                                                    </div>
                                                    @error('monto')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Fecha de pago</label>
                                                    <input type="date" name="fecha_pago" value="{{ old('fecha_pago', \Carbon\Carbon::parse($pago->fecha_pago)->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}" class="form-control @error('fecha_pago') is-invalid @enderror">
                                                    @error('fecha_pago')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay pagos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/pagos" method="POST" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reservación Pendiente</label>
                            <select name="reservacion_id" class="form-select @error('reservacion_id') is-invalid @enderror">
                                <option value="">-- Selecciona una reservación --</option>
                                @foreach($reservaciones as $r)
                                    @php
                                        $restante = round($r->saldo_pendiente ?? ($r->monto_total - $r->pagos->sum('monto')), 2);
                                    @endphp
                                    <option value="{{ $r->id_reservacion }}" {{ old('reservacion_id') == $r->id_reservacion ? 'selected' : '' }}>
                                        Reservación #{{ $r->id_reservacion }} - Restante: ${{ number_format($restante, 2) }} (Total: ${{ number_format($r->monto_total, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('reservacion_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Método de pago</label>
                            <select name="metodo_pago" class="form-select @error('metodo_pago') is-invalid @enderror">
                                <option value="">-- Selecciona --</option>
                                <option value="Efectivo" {{ old('metodo_pago') == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                <option value="Tarjeta" {{ old('metodo_pago') == 'Tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                <option value="Transferencia" {{ old('metodo_pago') == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                            </select>
                            @error('metodo_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="monto" value="{{ old('monto') }}" class="form-control @error('monto') is-invalid @enderror" step="0.01" min="0.01">
                            </div>
                            @error('monto')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de pago</label>
                            <input type="date" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" class="form-control @error('fecha_pago') is-invalid @enderror">
                            @error('fecha_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>