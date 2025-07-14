@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Gestión de bienes</h2>
                    <p class="text-muted mb-0">Control y administración de bienes de la ACG</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-warning text-dark fs-6">
                        <i class="fas fa-tools me-2"></i>En Desarrollo
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center py-5">
                    <!-- Icono principal -->
                    <div class="mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                            <i class="fas fa-boxes fa-3x text-primary"></i>
                        </div>
                    </div>

                    <!-- Título principal -->
                    <h3 class="text-primary mb-3">Próximamente</h3>

                    <!-- Descripción -->
                    <p class="text-muted fs-5 mb-4">
                        Estamos trabajando en el módulo de gestión de bienes para brindarte una experiencia completa de control de inventario.
                    </p>

                    <!-- Características que vendrán -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="p-3">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-plus-circle fa-2x text-success"></i>
                                </div>
                                <h6 class="fw-bold">Registro de Bienes</h6>
                                <small class="text-muted">Agregar y gestionar bienes del sistema</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-chart-line fa-2x text-info"></i>
                                </div>
                                <h6 class="fw-bold">Control de Stock</h6>
                                <small class="text-muted">Monitoreo en tiempo real del inventario</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-history fa-2x text-warning"></i>
                                </div>
                                <h6 class="fw-bold">Historial Completo</h6>
                                <small class="text-muted">Seguimiento de movimientos y transacciones</small>
                            </div>
                        </div>
                    </div>

                    <!-- Barra de progreso -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Progreso del desarrollo</span>
                            <span class="text-primary fw-bold">20%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 20%"></div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('datos.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                        </a>
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="fas fa-bell me-2"></i>Notificarme cuando esté listo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="row mt-5">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-clock text-primary fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1">Tiempo Estimado</h6>
                            <p class="text-muted mb-0">2-3 semanas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-start border-success border-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle text-success fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1">Funcionalidades</h6>
                            <p class="text-muted mb-0">15+ características</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-start border-info border-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-users text-info fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1">Equipo</h6>
                            <p class="text-muted mb-0">Desarrolladores trabajando</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensaje de contacto -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                <strong>¿Tienes sugerencias?</strong> Nos encantaría escuchar tus ideas para mejorar este módulo.
                <a href="#" class="alert-link">Contáctanos</a>
            </div>
        </div>
    </div>
</div>

@endsection
