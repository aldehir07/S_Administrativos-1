@extends('layouts.app')
@section('content')
<div class="container" style="max-width: 420px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h4 class="mb-4 text-center">Iniciar Sesión</h4>
            @if (session('mensaje'))
                <div class="alert alert-warning" role="alert">
                    {{ session('mensaje')}}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('loginpost') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" value="{{ old('nombre') }}" required autofocus>
                </div>
                <div class="mb-3 position-relative">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                    <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3" role="button" id="togglePassword"></i>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <span>¿No tienes cuenta?</span>
                <a href="{{ route('usuario.create') }}">Regístrate</a>
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
