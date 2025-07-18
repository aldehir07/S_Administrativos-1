@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <h2 class="mb-4">Dashboard de Inventario</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4 g-3">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card text-white bg-primary h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-boxes fa-3x mb-2"></i>
                    <h4 class="card-title mb-0">{{ $totalProductos }}</h4>
                    <p class="card-text">Total Productos</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card text-white bg-danger h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                    <h4 class="card-title mb-0">{{ $productosNecesitanReabastecimiento }}</h4>
                    <p class="card-text">Stock Crítico</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card text-white bg-success h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-certificate fa-3x mb-2"></i>
                    <h4 class="card-title mb-0">{{ $stockCertificados ?? 0 }}</h4>
                    <p class="card-text">Certificados Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card text-white bg-info h-100 shadow-sm">
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
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Productos en Stock Crítico</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($productosCriticos as $producto)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card border-danger shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        @if($producto->imagen)
                                        <img src="{{ asset('storage/'.$producto->imagen) }}" class="rounded me-2" alt="Imagen de {{ $producto->nombre }}" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                        <img src="https://via.placeholder.com/50x50?text=Sin+Imagen" class="rounded me-2" alt="Sin imagen">
                                        @endif
                                        <div>
                                            <h6 class="card-title text-danger fw-bold mb-0">{{ $producto->nombre }}</h6>
                                            <small class="text-muted">{{ $producto->clasificacion->nombre ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="border-end">
                                                <small class="text-muted d-block">Stock Actual</small>
                                                <span class="fw-bold text-danger">{{ $producto->stock_actual }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="border-end">
                                                <small class="text-muted d-block">Stock Mínimo</small>
                                                <span class="fw-bold">{{ $producto->stock_minimo }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted d-block">Faltan</small>
                                            <span class="fw-bold text-danger">{{ max(0, $producto->stock_minimo - $producto->stock_actual) }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress" style="height: 8px;">
                                            @php
                                                $porcentaje = $producto->stock_minimo > 0 ? ($producto->stock_actual / $producto->stock_minimo) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-danger" style="width: {{ min(100, $porcentaje) }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ number_format($porcentaje, 1) }}% del mínimo</small>
                                    </div>
                                </div>
                                <div class="card-footer bg-light p-2">
                                    <a href="{{ route('movimiento.create', ['producto_id' => $producto->id]) }}" class="btn btn-sm btn-primary w-100">
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
        <!-- Últimos Certificados Usados (fila completa) -->
        <div class="col-12">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-certificate"></i> Últimos Certificados Usados</h5>
                </div>
                <div class="card-body">
                    @forelse($ultimosCertificadosUsados ?? [] as $certificado)
                    <div class="d-flex align-items-center mb-3 p-3 border rounded">
                        <div class="me-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-certificate"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $certificado->evento }}</h6>
                            <p class="mb-1 text-muted">
                                <i class="fas fa-user"></i> {{ $certificado->responsable }}
                            </p>
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($certificado->fecha)->format('d/m/Y') }}
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-warning text-dark fs-6">{{ $certificado->cantidad }}</span>
                            <br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($certificado->created_at)->format('d/m H:i') }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-certificate fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No hay certificados usados recientemente</h6>
                        <a href="{{ route('certificados.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Registrar Certificado
                        </a>
                    </div>
                    @endforelse

                    @if(($ultimosCertificadosUsados ?? collect())->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('certificados.create') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-eye"></i> Ver Todos los Certificados
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Panel lateral con información adicional -->
    <div class="row mt-4 g-4">
        <div class="col-12 col-lg-8">
            <!-- Aquí puedes agregar futuras secciones o gráficos -->
        </div>
        <div class="col-12 col-lg-4">
            <div class="card border-success mb-3 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-certificate"></i> Estado de Certificados</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-success">{{ $stockCertificados ?? 0 }}</h5>
                            <small class="text-muted">Disponibles</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-warning">{{ $certificadosUsados ?? 0 }}</h5>
                            <small class="text-muted">Usados</small>
                        </div>
                    </div>
                    @if(($stockCertificados ?? 0) < 50)
                    <div class="alert alert-warning mt-2 mb-0 py-2">
                        <small><i class="fas fa-exclamation-triangle"></i> Stock bajo de certificados</small>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card shadow-sm">
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
