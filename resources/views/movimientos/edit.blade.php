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
                <option value="Certificados" {{ $movimiento->tipo_movimiento == 'Certificados' ? 'selected' : '' }}>Certificados</option>
            </select>
            <input type="hidden" name="tipo_movimiento" value="{{ $movimiento->tipo_movimiento }}">
        </div>

        <div class="row">
            <div class="col-md-6">

                <div class="mb-3 entrada-campos salida-campos d-none">
                    <label class="form-label">Clasificación</label>
                    <select name="clasificacion_id" class="form-select" id="clasificacionSelect" required>
                        @foreach($clasificaciones as $clasificacion)
                        <option value="{{ $clasificacion->id }}" {{ $movimiento->clasificacion_id == $clasificacion->id ? 'selected' : '' }}>
                            {{ $clasificacion->nombre }}
                        </option>
                        @endforeach
                    </select>
                    <label class="form-label mt-2">Producto</label>
                    <select name="producto_id" class="form-select" id="productoSelect" required>
                        @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" {{ $movimiento->producto_id == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Cantidad -->
                <div class="mb-3 entrada-campos salida-campos descarte-campos certificados-campos d-none">
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
                    <td>{{ $mov->responsable }}</td>
                    <td>{{ $mov->tipo_movimiento }}</td>
                    <td>
                        @if ($mov->tipo_movimiento === 'Entrada')
                        <span class="badge bg-success">E</span>
                        @else
                        <span class="badge bg-danger">S</span>
                        @endif
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
    document.addEventListener('DOMContentLoaded', function() {
        const tipoRegistro = document.getElementById('tipoRegistro');
        const clasificacionSelect = document.getElementById('clasificacionSelect');
        const productoSelect = document.getElementById('productoSelect');

        tipoRegistro.addEventListener('change', function() {
            const tipos = ['entrada', 'salida', 'descarte', 'certificados'];
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

        if (tipoRegistro && tipoRegistro.value) {
            const tipos = ['entrada', 'salida', 'descarte', 'certificados'];
            tipos.forEach(tipo => {
                document.querySelectorAll(`.${tipo}-campos`).forEach(el => el.classList.add('d-none'));
            });
            const seleccionado = tipoRegistro.value.toLowerCase();
            document.querySelectorAll(`.${seleccionado}-campos`).forEach(el => el.classList.remove('d-none'));
        }
    });
</script>
@endsection