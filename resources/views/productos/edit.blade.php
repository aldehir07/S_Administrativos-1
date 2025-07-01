@extends('layouts.app')
@section('content')

<div class="container">
    <h2 class="mb-4">Editar Producto</h2>

    <form method="POST" action="{{ route('producto.update', $producto->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">

                <!-- Clasificación -->
                <div class="mb-3 ">
                    <label class="form-label">Clasificación</label>
                    <select name="clasificacion_id" class="form-select">
                        <option value="" disabled>Seleccione</option>
                        @foreach($clasificaciones as $clasificacion)
                            <option value="{{ $clasificacion->id }}"
                                {{ old('clasificacion_id', $producto->clasificacion_id) == $clasificacion->id ? 'selected' : '' }}>
                                {{ $clasificacion->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre Producto -->
                <div class="mb-3">
                    <label class="form-label">Nombre del producto</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}">
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-md-6">
            
                <div class="mb-3">
                    <label class="form-label">Stock Minimo</label>
                    <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', $producto->stock_minimo) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock Actual</label>
                    <input type="number" name="stock_actual" class="form-control" value="{{ old('stock_actual', $producto->stock_actual) }}">
                </div>

                <!-- Imagen -->
                <div class="mb-3">
                    <label class="form-label">Imagen</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*" id=imagenInput>
                    <div class="mt-2">
                        <img id="previewImagen"
                            src="{{ $producto->imagen ? asset('storage/'.$producto->imagen) : '#' }}"
                            alt="vista previa"
                            style="max-width: 150px; {{ $producto->imagen ? '' : 'display: none;' }}">
                    </div>
                </div>

            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        </div>
    </form>

    <!-- Tanla de insumos registrados -->
     <div class="mt-5">
        <h4>Listado de Insumos</h4>
        @if (session('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
        @endif
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Clasificacion</th>
                    <th>Nombre</th>
                    <th>stock actual</th>
                    <th>stock_minimo</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>
                            @if($producto->imagen)
                                <img src="{{ asset('storage/'.$producto->imagen) }}" alt="Imagen" width="40">
                            @endif
                        </td>
                        <td>{{ $producto->clasificacion->nombre }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->stock_actual }}</td>
                        <td>{{ $producto->stock_minimo }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
     </div>
</div>

<script>
    document.getElementById('imagenInput').addEventListener('change', function(event){
        const [file] = event.target.files;
        const preview = document.getElementById('previewImagen');
        if(file){
            preview.src = URL.createObjectURL(file);
            preview.style.display = ' block';
        }else{
            preview.src = '#'
            preview.style.display = 'none';
        }
    })
</script>

@endsection