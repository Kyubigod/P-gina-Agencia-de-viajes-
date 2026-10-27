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
        @include('partials.alertas')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Asignación de Destinos (Ciudades) a Paquetes</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Asignar Destino
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Paquete Turístico</th>
                    <th>Ciudad / Destino</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paquetesDestinos as $item)
                    @php
                        $paquete = $paquetes->firstWhere('id_paquete', $item->id_paquete);
                        $ciudadKey = isset($ciudades->first()->id) ? 'id' : 'id_ciudad';
                        $ciudad = $ciudades->firstWhere($ciudadKey, $item->id_ciudad);
                    @endphp
                    <tr>
                        <td>
                            <strong>#{{ $item->id_paquete }}</strong> - {{ $paquete ? $paquete->nombre_paquete : 'N/A' }}
                        </td>
                        <td>
                            <strong>#{{ $item->id_ciudad }}</strong> - {{ $ciudad ? $ciudad->nombre_ciudad : 'N/A' }}
                        </td>
                        <td>
                            <form action="/paquetedestinos/{{ $item->id_paquete }}/{{ $item->id_ciudad }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas desasociar esta ciudad del paquete?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No hay destinos asignados a paquetes turísticos.</td>
                    </tr>
                @endforelse
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
                                    <option value="{{ $p->id_paquete }}">
                                        {{ $p->nombre_paquete }} (ID: {{ $p->id_paquete }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ciudad / Destino</label>
                            <select name="id_ciudad" class="form-select" required>
                                <option value="">Selecciona una ciudad</option>
                                @foreach($ciudades as $c)
                                    @php $cId = $c->id ?? $c->id_ciudad; @endphp
                                    <option value="{{ $cId }}">
                                        {{ $c->nombre_ciudad }} (ID: {{ $cId }})
                                    </option>
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