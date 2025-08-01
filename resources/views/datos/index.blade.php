@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <h2 class="mb-4">Dashboard de Inventario</h2>
    @if(session('mensaje2'))
    <div class="alert alert-danger">
        {{ session('mensaje2') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4 g-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card text-white bg-primary h-100 shadow-sm card-hover">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-boxes fa-3x mb-2"></i>
                    <h4 class="card-title mb-0">{{ $totalProductos }}</h4>
                    <p class="card-text">Total Productos</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card text-white bg-danger h-100 shadow-sm card-hover">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                    <h4 class="card-title mb-0">{{ $productosNecesitanReabastecimiento }}</h4>
                    <p class="card-text">Stock Crítico</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card text-white bg-success h-100 shadow-sm card-hover">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-certificate fa-3x mb-2"></i>
                    <h4 class="card-title mb-0">{{ $stockCertificados ?? 0 }}</h4>
                    <p class="card-text">Certificados Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card text-white bg-info h-100 shadow-sm card-hover">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-handshake fa-3x mb-2"></i>
                    <h4 class="card-title mb-0">{{ $certificadosUsados ?? 0 }}</h4>
                    <p class="card-text">Certificados Usados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de paneles principales en filas separadas -->
    <div class="row g-4">
        <!-- Productos en Stock Crítico (fila completa) -->
        <div class="col-12">
            <div class="card h-100 shadow-sm ">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Productos en Stock Crítico</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($productosCriticos as $producto)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card card-critico shadow-sm h-100 card-hover">
                                <span class="badge-critico">Crítico</span>
                                <div class="card-body text-center">
                                    @if($producto->imagen)
                                        <img src="{{ asset('storage/'.$producto->imagen) }}" class="producto-img" alt="Imagen de {{ $producto->nombre }}">
                                    @else
                                        <img src="https://via.placeholder.com/64x64?text=Sin+Imagen" class="producto-img" alt="Sin imagen">
                                    @endif
                                    <h6 class="card-title text-danger fw-bold mb-1">{{ $producto->nombre }}</h6>
                                    <small class="text-muted d-block mb-2">{{ $producto->clasificacion->nombre ?? 'N/A' }}</small>
                                    <div class="row justify-content-center mb-0">
                                        <div class="col-6 text-center">
                                            <i class="fas fa-box text-danger"></i>
                                            <div class="fw-bold text-danger">{{ $producto->stock_actual }}</div>
                                            <small class="text-muted">Stock Actual</small>
                                        </div>
                                        <div class="col-6 text-center">
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                            <div class="fw-bold">{{ $producto->stock_minimo }}</div>
                                            <small class="text-muted">Stock Mínimo</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-light p-2">
                                    <a href="{{ route('movimiento.create', ['producto_id' => $producto->id]) }}" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="fas fa-plus"></i> Registrar Entrada
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-success text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h5>¡Excelente!</h5>
                                <p class="mb-0">No hay productos en stock crítico.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Fila 1: Últimos Certificados Usados y Productos más utilizados -->
<div class="row mt-4 g-4">
    <div class="col-12 col-lg-6">
        <!-- Productos Vencidos y Próximos a Vencer -->
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Productos Vencidos y Próximos a Vencer</h5>
            </div>
            <div class="card-body">
                <!-- Productos Vencidos -->
                @if($productosVencidos->count() > 0)
                <div class="mb-3">
                    <h6 class="text-danger fw-bold mb-2">
                        <i class="fas fa-times-circle"></i> Productos Vencidos ({{ $productosVencidos->count() }})
                    </h6>
                    @foreach($productosVencidos->take(3) as $producto)
                    @php
                        $movimientoVencido = \App\Models\Movimiento::where('producto_id', $producto->id)
                            ->whereNotNull('fecha_vencimiento')
                            ->where('fecha_vencimiento', '<', \Carbon\Carbon::now())
                            ->where('tipo_movimiento', 'Entrada')
                            ->latest('fecha_vencimiento')
                            ->first();
                    @endphp
                    <div class="d-flex align-items-center mb-2 p-2 border border-danger rounded bg-danger bg-opacity-10">
                        <div class="me-3">
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold text-danger">{{ $producto->nombre }}</h6>
                            <p class="mb-1 text-muted">
                                <i class="fas fa-box"></i> Stock: {{ $producto->stock_actual }}
                            </p>
                            <p>Lote: {{ $producto->lote }}</p>
                            <small class="text-danger fw-bold">
                                <i class="fas fa-calendar-times"></i> Vencido: {{ $movimientoVencido ? \Carbon\Carbon::parse($movimientoVencido->fecha_vencimiento)->format('d/m/Y') : 'N/A' }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                    @if($productosVencidos->count() > 3)
                    <div class="text-center">
                        <small class="text-muted">Y {{ $productosVencidos->count() - 3 }} productos más vencidos</small>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Productos Próximos a Vencer -->
                @if($productosProximosVencer->count() > 0)
                <div class="mb-3">
                    <h6 class="text-warning fw-bold mb-2">
                        <i class="fas fa-clock"></i> Próximos a Vencer ({{ $productosProximosVencer->count() }})
                    </h6>
                    @foreach($productosProximosVencer->take(3) as $producto)
                    @php
                        $movimientoProximo = \App\Models\Movimiento::where('producto_id', $producto->id)
                            ->whereNotNull('fecha_vencimiento')
                            ->where('fecha_vencimiento', '>=', \Carbon\Carbon::now())
                            ->where('fecha_vencimiento', '<=', \Carbon\Carbon::now()->addDays(30))
                            ->where('tipo_movimiento', 'Entrada')
                            ->orderBy('fecha_vencimiento')
                            ->first();
                    @endphp
                    <div class="d-flex align-items-center mb-2 p-2 border border-warning rounded bg-warning bg-opacity-10">
                        <div class="me-3">
                            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold text-warning">{{ $producto->nombre }}</h6>
                            <p class="mb-1 text-muted">
                                <i class="fas fa-box"></i> Stock: {{ $producto->stock_actual }}
                            </p>
                            <small class="text-warning fw-bold">
                                <i class="fas fa-calendar"></i> Vence: {{ $movimientoProximo ? \Carbon\Carbon::parse($movimientoProximo->fecha_vencimiento)->format('d/m/Y') : 'N/A' }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                    @if($productosProximosVencer->count() > 3)
                    <div class="text-center">
                        <small class="text-muted">Y {{ $productosProximosVencer->count() - 3 }} productos más próximos a vencer</small>
                    </div>
                    @endif
                </div>
                @endif

                @if($productosVencidos->isEmpty() && $productosProximosVencer->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h6 class="text-success">¡Excelente!</h6>
                    <p class="text-muted mb-0">No hay productos vencidos ni próximos a vencer</p>
                </div>
                @endif

                @if($productosVencidos->count() > 0 || $productosProximosVencer->count() > 0)
                <div class="text-center mt-3">
                    <a href="{{ route('producto.create') }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-eye"></i> Ver Todos los Productos
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <!-- Productos más utilizados -->
        @if($productosMasUsados->count())
        <div class="card shadow-sm h-100">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-trophy"></i> Productos más utilizados</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Total Usados</th>
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
</div>

<!-- Fila 2: Estado de Certificados y Últimos Movimientos -->
<div class="row mt-4 g-4">
    <div class="col-12 col-lg-6">
        <!-- Estado de Certificados -->
        <div class="card border-success mb-3 shadow-sm h-100">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-certificate"></i> Estado de Certificados</h6>
            </div>
            <div class="card-body py-4">
                <div class="row text-center align-items-center">
                    <div class="col-6">
                        <div class="display-4 fw-bold text-success mb-1" style="font-size:2.8rem;">{{ $stockCertificados ?? 0 }}</div>
                        <div class="fs-5 text-dark">Disponibles</div>
                    </div>
                    <div class="col-6">
                        <div class="display-4 fw-bold text-warning mb-1" style="font-size:2.8rem;">{{ $certificadosUsados ?? 0 }}</div>
                        <div class="fs-5 text-dark">Usados</div>
                    </div>
                </div>
                @if(($stockCertificados ?? 0) < 50)
                <div class="alert alert-warning mt-3 mb-0 py-2 fs-6">
                    <i class="fas fa-exclamation-triangle"></i> Stock bajo de certificados
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <!-- Últimos Movimientos -->
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-history"></i> Últimos Movimientos</h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($ultimosMovimientos as $movimiento)
                <div class="d-flex align-items-center mb-2 p-2 border-bottom">
                    <div class="me-2">
                        @if($movimiento->tipo_movimiento === 'Entrada')
                            <i class="fas fa-arrow-down text-success"></i>
                        @elseif($movimiento->tipo_movimiento === 'Salida')
                            <i class="fas fa-arrow-up text-danger"></i>
                        @else
                            <i class="fas fa-trash text-warning"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <small class="fw-bold">{{ $movimiento->producto->nombre ?? 'N/A' }}</small>
                        <br>
                        <small class="text-muted">{{ $movimiento->tipo_movimiento }} - {{ $movimiento->cantidad }}</small>
                    </div>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($movimiento->created_at)->format('d/m') }}</small>
                </div>
                @empty
                <p class="text-muted text-center">No hay movimientos recientes</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
    
</div>

@endsection
