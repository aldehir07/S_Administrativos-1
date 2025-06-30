@extends('layouts.app')
@section('content')

<div class="container">
    <h2 class="mb-4">Agregar Producto</h2>

    <form method="POST" action="{{ route('producto.store') }}">
        @csrf
        @method('POST')
        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">

                <!-- Clasificación -->
                <div class="mb-3 ">
                    <label class="form-label">Clasificación</label>
                    <select name="clasificacion" class="form-select">
                        <option value="" disabled selected>Seleccione</option>
                        <option value="comestibles">Comestibles</option>
                        <option value="desechables">Desechables</option>
                        <option value="utiles de oficina">Utiles de oficina</option>
                        <option value="utiles de limpieza">Insumos de limpieza</option>
                    </select>
                </div>

                <!-- Nombre Producto -->
                <div class="mb-3">
                    <label class="form-label">Nombre del producto</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-md-6">
            
                <div class="mb-3">
                    <label class="form-label">Stock Minimo</label>
                    <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo') }}">
                </div>

                <!-- Imagen -->
                <div class="mb-3">
                    <label class="form-label">Imagen</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*" id=imagenInput>
                    <div class="mt-2">
                        <img id="previewImagen" src="#" alt="vista previa" style="max-width: 150px; display: none;">
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Registrar Producto</button>
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
                    <th>Stock Actual</th>
                    <th>Stock_minimo</th>
                    <th>Acciones</th>
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
                        <td>{{ $producto->clasificacion->nombre ?? '' }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->stock_actual }}</td>
                        <td>{{ $producto->stock_minimo }}</td>
                        <td>
                            <a href="{{ route('producto.edit', $producto->id) }}" class='btn btn-sm btn-warning'>
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
     </div>
     <form action="{{ route('producto.importar') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <input type="file" name="archivo" class="form-control" accept=".csv" required>
            </div>
            <div class="col-md-6">
                <button class="btn btn-success" type="submit">Importar CSV</button>
            </div>
        </div>
    </form>

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