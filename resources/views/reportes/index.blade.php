@extends('layouts.app')
@section('content')
<div class="container-fluid">


    <div class="card">
        <div class="card-header" style="background:#082140;">
            <h2 class="card-tittle mb-0 text-white"><i class="fas fa-chart-bar"></i> Reporte de Movimientos</h2>
        </div>

        <div class="card-body">
            <!-- Filtros -->
            <form method="GET" action="{{ route('reportes.index') }}" class="row g-3 mb-4 align-items-end shadow-sm p-3 bg-white rounded">
                <div class="col-md-2">
                    <label class="form-label mb-1">Desde</label>
                    <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1">Hasta</label>
                    <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label mb-1">Clasificación</label>
                    <select name="clasificacion" class="form-select">
                        <option value="">Todas</option>
                        @foreach($clasificaciones as $clasificacion)
                            <option value="{{ $clasificacion->clasificacion_id }}" {{ request('clasificacion') == $clasificacion->clasificacion_id ? 'selected' : '' }}>
                                {{ \App\Models\Clasificacion::find($clasificacion->clasificacion_id)->nombre ?? 'Sin nombre' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label mb-1">Producto</label>
                    <select name="producto_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" {{ request('producto_id') == $producto->id ? 'selected' : '' }}>
                                {{ $producto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1">Tipo de Movimiento</label>
                    <select name="tipo_movimiento" class="form-select">
                        <option value="">Todos</option>
                        <option value="Entrada" {{ request('tipo_movimiento') == 'Entrada' ? 'selected' : '' }}>Entrada</option>
                        <option value="Salida" {{ request('tipo_movimiento') == 'Salida' ? 'selected' : '' }}>Salida</option>
                        <option value="Descarte" {{ request('tipo_movimiento') == 'Descarte' ? 'selected' : '' }}>Descarte</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Botones para Exportar a Excel y PDF -->
    <form id="exportarPdfForm" method="POST" action="{{ route('reportes.exportar-pdf-seleccionados') }}" target="_blank" class="d-inline">
        @csrf
        <input type="hidden" name="movimientos_ids" id="movimientosIdsInput">
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf"></i> Exportar seleccionados a PDF
        </button>
    </form>

    <!-- Tabla de resultados -->
    <div class="card mt-3">
        <div class="card-header" style="background:#3177bf">
            <h4 class="card-tittle mb-0 text-white"> <i class="fas fa-list"></i> Movimientos encontrados</h4>
        </div>

            <table class="table table-striped table-hover" id="tabla">
                <thead >
                    <tr class="table-info">
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Tipo</th>
                        <th>Responsable</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $mov)
                    <tr>
                        <td>
                            <input type="checkbox" name="movimientos_seleccionados[]" value="{{ $mov->id }}" class="movimiento-checkbox">
                        </td>
                        <td>{{ $mov->fecha }}</td>
                        <td>{{ $mov->producto->nombre ?? '-' }}</td>
                        <td>{{ $mov->cantidad }}</td>
                        <td>
                             @if($mov->tipo_movimiento == 'Entrada')
                                <span class="badge bg-success">Entrada</span>
                            @elseif($mov->tipo_movimiento == 'Salida')
                                <span class="badge bg-danger">Salida</span>
                            @elseif($mov->tipo_movimiento == 'Certificado')
                                <span class="badge bg-info text-dark">Certificado</span>
                            @else
                                <span class="badge bg-warning text-dark">Descarte</span>
                            @endif
                        </td>
                        <td>{{ $mov->responsable->nombre ?? '-' }}</td>
                        <td>{{ $mov->observaciones ?? '-' }}</td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay movimientos para los filtros seleccionados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>


</div>
<script>
    document.getElementById('exportarPdfForm').addEventListener('submit', function(e) {
        const ids = Array.from(document.querySelectorAll('.movimiento-checkbox:checked')).map(cb => cb.value);
        document.getElementById('movimientosIdsInput').value = ids.join(',');
        if (ids.length === 0) {
            e.preventDefault();
            alert('Seleccione al menos un movimiento para imprimir.');
        }
    });

    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.movimiento-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
