<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Lista de Paquetes Turísticos</title>
</head>
<body>
    @include('partials.menu')

    <div class="container mt-4">
        @include('partials.alertas')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Lista de Paquetes Turísticos</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Nuevo Paquete
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Paquete</th>
                    <th>Descripción</th>
                    <th>Duración (Días)</th>
                    <th>Precio Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paquetesturisticos as $paquete)
                <tr>
                    <td>{{ $paquete->id_paquete }}</td>
                    <td>{{ $paquete->nombre_paquete }}</td>
                    <td>{{ $paquete->descripcion }}</td>
                    <td>{{ $paquete->duracion_dias }} días</td>
                    <td>${{ number_format($paquete->precio_total, 2) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <form action="/paquetesturisticos/{{ $paquete->id_paquete }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas eliminar este paquete?')">Eliminar</button>
                            </form>

                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $paquete->id_paquete }}">
                                Editar
                            </button>
                        </div>

                        <div class="modal fade" id="modalEditar{{ $paquete->id_paquete }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Paquete #{{ $paquete->id_paquete }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="/paquetesturisticos/{{ $paquete->id_paquete }}" method="POST" novalidate>
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nombre del Paquete</label>
                                                <input type="text" name="nombre_paquete" value="{{ old('nombre_paquete', $paquete->nombre_paquete) }}" class="form-control @error('nombre_paquete') is-invalid @enderror" maxlength="100">
                                                @error('nombre_paquete')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Descripción</label>
                                                <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" maxlength="500">{{ old('descripcion', $paquete->descripcion) }}</textarea>
                                                @error('descripcion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Duración (Días)</label>
                                                <input type="number" name="duracion_dias" value="{{ old('duracion_dias', $paquete->duracion_dias) }}" class="form-control @error('duracion_dias') is-invalid @enderror" min="1">
                                                @error('duracion_dias')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Precio Total</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" min="0.01" name="precio_total" value="{{ old('precio_total', $paquete->precio_total) }}" class="form-control @error('precio_total') is-invalid @enderror">
                                                </div>
                                                @error('precio_total')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
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
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay paquetes turísticos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Paquete Turístico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/paquetesturisticos" method="POST" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Paquete</label>
                            <input type="text" name="nombre_paquete" value="{{ old('nombre_paquete') }}" class="form-control @error('nombre_paquete') is-invalid @enderror" maxlength="100">
                            @error('nombre_paquete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" maxlength="500">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duración (Días)</label>
                            <input type="number" name="duracion_dias" value="{{ old('duracion_dias') }}" class="form-control @error('duracion_dias') is-invalid @enderror" min="1">
                            @error('duracion_dias')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio Total</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0.01" name="precio_total" value="{{ old('precio_total') }}" class="form-control @error('precio_total') is-invalid @enderror">
                            </div>
                            @error('precio_total')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
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