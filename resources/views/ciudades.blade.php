<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ciudades</title>
</head>
<body>
    @include('partials.menu')

    <div class="container mt-4">
        @include('partials.alertas')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Lista de Ciudades</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Agregar Ciudad
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ciudad</th>
                    <th>País</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ciudades as $ciudad)
                <tr>
                    <td>{{ $ciudad->id }}</td>
                    <td>{{ $ciudad->nombre_ciudad }}</td>
                    <td>{{ $ciudad->pais->nombre_pais }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <form action="/ciudades/{{ $ciudad->id }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta ciudad?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>

                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $ciudad->id }}">
                                Editar
                            </button>
                        </div>

                        <div class="modal fade" id="modalEditar{{ $ciudad->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Ciudad #{{ $ciudad->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="/ciudades/{{ $ciudad->id }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nombre de la ciudad</label>
                                                <input type="text" name="nombre_ciudad" value="{{ $ciudad->nombre_ciudad }}" class="form-control" maxlength="100" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">País</label>
                                                <select name="pais_id" class="form-select" required>
                                                    @foreach($paises as $pais)
                                                        <option value="{{ $pais->id }}" @selected($ciudad->pais_id == $pais->id)>
                                                            {{ $pais->nombre_pais }}
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
                <tr><td colspan="4" class="text-center">No hay ciudades registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Ciudad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/ciudades" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre de la ciudad</label>
                            <input type="text" name="nombre_ciudad" class="form-control" maxlength="100" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">País</label>
                            <select name="pais_id" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                                @foreach($paises as $pais)
                                    <option value="{{ $pais->id }}">{{ $pais->nombre_pais }}</option>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>