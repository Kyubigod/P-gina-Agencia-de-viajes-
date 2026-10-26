<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Hoteles</title>
</head>
<body>
    @include('partials.menu')

    <div class="container mt-4">
        @include('partials.alertas')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Lista de Hoteles</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Agregar Hotel
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hotel</th>
                    <th>Dirección</th>
                    <th>Estrellas</th>
                    <th>Ciudad</th>
                    <th>Habitaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hoteles as $hotel)
                <tr>
                    <td>{{ $hotel->id }}</td>
                    <td>{{ $hotel->nombre_hotel }}</td>
                    <td>{{ $hotel->direccion }}</td>
                    <td>{{ str_repeat('★', $hotel->estrellas) }}</td>
                    <td>{{ $hotel->ciudad->nombre_ciudad }}, {{ $hotel->ciudad->pais->nombre_pais }}</td>
                    <td>{{ $hotel->habitaciones_count }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <form action="/hoteles/{{ $hotel->id }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este hotel?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>

                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $hotel->id }}">
                                Editar
                            </button>
                        </div>

                        <div class="modal fade" id="modalEditar{{ $hotel->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Hotel #{{ $hotel->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="/hoteles/{{ $hotel->id }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nombre del hotel</label>
                                                <input type="text" name="nombre_hotel" value="{{ $hotel->nombre_hotel }}" class="form-control" maxlength="150" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Dirección</label>
                                                <input type="text" name="direccion" value="{{ $hotel->direccion }}" class="form-control" maxlength="255" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Estrellas</label>
                                                <select name="estrellas" class="form-select" required>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}" @selected($hotel->estrellas == $i)>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ciudad</label>
                                                <select name="ciudad_id" class="form-select" required>
                                                    @foreach($ciudades as $ciudad)
                                                        <option value="{{ $ciudad->id }}" @selected($hotel->ciudad_id == $ciudad->id)>
                                                            {{ $ciudad->nombre_ciudad }} ({{ $ciudad->pais->nombre_pais }})
                                                        </option>
                                                    @endforeach
                                                </select>
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
                <tr><td colspan="7" class="text-center">No hay hoteles registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Hotel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/hoteles" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del hotel</label>
                            <input type="text" name="nombre_hotel" class="form-control" maxlength="150" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" maxlength="255" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estrellas</label>
                            <select name="estrellas" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ciudad</label>
                            <select name="ciudad_id" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                                @foreach($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}">{{ $ciudad->nombre_ciudad }} ({{ $ciudad->pais->nombre_pais }})</option>
                                @endforeach
                            </select>
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