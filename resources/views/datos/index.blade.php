@extends('layouts.app')
@section('content')

<div class="container">
    <h2 class="mb-4">Productos en Stock Crítico</h2>

    <div class="row">
        @forelse($productosCriticos as $producto)
        <div class="col-md-4 mb-4">
            <div class="card border-warning shadow h-100">
                @if($producto->imagen)
                <img src="{{ asset('storage/'.$producto->imagen) }}" class="card-img-top" alt="Imagen de {{ $producto->nombre }}" style="object-fit:cover;max-height:180px;">
                @else
                <img src="https://via.placeholder.com/300x180?text=Sin+Imagen" class="card-img-top" alt="Sin imagen">
                @endif
                <div class="card-body">
                    <h5 class="card-title text-danger fw-bold">{{ $producto->nombre }}</h5>
                    <p class="card-text mb-1"><strong>Clasificación:</strong> {{ $producto->clasificacion->nombre ?? 'N/A' }}</p>
                    <p class="card-text mb-1"><strong>Stock actual:</strong> <span class="text-danger">{{ $producto->stock_actual }}</span></p>
                    <p class="card-text mb-1"><strong>Stock mínimo:</strong> {{ $producto->stock_minimo }}</p>
                </div>
                <div class="card-footer bg-warning text-dark fw-bold text-center">
                    ¡Stock crítico!
                </div>
                <a href="{{ route('movimiento.create', ['producto_id' => $producto->id]) }}" class="stretched-link"></a>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-success text-center">
                No hay productos en stock crítico.
            </div>
        </div>
        @endforelse
    </div>
</div>

@endsection