@extends('layouts.app')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-tittle bg-warning text-dark">

        </div>
    </div>
    <h2 class="mb-4">Registrar Movimiento de Inventario</h2>
    @if(session('alerta_stock'))
    <div style="position:fixed;top:0;left:0;width:100%;z-index:9999;" class="bg-warning text-dark text-center py-3 fw-bold fs-5 shadow">
        <i class="fas fa-exclamation-triangle"></i>
        {{ session('alerta_stock') }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('movimiento.store') }}">
        @csrf

        <!-- Tipo de Registro -->
        <div class="mb-4">
            <label class="form-label fw-bold">Tipo de Registro</label>
            <select name="tipo_movimiento" class="form-select" id="tipoRegistro" required>
                <option value="" {{ !old('tipo_movimiento') && !isset($producto_id) ? 'selected' : '' }}>Seleccione</option>
                <option value="Entrada"
                    {{ (old('tipo_movimiento') == 'Entrada' || isset($producto_id)) ? 'selected' : '' }}>
                    Entrada</option>
                <option value="Salida" {{ old('tipo_movimiento') == 'Salida' ? 'selected' : '' }}>Salida</option>
                <option value="Descarte" {{ old('tipo_movimiento') == 'Descarte' ? 'selected' : '' }}>Descarte</option>
            </select>
        </div>

        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">

                <!-- Clasificación -->
                <div class="mb-3 entrada-campos salida-campos descarte-campos d-none">
                    <label class="form-label">Clasificación</label>
                    <select name="clasificacion_id" id="clasificacionSelect" class="form-select" required>
                        <option value="" disabled>Seleccione</option>
                        @foreach ($clasificaciones as $clasificacion)
                        <option value="{{ $clasificacion->id }}"
                            {{ (isset($producto_id) && $productos->find($producto_id)?->clasificacion_id == $clasificacion->id) ? 'selected' : '' }}>
                            {{ $clasificacion->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Productos dinámicos para Salida -->
                <div class="mb-3 salida-campos d-none" id="productosSalidaContainer">
                    <label class="form-label">Productos y Cantidades</label>
                    <div id="productosSalidaRows">
                        <div class="row mb-2 producto-salida-row">
                            <div class="col-8">
                                <select name="productos_salida[]" class="form-select producto-salida-select">
                                    <option value="" disabled {{ !isset($producto_id) ? 'selected' : '' }}>Seleccione un producto</option>
                                    @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}" {{ (isset($producto_id) && $producto_id == $producto->id) ? 'selected' : '' }}>
                                        {{ $producto->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="cantidades_salida[]" class="form-control" min="1" placeholder="Cantidad">
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-sm remove-producto-salida" title="Quitar">
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="agregarProductoSalida">Agregar otro producto</button>
                </div>

                <!-- Producto para Entrada, Descarte -->
                <div class="mb-3 entrada-campos descarte-campos d-none">
                    <label class="form-label">Producto</label>
                    <select name="producto_id" id="productoSelectUnico" class="form-select">
                        <option value="" disabled selected>Seleccione un producto</option>
                        @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cantidad -->
                <div class="mb-3 entrada-campos descarte-campos  d-none">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" min="1">
                </div>

                <!-- Evento/Destino -->
                <div class="mb-3 salida-campos  d-none">
                    <label class="form-label">Evento / Destino</label>
                    <input type="text" name="evento" class="form-control">
                </div>

                <!-- Observaciones -->
                <div class="mb-3 entrada-campos d-none">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-md-6">

                <!-- Fecha -->
                <div class="mb-3 entrada-campos salida-campos descarte-campos d-none">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>

                <!-- Lote -->
                <div class="mb-3 entrada-campos d-none">
                    <label class="form-label">Lote</label>
                    <input type="text" name="lote" class="form-control">
                </div>

                <!-- Fecha de Vencimiento -->
                <div class="mb-3 entrada-campos d-none">
                    <label class="form-label">Fecha de Vencimiento</label>
                    <input type="date" name="fecha_vencimiento" class="form-control">
                </div>

                <!-- Solicitado Por -->
                <div class="mb-3 salida-campos  d-none">
                    <label class="form-label">Solicitado por</label>
                    <select name="solicitante_id" class="form-select">
                        <option value="" disabled selected>Seleccione</option>
                        @foreach($solicitantes as $solicitante)
                        <option value="{{ $solicitante->id }}">{{ $solicitante->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Responsable -->
                <div class="mb-3 salida-campos d-none">
                    <label class="form-label">Responsable</label>
                    <select name="responsable" class="form-select">
                        <option value="" disabled selected>Seleccione</option>
                        <option value="Arline Tuñon">Arline Tuñon</option>
                        <option value="Luis Urriola">Luis Urriola</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <!-- Motivo de Descarte -->
                <div class="mb-3 descarte-campos d-none">
                    <label class="form-label">Motivo de Descarte</label>
                    <select name="motivo" class="form-select">
                        <option value="" disabled selected>Seleccione</option>
                        <option value="Dañado">Dañado</option>
                        <option value="Vencido">Vencido</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Registrar Movimiento</button>
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
            <table class="table" id="tabla">
                <thead>
                    <tr>
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
                        <td>
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
        const clasificacionSelect = document.getElementById('clasificacionSelect');
        const productoSelect = document.getElementById('productoSelect');
        const productosSalidaContainer = document.getElementById('productosSalidaContainer');

        function toggleProductosSalida() {
            if (tipoRegistro.value === 'Salida') {
                productosSalidaContainer.classList.remove('d-none');
                // Agrega required a los campos de Salida
                document.querySelectorAll('.producto-salida-select').forEach(el => el.setAttribute('required', 'required'));
                document.querySelectorAll('input[name="cantidades_salida[]"]').forEach(el => el.setAttribute('required', 'required'));
            } else {
                productosSalidaContainer.classList.add('d-none');
                // Quita required a los campos de Salida
                document.querySelectorAll('.producto-salida-select').forEach(el => el.removeAttribute('required'));
                document.querySelectorAll('input[name="cantidades_salida[]"]').forEach(el => el.removeAttribute('required'));
            }
        }

        tipoRegistro.addEventListener('change', toggleProductosSalida);
        toggleProductosSalida(); // Ejecutar al cargar

        // Cuando agregues una nueva fila, también debes actualizar su select de producto
        document.getElementById('agregarProductoSalida').addEventListener('click', function() {
            const row = document.querySelector('.producto-salida-row').cloneNode(true);
            row.querySelector('select').value = '';
            row.querySelector('input').value = '';
            document.getElementById('productosSalidaRows').appendChild(row);

            // Actualiza el select de producto de la nueva fila según la clasificación seleccionada
            const clasificacionId = clasificacionSelect.value;
            const productoSelect = row.querySelector('.producto-salida-select');
            if (clasificacionId) {
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
            }
        });

        document.getElementById('productosSalidaRows').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-producto-salida')) {
                const rows = document.querySelectorAll('.producto-salida-row');
                if (rows.length > 1) {
                    e.target.closest('.producto-salida-row').remove();
                }
            }
        });

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

        clasificacionSelect.addEventListener('change', function() {
            const clasificacionId = this.value;

            // Actualiza los selects de productos dinámicos (Salida)
            document.querySelectorAll('.producto-salida-select').forEach(function(productoSelect) {
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

            // Actualiza el select de producto único (Entrada, Descarte)
            const productoUnicoSelect = document.getElementById('productoSelectUnico');
            if (productoUnicoSelect) {
                productoUnicoSelect.innerHTML = '<option value="" disabled selected>Cargando...</option>';
                fetch(`/productos/por-clasificacion/${clasificacionId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="" disabled selected>Seleccione un producto</option>';
                        data.forEach(producto => {
                            options += `<option value="${producto.id}">${producto.nombre}</option>`;
                        });
                        productoUnicoSelect.innerHTML = options;
                    });
            }
        });

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

        // Al cargar, si hay producto_id (viene desde el card), muestra los campos de Entrada automáticamente
        @if(isset($producto_id))
        //Forzar seleccion y mostrar campos de ENtrada
        tipoRegistro.value = 'Entrada';
        tipoRegistro.dispatchEvent(new Event('change'));
        @endif
    });
</script>
@endsection
