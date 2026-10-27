<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Detalles de Reservación</title>
</head>
<body>
    @include('partials.menu')

    <div class="container mt-4">
        @include('partials.alertas')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Detalles de Reservación</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Agregar Detalle
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Reservación</th>
                    <th>ID Paquete</th>
                    <th>ID Habitación</th>
                    <th>ID Ruta</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->id_detalle }}</td>
                        <td>{{ $detalle->id_reservacion }}</td>
                        <td>{{ $detalle->id_paquete ?? 'N/A' }}</td>
                        <td>{{ $detalle->id_habitacion ?? 'N/A' }}</td>
                        <td>{{ $detalle->id_ruta ?? 'N/A' }}</td>
                        <td>{{ $detalle->fecha_inicio ? $detalle->fecha_inicio->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $detalle->fecha_fin ? $detalle->fecha_fin->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->subtotal, 2) }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $detalle->id_detalle }}">
                                Editar
                            </button>
                            <form action="/detallesreservacion/{{ $detalle->id_detalle }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Deseas eliminar este detalle?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEditar{{ $detalle->id_detalle }}" tabindex="-1" aria-labelledby="modalEditarLabel{{ $detalle->id_detalle }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="/detallesreservacion/{{ $detalle->id_detalle }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditarLabel{{ $detalle->id_detalle }}">Editar Detalle de Reservación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Reservación</label>
                                            <select name="id_reservacion" class="form-select" required>
                                                @foreach($reservaciones as $res)
                                                    <option value="{{ $res->id_reservacion }}" {{ $detalle->id_reservacion == $res->id_reservacion ? 'selected' : '' }}>
                                                        Reservación #{{ $res->id_reservacion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Paquete (Opcional)</label>
                                            <select name="id_paquete" class="form-select">
                                                <option value="">Ninguno</option>
                                                @foreach($paquetes as $paq)
                                                    <option value="{{ $paq->id_paquete }}" {{ $detalle->id_paquete == $paq->id_paquete ? 'selected' : '' }}>
                                                        {{ $paq->nombre_paquete }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Habitación (Opcional)</label>
                                            <select name="id_habitacion" class="form-select">
                                                <option value="">Ninguna</option>
                                                @foreach($habitaciones as $hab)
                                                    <option value="{{ $hab->id }}" {{ $detalle->id_habitacion == $hab->id ? 'selected' : '' }}>
                                                        Habitación #{{ $hab->id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ruta Transporte (Opcional)</label>
                                            <select name="id_ruta" class="form-select">
                                                <option value="">Ninguna</option>
                                                @foreach($rutas as $ruta)
                                                    <option value="{{ $ruta->id }}" {{ $detalle->id_ruta == $ruta->id ? 'selected' : '' }}>
                                                        Ruta #{{ $ruta->id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha Inicio</label>
                                            <input type="date" name="fecha_inicio" class="form-control" value="{{ $detalle->fecha_inicio ? $detalle->fecha_inicio->format('Y-m-d') : '' }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha Fin</label>
                                            <input type="date" name="fecha_fin" class="form-control" value="{{ $detalle->fecha_fin ? $detalle->fecha_fin->format('Y-m-d') : '' }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Cantidad</label>
                                            <input type="number" name="cantidad" class="form-control" value="{{ $detalle->cantidad }}" min="1" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Subtotal</label>
                                            <input type="number" step="0.01" name="subtotal" class="form-control" value="{{ $detalle->subtotal }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No hay detalles registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/detallesreservacion" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearLabel">Agregar Detalle de Reservación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reservación</label>
                            <select name="id_reservacion" class="form-select" required>
                                <option value="">Selecciona una reservación</option>
                                @foreach($reservaciones as $res)
                                    <option value="{{ $res->id_reservacion }}">Reservación #{{ $res->id_reservacion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Paquete (Opcional)</label>
                            <select name="id_paquete" class="form-select">
                                <option value="">Ninguno</option>
                                @foreach($paquetes as $paq)
                                    <option value="{{ $paq->id_paquete }}">{{ $paq->nombre_paquete }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Habitación (Opcional)</label>
                            <select name="id_habitacion" class="form-select">
                                <option value="">Ninguna</option>
                                @foreach($habitaciones as $hab)
                                    <option value="{{ $hab->id }}">Habitación #{{ $hab->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ruta Transporte (Opcional)</label>
                            <select name="id_ruta" class="form-select">
                                <option value="">Ninguna</option>
                                @foreach($rutas as $ruta)
                                    <option value="{{ $ruta->id }}">Ruta #{{ $ruta->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" name="cantidad" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subtotal</label>
                            <input type="number" step="0.01" name="subtotal" class="form-control" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>