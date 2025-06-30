@extends('layouts.app')
@section('content')
<div class="container">
    <h2 class="mb-4">Registrar Movimiento de Inventario</h2>

    <form method="POST" action="{{ route('movimiento.store') }}">
        @csrf

        <!-- Tipo de Registro -->
        <div class="mb-4">
            <label class="form-label fw-bold">Tipo de Registro</label>
            <select name="registro" class="form-select" id="tipoRegistro" required>
                <option value="" disabled selected>Seleccione</option>
                <option value="Entrada">Entrada</option>
                <option value="Salida">Salida</option>
                <option value="Descarte">Descarte</option>
                <option value="Certificados">Certificados</option>
            </select>
        </div>

        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">

                <!-- Clasificación -->
                <div class="mb-3 entrada-campos salida-campos descarte-campos d-none">
                    <label class="form-label">Clasificación</label>
                    <select name="clasificacion_id" id="clasificacionSelect" class="form-select" required>
                        <option value="" disabled selected>Seleccione</option>
                        @foreach ($clasificaciones as $clasificacion)
                            <option value="{{ $clasificacion->id }}">{{ $clasificacion->nombre }}</option>
                        @endforeach
                    </select>
                    <select name="producto_id" id="productoSelect" class="form-select" required>
                        <option value="" disabled selected>Seleccione un producto</option>
                    </select>

                </div>

                <!-- Cantidad -->
                <div class="mb-3 entrada-campos salida-campos descarte-campos certificados-campos d-none">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" min="1">
                </div>

                <!-- Evento/Destino -->
                <div class="mb-3 entrada-campos salida-campos d-none">
                    <label class="form-label">Evento / Destino</label>
                    <input type="text" name="evento" class="form-control">
                </div>

                <!-- Observaciones -->
                <div class="mb-3 descarte-campos d-none">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-md-6">

                <!-- Fecha -->
                <div class="mb-3">
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
                <div class="mb-3 salida-campos certificados-campos d-none">
                    <label class="form-label">Solicitado por</label>
                    <select name="solicitante_id" class="form-select" required>
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
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha Entrega</th>
                    <th>Solicitante</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Vencimiento</th>
                    <th>Entregado por</th>
                    <th>Registro</th>
                    <th>E/S</th>
                    <th>Acciones</th>

                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($movimientos as $mov)
                    <tr>
                        <td>{{ $mov->fecha }}</td>
                        <td>{{ $mov->solicitante->nombre ?? '' }}</td>
                        <td>{{ $mov->producto->nombre ?? '' }}</td>
                        <td>{{ $mov->cantidad }}</td>
                        <td>{{ $mov->fecha_vencimiento }}</td>
                        <td>{{ $mon->responsable }}</td>
                        <td>{{ $mov->tipo_movimiento }}</td>
                        <td>
                            @if ($mov->tipo_movimiento === 'Entrada')
                                <span class="badge bg-success">E</span>
                            @else
                                <span class="badge bg-danger">S</span>
                            @endif
                        </td>
                        <td>
                            <!-- Acciones de Editar y eliminar -->
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
    document.addEventListener('DOMContentLoaded', function () {
        const tipoRegistro = document.getElementById('tipoRegistro');
        const clasificacionSelect = document.getElementById('clasificacionSelect');
        const productoSelect = document.getElementById('productoSelect');
        
        tipoRegistro.addEventListener('change', function () {
            const tipos = ['entrada', 'salida', 'descarte', 'certificados'];
            tipos.forEach(tipo => {
                document.querySelectorAll(`.${tipo}-campos`).forEach(el => el.classList.add('d-none'));
            });

            if (this.value) {
                const seleccionado = this.value.toLowerCase();
                document.querySelectorAll(`.${seleccionado}-campos`).forEach(el => el.classList.remove('d-none'));
            }
        });

        clasificacionSelect.addEventListener('change', function () {
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
    });
</script>
@endsection