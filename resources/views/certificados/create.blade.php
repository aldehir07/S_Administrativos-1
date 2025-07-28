@extends('layouts.app')
@section('content')

<div class="container">
        <!-- Mensajes de alerta -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

    <div class="card">
        <div class="card-header" style="background:#082140;">
            <h2 class="card-tittle mb-0 text-white"><i class="fas fa-certificate"></i> Control de Certificados</h2>
        </div>

        <div class="card-body">
                <div class="row">
                <!-- Formulario para registrar certificados usados -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header" style="background:#3177bf">
                            <h5 class="card-title mb-0 text-white">Registrar Certificados Usados</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('certificados.store') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Fecha del Evento</label>
                                    <input type="date" name="fecha" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Cantidad Usada</label>
                                    <input type="number" name="cantidad" class="form-control" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nombre del Evento</label>
                                    <input type="text" name="evento" class="form-control" placeholder="Ej: Conferencia de Tecnología" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Responsable</label>
                                    <input type="text" name="responsable" class="form-control" placeholder="Quien solicitó los certificados" required>
                                </div>

                                <button type="submit" class="btn text-white" style="background:#3177bf">Registrar Uso</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Card para agregar al stock -->
                <div class="col-md-6">
                    <div class="card text-center card-stock-bg">
                        <div class="card-header" style="background:#3177bf">
                            <h5 class="card-title mb-0 text-white">Stock Actual</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="display-4 text-primary">{{ $stockTotal ?? 0 }}</h2>
                            <p class="card-text">Certificados disponibles en inventario</p>

                            <button type="button" class="btn text-white" data-bs-toggle="modal" data-bs-target="#agregarStockModal" style="background:#3177bf">
                                Agregar al Stock
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Certificados Usados -->
    <div class="card mt-4">
        <div class="card-header" style="background:#3177bf">
            <h5 class="card-title mb-0 text-white"> <i class="fas fa-list"></i> Historial de Certificados Usados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-info">
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Evento</th>
                            <th scope="col">Cantidad Usada</th>
                            <th scope="col">Responsable</th>
                            <th scope="col">Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($certificadosUsados ?? [] as $certificado)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($certificado->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $certificado->evento }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ $certificado->cantidad }}
                                    </span>
                                </td>
                                <td>{{ $certificado->responsable }}</td>
                                <td>{{ \Carbon\Carbon::parse($certificado->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay certificados usados registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar al stock -->
<div class="modal fade" id="agregarStockModal" tabindex="-1" aria-labelledby="agregarStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarStockModalLabel">Agregar Certificados al Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('certificados.agregar-stock') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cantidad a Agregar</label>
                        <input type="number" name="cantidad_agregar" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Responsable</label>
                        <input type="text" name="responsable_agregar" class="form-control" placeholder="Quien agrega al stock" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha_agregar" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Agregar al Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
