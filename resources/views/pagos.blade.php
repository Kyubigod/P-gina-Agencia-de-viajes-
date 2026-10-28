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

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reservación</th>
                    <th>Método</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagos as $pago)
                <tr>
                    <td>{{ $pago->id }}</td>
                    <td>#{{ $pago->reservacion_id }} ({{ ucfirst($pago->reservacion->estado) }})</td>
                    <td>{{ $pago->metodo_pago }}</td>
                    <td>${{ number_format($pago->monto, 2) }}</td>
                    <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <form action="/pagos/{{ $pago->id }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este pago?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>

                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $pago->id }}">
                                Editar
                            </button>
                        </div>

                        <div class="modal fade" id="modalEditar{{ $pago->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Pago #{{ $pago->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="/pagos/{{ $pago->id }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Reservación</label>
                                                <select name="reservacion_id" class="form-select" required>
                                                    @foreach($reservaciones as $r)
                                                        <option value="{{ $r->id_reservacion }}" @selected($pago->reservacion_id == $r->id_reservacion)>
                                                            #{{ $r->id_reservacion }} - Total ${{ number_format($r->monto_total, 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Método de pago</label>
                                                <select name="metodo_pago" class="form-select" required>
                                                    @foreach(['Efectivo', 'Tarjeta', 'Transferencia'] as $m)
                                                        <option value="{{ $m }}" @selected($pago->metodo_pago == $m)>{{ $m }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Monto</label>
                                                <input type="number" name="monto" value="{{ $pago->monto }}" class="form-control" step="0.01" min="0.01" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Fecha de pago</label>
                                                <input type="date" name="fecha_pago" value="{{ $pago->fecha_pago->format('Y-m-d') }}" max="{{ date('Y-m-d') }}" class="form-control" required>
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
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No hay pagos registrados.</td></tr>
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
                <form action="/pagos" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reservación</label>
                            <select name="reservacion_id" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                                @foreach($reservaciones as $r)
                                    <option value="{{ $r->id_reservacion }}">
                                        #{{ $r->id_reservacion }} - Total ${{ number_format($r->monto_total, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Método de pago</label>
                            <select name="metodo_pago" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Monto</label>
                            <input type="number" name="monto" class="form-control" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de pago</label>
                            <input type="date" name="fecha_pago" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" class="form-control" required>
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