<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Lista de Reservaciones</title>
</head>
<body>
    @include('partials.menu')

    <div class="container mt-4">
        @include('partials.alertas')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Lista de Reservaciones</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Nueva Reservación
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID Reservación</th>
                    <th>Cliente</th>
                    <th>Fecha Reservación</th>
                    <th>Estado</th>
                    <th>Monto Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservaciones as $reservacion)
                    @php
                        $cliente = $clientes->firstWhere('id', $reservacion->id_cliente);
                    @endphp
                    <tr>
                        <td>{{ $reservacion->id_reservacion }}</td>
                        <td>
                            <strong>#{{ $reservacion->id_cliente }}</strong> - {{ $cliente ? $cliente->nombre . ' ' . ($cliente->apellido ?? '') : 'N/A' }}
                        </td>
                        <td>{{ $reservacion->fecha_reservacion }}</td>
                        <td>
                            <span class="badge {{ $reservacion->estado == 'confirmada' ? 'bg-success' : ($reservacion->estado == 'cancelada' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ ucfirst($reservacion->estado) }}
                            </span>
                        </td>
                        <td>${{ number_format($reservacion->monto_total, 2) }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <form action="/reservaciones/{{ $reservacion->id_reservacion }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas eliminar esta reservación?')" {{ strtolower($reservacion->estado) !== 'pendiente' ? 'disabled' : '' }}>
                                        Eliminar
                                    </button>
                                </form>

                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $reservacion->id_reservacion }}" {{ strtolower($reservacion->estado) !== 'pendiente' ? 'disabled' : '' }}>
                                    Editar
                                </button>
                            </div>

                            <div class="modal fade" id="modalEditar{{ $reservacion->id_reservacion }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Reservación #{{ $reservacion->id_reservacion }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="/reservaciones/{{ $reservacion->id_reservacion }}" method="POST" novalidate>
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Cliente</label>
                                                    <select name="id_cliente" class="form-select @error('id_cliente') is-invalid @enderror">
                                                        <option value="">Selecciona un cliente</option>
                                                        @foreach($clientes as $cli)
                                                            <option value="{{ $cli->id }}" {{ old('id_cliente', $reservacion->id_cliente) == $cli->id ? 'selected' : '' }}>
                                                                {{ $cli->nombre }} {{ $cli->apellido ?? '' }} (ID: {{ $cli->id }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_cliente')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Fecha Reservación</label>
                                                    <input type="date" name="fecha_reservacion" value="{{ old('fecha_reservacion', $reservacion->fecha_reservacion) }}" min="{{ date('Y-m-d') }}" class="form-control @error('fecha_reservacion') is-invalid @enderror">
                                                    @error('fecha_reservacion')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Estado</label>
                                                    <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                                        <option value="pendiente" {{ old('estado', $reservacion->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                        <option value="confirmada" {{ old('estado', $reservacion->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                                        <option value="cancelada" {{ old('estado', $reservacion->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                                    </select>
                                                    @error('estado')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Monto Total Calculado</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" value="{{ number_format($reservacion->monto_total, 2) }}" readonly disabled>
                                                    </div>
                                                    <small class="form-text text-muted">Se recalcula automáticamente desde los detalles de reservación.</small>
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
                    <tr>
                        <td colspan="6" class="text-center">No hay reservaciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Reservación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/reservaciones" method="POST" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <select name="id_cliente" class="form-select @error('id_cliente') is-invalid @enderror">
                                <option value="">Selecciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('id_cliente') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }} {{ $cliente->apellido ?? '' }} (ID: {{ $cliente->id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_cliente')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de Reservación</label>
                            <input type="date" name="fecha_reservacion" value="{{ old('fecha_reservacion') }}" min="{{ date('Y-m-d') }}" class="form-control @error('fecha_reservacion') is-invalid @enderror">
                            @error('fecha_reservacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado Inicial</label>
                            <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                <option value="pendiente" {{ old('estado', 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ old('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="cancelada" {{ old('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                            @error('estado')
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