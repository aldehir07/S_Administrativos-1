@extends('layouts.app')
@section('content')
<div class="container">
    <h2 class="mb-4">Editar Movimiento de Inventario</h2>

    <form method="POST" action="{{ route('movimiento.update', $movimiento->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tipo de Movimiento</label>
            <select name="tipo_movimiento" class="form-select" id="tipoRegistro" required disabled>
                <option value="Entrada" {{ $movimiento->tipo_movimiento == 'Entrada' ? 'selected' : '' }}>Entrada</option>
                <option value="Salida" {{ $movimiento->tipo_movimiento == 'Salida' ? 'selected' : '' }}>Salida</option>
                <option value="Descarte" {{ $movimiento->tipo_movimiento == 'Descarte' ? 'selected' : '' }}>Descarte</option>
            </select>
            <input type="hidden" name="tipo_movimiento" value="{{ $movimiento->tipo_movimiento }}">
        </div>

        <div class="row">
            <div class="col-md-6">

                <!-- Clasificación -->
                <div class="mb-3 entrada-campos salida-campos descarte-campos d-none">
                    <label class="form-label">Clasificación</label>
                    <select name="clasificacion_id" class="form-select" id="clasificacionSelect" required>
                        @foreach($clasificaciones as $clasificacion)
                        <option value="{{ $clasificacion->id }}" {{ $movimiento->clasificacion_id == $clasificacion->id ? 'selected' : '' }}>
                            {{ $clasificacion->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Producto -->
                <div class="mb-3 entrada-campos salida-campos descarte-campos d-none">
                    <label class="form-label">Producto</label>
                    <select name="producto_id" class="form-select" id="productoSelect" required>
                        @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" {{ $movimiento->producto_id == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Cantidad -->
                <div class="mb-3 entrada-campos salida-campos descarte-campos d-none">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" min="1" value="{{ $movimiento->cantidad }}">
                </div>

                <!-- Evento/Destino -->
                <div class="mb-3 salida-campos d-none">
                    <label class="form-label">Evento / Destino</label>
                    <input type="text" name="evento" class="form-control" value="{{ $movimiento->evento }}">
                </div>

                <!-- Observaciones -->
                <div class="mb-3 entrada-campos d-none">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ $movimiento->observaciones }}</textarea>
                </div>
            </div>

            <!-- Columna 2 -->
            <div class="col-md-6">

                <!-- Fecha -->
                <div class="mb-3 entrada-campos salida-campos d-none">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control" value="{{ $movimiento->fecha }}" required>
                </div>

                <div class="mb-3 entrada-campos d-none">
                    <label class="form-label">Lote</label>
                    <input type="text" name="lote" class="form-control" value="{{ $movimiento->lote }}">
                </div>


                <div class="mb-3 entrada-campos d-none">
                    <label class="form-label">Fecha de Vencimiento</label>
                    <input type="date" name="fecha_vencimiento" class="form-control" value="{{ $movimiento->fecha_vencimiento }}">
                </div>

                <div class="mb-3 salida-campos d-none">
                    <label class="form-label">Solicitado por</label>
                    <select name="solicitante_id" class="form-select">
                        <option value="">Seleccione</option>
                        @foreach($solicitantes as $solicitante)
                        <option value="{{ $solicitante->id }}" {{ $movimiento->solicitante_id == $solicitante->id ? 'selected' : '' }}>
                            {{ $solicitante->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 salida-campos d-none">
                    <label class="form-label">Responsable</label>
                    <input type="text" name="responsable" class="form-control" value="{{ $movimiento->responsable }}">
                </div>

                <div class="mb-3 descarte-campos d-none">
                    <label class="form-label">Motivo</label>
                    <input type="text" name="motivo" class="form-control" value="{{ $movimiento->motivo }}">
                </div>
            </div>

        </div>
</div>

<div class="text-end mt-4">
    <button type="submit" class="btn btn-primary">Actualizar Movimiento</button>
</div>
</form>

<div class="card">
    <h5 class="card-header">INVENTARIO</h5>
    <div class="mb-3">
        <button type="button" class="btn btn-outline-secondary btn-sm filtro-movimiento active" data-tipo="">Todos</button>
        <button type="button" class="btn btn-outline-success btn-sm filtro-movimiento" data-tipo="Entrada">Entrada</button>
        <button type="button" class="btn btn-outline-danger btn-sm filtro-movimiento" data-tipo="Salida">Salida</button>
        <button type="button" class="btn btn-outline-warning btn-sm filtro-movimiento" data-tipo="Descarte">Descarte</button>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-striped" id="tabla">
            <thead>
                <tr class="table-dark">
                    <th class="col-entrada col-salida col-descarte">Clasificación</th>
                    <th class="col-entrada col-salida col-descarte">Producto</th>
                    <th class="col-entrada col-salida col-descarte">Cantidad</th>
                    <th class="col-entrada">Observaciones</th>
                    <th class="col-entrada col-salida col-descarte">Fecha</th>
                    <th class="col-salida">Solicitado por</th>
                    <th class="col-entrada">Fecha de Vencimiento</th>
                    <th class="col-entrada col-salida col-descarte">E/S/D</th>
                    <th class="col-salida">Responsable</th>
                    <th class="col-salida">Evento</th>
                    <th class="col-descarte">Motivo</th>
                    <th class="col-entrada">Lote</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($movimientos as $mov)
                <tr class="fila-movimiento" data-tipo="{{ $mov->tipo_movimiento }}">
                    <td class="col-entrada col-salida col-descarte">{{ $mov->clasificacion->nombre ?? '' }}</td>
                    <td class="col-entrada col-salida col-descarte">{{ $mov->producto->nombre ?? '' }}</td>
                    <td class="col-entrada col-salida col-descarte">{{ $mov->cantidad }}</td>
                    <td class="col-entrada">{{ $mov->observaciones }}</td>
                    <td class="col-entrada col-salida col-descarte">{{ $mov->fecha }}</td>
                    <td class="col-salida">{{ $mov->solicitante->nombre ?? '' }}</td>
                    <td class="col-entrada">{{ $mov->fecha_vencimiento }}</td>
                    <td class="col-entrada col-salida col-descarte">
                        @if ($mov->tipo_movimiento === 'Entrada')
                        <span class="badge bg-success">Entrada</span>
                        @elseif ($mov->tipo_movimiento === 'Descarte')
                        <span class="badge bg-warning">Descarte</span>
                        @elseif ($mov->tipo_movimiento === 'Salida')
                        <span class="badge bg-danger">Salida</span>
                        @endif
                    </td>
                    <td class="col-salida">{{ $mov->responsable }}</td>
                    <td class="col-salida">{{ $mov->evento }}</td>
                    <td class="col-descarte">{{ $mov->motivo }}</td>
                    <td class="col-entrada">{{ $mov->lote }}</td>
                    <td>
                        <a href="{{ route('movimiento.edit', $mov->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('movimiento.destroy', $mov->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estas seguro de que desea eliminar este movimiento?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- JavaScript para alternar campos -->
<script>
    $(document).ready(function() {
        $('#tabla').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const tipoRegistro = document.getElementById('tipoRegistro');
        if (tipoRegistro && tipoRegistro.value) {
            const tipos = ['entrada', 'salida', 'descarte'];
            tipos.forEach(tipo => {
                document.querySelectorAll(`.${tipo}-campos`).forEach(el => el.classList.add('d-none'));
            });
            const seleccionado = tipoRegistro.value.toLowerCase();
            document.querySelectorAll(`.${seleccionado}-campos`).forEach(el => el.classList.remove('d-none'));
        }

        tipoRegistro.addEventListener('change', function() {
            const tipos = ['entrada', 'salida', 'descarte'];
            tipos.forEach(tipo => {
                document.querySelectorAll(`.${tipo}-campos`).forEach(el => el.classList.add('d-none'));
            });
            if (this.value) {
                const seleccionado = this.value.toLowerCase();
                document.querySelectorAll(`.${seleccionado}-campos`).forEach(el => el.classList.remove('d-none'));
            }
        });

        // Si quieres actualizar productos por clasificación, agrega el mismo código que en create
        const clasificacionSelect = document.getElementById('clasificacionSelect');
        const productoSelect = document.getElementById('productoSelect');
        if (clasificacionSelect && productoSelect) {
            clasificacionSelect.addEventListener('change', function() {
                const clasificacionId = this.value;
                productoSelect.innerHTML = '<option value="" disabled selected>Cargando...</option>';
                fetch(`/productos/por-clasificacion/${clasificacionId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="" disabled selected>Seleccione un producto</option>';
                        data.forEach(producto => {
                            options += `<option value="${producto.id}">${producto.nombre}</option>`;
                        });
                        productoSelect.innerHTML = options;
                    });
            });
        }

        // Mapeo de columnas por tipo
        const columnasPorTipo = {
            'Entrada': ['col-entrada'],
            'Salida': ['col-salida'],
            'Descarte': ['col-descarte'],
        };

        // Función para aplicar filtros
        function aplicarFiltro(tipo) {
            // Actualizar botones activos
            document.querySelectorAll('.filtro-movimiento').forEach(b => {
                b.classList.remove('active');
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-secondary', 'btn-outline-success', 'btn-outline-danger', 'btn-outline-warning');
            });
            
            const botonActivo = document.querySelector(`[data-tipo="${tipo}"]`);
            if (botonActivo) {
                botonActivo.classList.add('active', 'btn-primary');
                botonActivo.classList.remove('btn-outline-secondary', 'btn-outline-success', 'btn-outline-danger', 'btn-outline-warning');
            }

            // Mostrar/ocultar columnas
            const mostrar = columnasPorTipo[tipo] || ['col-entrada', 'col-salida', 'col-descarte'];
            
            // Ocultar todas las columnas específicas
            document.querySelectorAll('th, td').forEach(el => {
                if (el.className.match(/col-(entrada|salida|descarte)/)) {
                    el.style.display = 'none';
                }
            });
            
            // Mostrar las columnas del tipo seleccionado
            mostrar.forEach(clase => {
                document.querySelectorAll('.' + clase).forEach(el => el.style.display = '');
            });

            // Filtrar filas
            document.querySelectorAll('.fila-movimiento').forEach(function(row) {
                if (!tipo || row.getAttribute('data-tipo') === tipo) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Event listeners para los botones de filtro
        document.querySelectorAll('.filtro-movimiento').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const tipo = this.getAttribute('data-tipo');
                aplicarFiltro(tipo);
            });
        });

        // Al cargar, aplicar filtro "Todos"
        aplicarFiltro('');
    });
</script>
@endsection
