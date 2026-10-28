<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reservación</th>
                    <th>Paquete</th>
                    <th>Habitación</th>
                    <th>Ruta</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detalles as $detalle)
                    @php
                        $paquete = $paquetes->firstWhere('id_paquete', $detalle->id_paquete);
                        $habitacion = $habitaciones->firstWhere('id', $detalle->id_habitacion);
                        $ruta = $rutas->firstWhere('id', $detalle->id_ruta);
                    @endphp
                    <tr>
                        <td>{{ $detalle->id_detalle }}</td>
                        <td><strong>#{{ $detalle->id_reservacion }}</strong></td>
                        <td>{{ $paquete ? $paquete->nombre_paquete : 'N/A' }}</td>
                        <td>{{ $habitacion ? 'Habitación #' . $habitacion->id : 'N/A' }}</td>
                        <td>{{ $ruta ? 'Ruta #' . $ruta->id : 'N/A' }}</td>
                        <td>{{ $detalle->fecha_inicio ? \Carbon\Carbon::parse($detalle->fecha_inicio)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $detalle->fecha_fin ? \Carbon\Carbon::parse($detalle->fecha_fin)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->subtotal, 2) }}</td>
                        <td>
                            <div class="d-flex gap-1">
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
                            </div>

                            <div class="modal fade" id="modalEditar{{ $detalle->id_detalle }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="/detallesreservacion/{{ $detalle->id_detalle }}" method="POST" class="form-detalle" novalidate>
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Detalle #{{ $detalle->id_detalle }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Reservación</label>
                                                    <select name="id_reservacion" class="form-select @error('id_reservacion') is-invalid @enderror">
                                                        @foreach($reservaciones as $res)
                                                            <option value="{{ $res->id_reservacion }}" {{ old('id_reservacion', $detalle->id_reservacion) == $res->id_reservacion ? 'selected' : '' }}>
                                                                Reservación #{{ $res->id_reservacion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_reservacion')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Paquete (Opcional)</label>
                                                    <select name="id_paquete" class="form-select select-paquete @error('id_paquete') is-invalid @enderror">
                                                        <option value="" data-precio="0">Ninguno</option>
                                                        @foreach($paquetes as $paq)
                                                            <option value="{{ $paq->id_paquete }}" data-precio="{{ $paq->precio_total }}" {{ old('id_paquete', $detalle->id_paquete) == $paq->id_paquete ? 'selected' : '' }}>
                                                                {{ $paq->nombre_paquete }} (${{ number_format($paq->precio_total, 2) }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_paquete')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Habitación (Opcional)</label>
                                                    <select name="id_habitacion" class="form-select select-habitacion @error('id_habitacion') is-invalid @enderror">
                                                        <option value="" data-precio="0">Ninguna</option>
                                                        @foreach($habitaciones as $hab)
                                                            @php $precioHab = $hab->precio_noche ?? $hab->precio ?? 0; @endphp
                                                            <option value="{{ $hab->id }}" data-precio="{{ $precioHab }}" {{ old('id_habitacion', $detalle->id_habitacion) == $hab->id ? 'selected' : '' }}>
                                                                Habitación #{{ $hab->id }} (${{ number_format($precioHab, 2) }}/noche)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_habitacion')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ruta Transporte (Opcional)</label>
                                                    <select name="id_ruta" class="form-select select-ruta @error('id_ruta') is-invalid @enderror">
                                                        <option value="" data-precio="0">Ninguna</option>
                                                        @foreach($rutas as $ruta)
                                                            @php $precioRuta = $ruta->precio_trayecto ?? $ruta->precio ?? 0; @endphp
                                                            <option value="{{ $ruta->id }}" data-precio="{{ $precioRuta }}" {{ old('id_ruta', $detalle->id_ruta) == $ruta->id ? 'selected' : '' }}>
                                                                Ruta #{{ $ruta->id }} (${{ number_format($precioRuta, 2) }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_ruta')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Fecha Inicio</label>
                                                    <input type="date" name="fecha_inicio" class="form-control input-fecha-inicio @error('fecha_inicio') is-invalid @enderror" value="{{ old('fecha_inicio', $detalle->fecha_inicio ? \Carbon\Carbon::parse($detalle->fecha_inicio)->format('Y-m-d') : '') }}" min="{{ date('Y-m-d') }}">
                                                    @error('fecha_inicio')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Fecha Fin</label>
                                                    <input type="date" name="fecha_fin" class="form-control input-fecha-fin @error('fecha_fin') is-invalid @enderror" value="{{ old('fecha_fin', $detalle->fecha_fin ? \Carbon\Carbon::parse($detalle->fecha_fin)->format('Y-m-d') : '') }}" min="{{ date('Y-m-d') }}">
                                                    @error('fecha_fin')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Cantidad</label>
                                                    <input type="number" name="cantidad" class="form-control input-cantidad @error('cantidad') is-invalid @enderror" value="{{ old('cantidad', $detalle->cantidad) }}" min="1">
                                                    @error('cantidad')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Subtotal Calculado</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" name="subtotal" class="form-control input-subtotal bg-light @error('subtotal') is-invalid @enderror" value="{{ old('subtotal', $detalle->subtotal) }}" readonly>
                                                    </div>
                                                    <small class="form-text text-muted">Se calcula automáticamente según los ítems seleccionados.</small>
                                                    @error('subtotal')
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
                        <td colspan="10" class="text-center">No hay detalles registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/detallesreservacion" method="POST" class="form-detalle" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Detalle de Reservación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reservación Pendiente</label>
                            <select name="id_reservacion" class="form-select @error('id_reservacion') is-invalid @enderror">
                                <option value="">Selecciona una reservación</option>
                                @foreach($reservaciones as $res)
                                    <option value="{{ $res->id_reservacion }}" {{ old('id_reservacion') == $res->id_reservacion ? 'selected' : '' }}>
                                        Reservación #{{ $res->id_reservacion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_reservacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Paquete (Opcional)</label>
                            <select name="id_paquete" class="form-select select-paquete @error('id_paquete') is-invalid @enderror">
                                <option value="" data-precio="0">Ninguno</option>
                                @foreach($paquetes as $paq)
                                    <option value="{{ $paq->id_paquete }}" data-precio="{{ $paq->precio_total }}" {{ old('id_paquete') == $paq->id_paquete ? 'selected' : '' }}>
                                        {{ $paq->nombre_paquete }} (${{ number_format($paq->precio_total, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_paquete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Habitación (Opcional)</label>
                            <select name="id_habitacion" class="form-select select-habitacion @error('id_habitacion') is-invalid @enderror">
                                <option value="" data-precio="0">Ninguna</option>
                                @foreach($habitaciones as $hab)
                                    @php $precioHab = $hab->precio_noche ?? $hab->precio ?? 0; @endphp
                                    <option value="{{ $hab->id }}" data-precio="{{ $precioHab }}" {{ old('id_habitacion') == $hab->id ? 'selected' : '' }}>
                                        Habitación #{{ $hab->id }} (${{ number_format($precioHab, 2) }}/noche)
                                    </option>
                                @endforeach
                            </select>
                            @error('id_habitacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ruta Transporte (Opcional)</label>
                            <select name="id_ruta" class="form-select select-ruta @error('id_ruta') is-invalid @enderror">
                                <option value="" data-precio="0">Ninguna</option>
                                @foreach($rutas as $ruta)
                                    @php $precioRuta = $ruta->precio_trayecto ?? $ruta->precio ?? 0; @endphp
                                    <option value="{{ $ruta->id }}" data-precio="{{ $precioRuta }}" {{ old('id_ruta') == $ruta->id ? 'selected' : '' }}>
                                        Ruta #{{ $ruta->id }} (${{ number_format($precioRuta, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_ruta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="form-control input-fecha-inicio @error('fecha_inicio') is-invalid @enderror" min="{{ date('Y-m-d') }}">
                            @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="form-control input-fecha-fin @error('fecha_fin') is-invalid @enderror" min="{{ date('Y-m-d') }}">
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad', 1) }}" class="form-control input-cantidad @error('cantidad') is-invalid @enderror" min="1">
                            @error('cantidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subtotal Calculado</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="subtotal" value="{{ old('subtotal') }}" class="form-control input-subtotal bg-light @error('subtotal') is-invalid @enderror" readonly>
                            </div>
                            <small class="form-text text-muted">Se calcula automáticamente según los ítems seleccionados.</small>
                            @error('subtotal')
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formularios = document.querySelectorAll('.form-detalle');

            formularios.forEach(form => {
                const selectPaquete = form.querySelector('.select-paquete');
                const selectHabitacion = form.querySelector('.select-habitacion');
                const selectRuta = form.querySelector('.select-ruta');
                const fechaInicio = form.querySelector('.input-fecha-inicio');
                const fechaFin = form.querySelector('.input-fecha-fin');
                const inputCantidad = form.querySelector('.input-cantidad');
                const inputSubtotal = form.querySelector('.input-subtotal');

                function calcularSubtotal() {
                    let totalBase = 0;

                    if (selectPaquete && selectPaquete.selectedIndex >= 0) {
                        const opt = selectPaquete.options[selectPaquete.selectedIndex];
                        totalBase += parseFloat(opt.dataset.precio || 0);
                    }

                    if (selectRuta && selectRuta.selectedIndex >= 0) {
                        const opt = selectRuta.options[selectRuta.selectedIndex];
                        totalBase += parseFloat(opt.dataset.precio || 0);
                    }

                    if (selectHabitacion && selectHabitacion.selectedIndex >= 0) {
                        const opt = selectHabitacion.options[selectHabitacion.selectedIndex];
                        const precioNoche = parseFloat(opt.dataset.precio || 0);

                        let noches = 1;
                        if (fechaInicio && fechaFin && fechaInicio.value && fechaFin.value) {
                            const fInicio = new Date(fechaInicio.value);
                            const fFin = new Date(fechaFin.value);
                            const diffTime = fFin - fInicio;
                            if (diffTime > 0) {
                                noches = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            }
                        }
                        totalBase += (precioNoche * noches);
                    }

                    const cantidad = parseInt(inputCantidad?.value || 1);
                    const subtotalFinal = totalBase * (cantidad > 0 ? cantidad : 1);

                    if (inputSubtotal) {
                        inputSubtotal.value = subtotalFinal.toFixed(2);
                    }
                }

                [selectPaquete, selectHabitacion, selectRuta, fechaInicio, fechaFin, inputCantidad].forEach(element => {
                    if (element) {
                        element.addEventListener('change', calcularSubtotal);
                        element.addEventListener('input', calcularSubtotal);
                    }
                });

                calcularSubtotal();
            });
        });
    </script>
</body>
</html>