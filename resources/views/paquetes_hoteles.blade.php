<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Paquetes y Hoteles</title>
</head>
<body>
    @include('partials.menu')

    <div class="container mt-4">
        @include('partials.alertas')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Asignación de Hoteles a Paquetes</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                Asignar Hotel
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Paquete Turístico</th>
                    <th>Hotel Asignado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paqueteHoteles as $item)
                    @php
                        $paquete = $paquetes->firstWhere('id_paquete', $item->id_paquete);
                        $hotel = $hoteles->firstWhere('id', $item->id_hotel);
                    @endphp
                    <tr>
                        <td>
                            <strong>#{{ $item->id_paquete }}</strong> - {{ $paquete ? $paquete->nombre_paquete : 'N/A' }}
                        </td>
                        <td>
                            <strong>#{{ $item->id_hotel }}</strong> - {{ $hotel ? $hotel->nombre_hotel : 'N/A' }}
                        </td>
                        <td>
                            <form action="/paquete-hoteles/{{ $item->id_paquete }}/{{ $item->id_hotel }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Deseas eliminar esta relación?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No hay registros de hoteles asignados a paquetes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/paquete-hoteles" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearLabel">Asignar Hotel a Paquete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_paquete" class="form-label">Paquete Turístico</label>
                            <select name="id_paquete" id="id_paquete" class="form-select" required>
                                <option value="">Selecciona un paquete</option>
                                @foreach($paquetes as $paquete)
                                    <option value="{{ $paquete->id_paquete }}">{{ $paquete->nombre_paquete }} (ID: {{ $paquete->id_paquete }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_hotel" class="form-label">Hotel</label>
                            <select name="id_hotel" id="id_hotel" class="form-select" required>
                                <option value="">Selecciona un hotel</option>
                                @foreach($hoteles as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->nombre_hotel }} (ID: {{ $hotel->id }})</option>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>