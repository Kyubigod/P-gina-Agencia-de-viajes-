<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Habitaciones</title>
</head>
<body>
    @include('partials.menu')

    <div class="container mt-4">
        @include('partials.alertas')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Lista de Habitaciones</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Agregar Habitación
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hotel</th>
                    <th>Tipo</th>
                    <th>Precio por noche</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($habitaciones as $habitacion)
                <tr>
                    <td>{{ $habitacion->id }}</td>
                    <td>{{ $habitacion->hotel->nombre_hotel }}</td>
                    <td>{{ $habitacion->tipo_habitacion }}</td>
                    <td>${{ number_format($habitacion->precio_noche, 2) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <form action="/habitaciones/{{ $habitacion->id }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta habitación?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>

                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $habitacion->id }}">
                                Editar
                            </button>
                        </div>

                        <div class="modal fade" id="modalEditar{{ $habitacion->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Habitación #{{ $habitacion->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="/habitaciones/{{ $habitacion->id }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Hotel</label>
                                                <select name="hotel_id" class="form-select" required>
                                                    @foreach($hoteles as $hotel)
                                                        <option value="{{ $hotel->id }}" @selected($habitacion->hotel_id == $hotel->id)>
                                                            {{ $hotel->nombre_hotel }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tipo de habitación</label>
                                                <select name="tipo_habitacion" class="form-select" required>
                                                    @foreach(['Individual', 'Doble', 'Suite'] as $tipo)
                                                        <option value="{{ $tipo }}" @selected($habitacion->tipo_habitacion == $tipo)>{{ $tipo }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Precio por noche</label>
                                                <input type="number" name="precio_noche" value="{{ $habitacion->precio_noche }}" class="form-control" step="0.01" min="1" required>
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
                <tr><td colspan="5" class="text-center">No hay habitaciones registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Habitación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/habitaciones" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Hotel</label>
                            <select name="hotel_id" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                                @foreach($hoteles as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->nombre_hotel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de habitación</label>
                            <select name="tipo_habitacion" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                                <option value="Individual">Individual</option>
                                <option value="Doble">Doble</option>
                                <option value="Suite">Suite</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio por noche</label>
                            <input type="number" name="precio_noche" class="form-control" step="0.01" min="1" required>
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