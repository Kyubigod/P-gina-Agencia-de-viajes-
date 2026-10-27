<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Rutas de transporte</title>
</head>
<body>
    @include('partials.menu')

    <div class="container mt-4">
        @include('partials.alertas')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Rutas de Transporte</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Agregar Ruta
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Transporte</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Precio</th>
                    <th>Salida</th>
                    <th>Duración</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rutas as $ruta)
                <tr>
                    <td>{{ $ruta->id }}</td>
                    <td>{{ $ruta->tipo->nombre_tipo }}</td>
                    <td>{{ $ruta->origen->nombre_ciudad }}</td>
                    <td>{{ $ruta->destino->nombre_ciudad }}</td>
                    <td>${{ number_format($ruta->precio_trayecto, 2) }}</td>
                    <td>{{ $ruta->fecha_salida->format('d/m/Y H:i') }}</td>
                    <td>{{ $ruta->duracion_horas }} h</td>
                    <td>
                        <div class="d-flex gap-1">
                            <form action="/rutas/{{ $ruta->id }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta ruta?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>

                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $ruta->id }}">
                                Editar
                            </button>
                        </div>

                        <div class="modal fade" id="modalEditar{{ $ruta->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Ruta #{{ $ruta->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="/rutas/{{ $ruta->id }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Tipo de transporte</label>
                                                <select name="tipo_transporte_id" class="form-select" required>
                                                    @foreach($tipos as $tipo)
                                                        <option value="{{ $tipo->id }}" @selected($ruta->tipo_transporte_id == $tipo->id)>{{ $tipo->nombre_tipo }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ciudad de origen</label>
                                                <select name="ciudad_origen_id" class="form-select" required>
                                                    @foreach($ciudades as $ciudad)
                                                        <option value="{{ $ciudad->id }}" @selected($ruta->ciudad_origen_id == $ciudad->id)>{{ $ciudad->nombre_ciudad }} ({{ $ciudad->pais->nombre_pais }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ciudad de destino</label>
                                                <select name="ciudad_destino_id" class="form-select" required>
                                                    @foreach($ciudades as $ciudad)
                                                        <option value="{{ $ciudad->id }}" @selected($ruta->ciudad_destino_id == $ciudad->id)>{{ $ciudad->nombre_ciudad }} ({{ $ciudad->pais->nombre_pais }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Precio del trayecto</label>
                                                <input type="number" name="precio_trayecto" value="{{ $ruta->precio_trayecto }}" class="form-control" step="0.01" min="0" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Fecha y hora de salida</label>
                                                <input type="datetime-local" name="fecha_salida" value="{{ $ruta->fecha_salida->format('Y-m-d\TH:i') }}" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Duración (horas)</label>
                                                <input type="number" name="duracion_horas" value="{{ $ruta->duracion_horas }}" class="form-control" step="0.1" min="0.5" max="99.9" required>
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
                <tr><td colspan="8" class="text-center">No hay rutas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Ruta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/rutas" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tipo de transporte</label>
                            <select name="tipo_transporte_id" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre_tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ciudad de origen</label>
                            <select name="ciudad_origen_id" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                                @foreach($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->nombre_ciudad }} ({{ $ciudad->pais->nombre_pais }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ciudad de destino</label>
                            <select name="ciudad_destino_id" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                                @foreach($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->nombre_ciudad }} ({{ $ciudad->pais->nombre_pais }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio del trayecto</label>
                            <input type="number" name="precio_trayecto" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha y hora de salida</label>
                            <input type="datetime-local" name="fecha_salida" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duración (horas)</label>
                            <input type="number" name="duracion_horas" class="form-control" step="0.1" min="0.5" max="99.9" required>
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
</body>
</html>