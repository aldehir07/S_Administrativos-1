@extends('layouts.app')
@section('content')

<div class="container">

    @if (session('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
    @endif

     @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close float-end me-3" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header" style="background:#082140">
            <h2 class="card-tittle mb-0 text-white"> <i class="fas fa-plus-circle"></i> Agregar Producto</h2>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('producto.store') }}" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="row">
                <!-- Columna Izquierda -->
                <div class="col-md-6">

                    <!-- Clasificación -->
                    <div class="mb-3 ">
                        <label class="form-label">Clasificación</label>
                        <select name="clasificacion_id" class="form-select">
                            <option value="" disabled selected>Seleccione</option>
                            @foreach ($clasificaciones as $clasificacion)
                                <option value="{{ $clasificacion->id }}">{{ $clasificacion->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nombre Producto -->
                    <div class="mb-3">
                        <label class="form-label">Nombre del producto</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
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

                <!-- Columna Derecha -->
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="form-label">Stock Minimo</label>
                        <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock Actual</label>
                        <input type="number" name="stock_actual" class="form-control" value="{{ old('stock_actual', 0) }}">
                    </div>

                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">Registrar Producto</button>
            </div>
        </form>
        </div>
    </div>

    <!-- Tanla de insumos registrados -->
    <div class="card mt-5">
        <div class="card-header" style="background:#3177bf">
            <h4 class="card-tittle mb-0 text-white"> <i class="fas fa-list"></i> Listado de Productos</h4>
        </div>

        <table class="table table-hover table-striped" id="tabla">
            <thead>
                <tr class="table-info">
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
                        <form action="{{ route('producto.destroy', $producto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estas seguro de que deseaa eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" >
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
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
    $(document).ready(function() {
        $('#tabla').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
            }
        });
    });
    document.getElementById('imagenInput').addEventListener('change', function(event) {
        const [file] = event.target.files;
        const preview = document.getElementById('previewImagen');
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = ' block';
        } else {
            preview.src = '#'
            preview.style.display = 'none';
        }
    })
</script>

@endsection
