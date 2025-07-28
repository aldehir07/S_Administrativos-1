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
                <div class="col-md-3">
                    <label class="form-label mb-1">Desde</label>
                    <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Hasta</label>
                    <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
                </div>
                <div class="col-md-3">
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

    

    <!-- Tabla de resultados -->
    <div class="card mt-5">
        <div class="card-header" style="background:#3177bf">
            <h4 class="card-tittle mb-0 text-white"> <i class="fas fa-list"></i> Movimientos encontrados</h4>
        </div>
        
                <table class="table table-striped table-hover" id="tabla">
                    <thead >
                        <tr class="table-info">
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Tipo</th>
                            <th>Responsable</th>
                            <th>Evento/Destino</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $mov)
                        <tr>
                            <td>{{ $mov->fecha }}</td>
                            <td>{{ $mov->producto->nombre ?? '-' }}</td>
                            <td>{{ $mov->cantidad }}</td>
                            <td>
                                @if($mov->tipo_movimiento == 'Entrada')
                                    <span class="badge bg-success">Entrada</span>
                                @elseif($mov->tipo_movimiento == 'Salida')
                                    <span class="badge bg-danger">Salida</span>
                                @else
                                    <span class="badge bg-warning text-dark">Descarte</span>
                                @endif
                            </td>
                            <td>{{ $mov->responsable ?? '-' }}</td>
                            <td>{{ $mov->evento ?? '-' }}</td>
                            <td>{{ $mov->observaciones ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay movimientos para los filtros seleccionados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            
        
    </div>

    <!-- Ranking de productos más utilizados -->
    @if($productosMasUsados->count())
        <div class="card shadow-sm">
            <div class="card-header text-white" style="background:#3177bf">
                <i class="fas fa-trophy"></i> Productos más utilizados {{ request('desde') || request('hasta') ? 'en el periodo seleccionado' : 'históricamente' }}
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Total Usado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productosMasUsados as $item)
                                <tr>
                                    <td>{{ $item->producto->nombre ?? '-' }}</td>
                                    <td>{{ $item->total_usada }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection