@extends('layouts.auth')
@section('content')
<div class="container" style="max-width: 520px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h4 class="mb-4 text-center">Crear Cuenta</h4>
            <form method="POST" action="{{ route('usuario.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>
                {{-- <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div> --}}
                <div class="row">
                    <div class="col-md-6 mb-3 position-relative">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                        <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3" role="button" id="togglePassword"></i>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                    </div>
                </div>
                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-primary">Registrarme</button>
                </div>
            </form>
            <div class="text-center mt-2">
                <span>¿Ya tienes cuenta?</span>
                <a href="{{ route('login') }}">Inicia Sesión</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#togglePassword').on('click', function(){
            const pass = $('#password');
            const type = pass.attr('type') === 'password' ? 'text' : 'password';
            pass.attr('type', type);
            $(this).toggleClass('bi-eye bi-eye-slash');
        });
    });
</script>
@endsection
