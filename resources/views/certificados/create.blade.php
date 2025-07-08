@extends('layouts.app')
@section('content')

<div class="container">
    <h2 class="mb-4">Certificados</h2>

    <form action="#!" method="post">
        @csrf
        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">

                <div class="mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" id="" class="form-control">
                </div>

            </div>

            <!-- Columna Derecha -->
            <div class="col-md-6">

                <div class="mb-3">
                    <label class="form-label">Evento</label>
                    <input type="text" name="evento" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Responsable</label>
                      <select class="form-select" name="responsable" aria-label="Default select example">
                        <option selected>Seleccione</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                      </select>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection