<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Asignación Paquete - Destino</title>
</head>
<body>
    @include('partials.menu')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Asignación de Destinos (Ciudades) a Paquetes</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Asignar Destino
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID Paquete</th>
                    <th>ID Ciudad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paquetesDestinos as $item)
                <tr>
                    <td>{{ $item->id_paquete }}</td>
                    <td>{{ $item->id_ciudad }}</td>
                    <td>
                        <form action="/paquetedestinos/{{ $item->id_paquete }}/{{ $item->id_ciudad }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas desasociar esta ciudad del paquete?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Destino a Paquete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/paquetedestinos" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Paquete Turístico</label>
                            <select name="id_paquete" class="form-select" required>
                                <option value="">Selecciona un paquete</option>
                                @foreach($paquetes as $p)
                                    <option value="{{ $p->id_paquete }}">{{ $p->id_paquete }} - {{ $p->nombre_paquete }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ciudad / Destino</label>
                            <select name="id_ciudad" class="form-select" required>
                                <option value="">Selecciona una ciudad</option>
                                @foreach($ciudades as $c)
                                    <option value="{{ $c->id ?? $c->id_ciudad }}">{{ $c->id ?? $c->id_ciudad }} - {{ $c->nombre_ciudad }}</option>
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