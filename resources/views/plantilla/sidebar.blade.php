<!-- filepath: /S_Administrativos/S_Administrativos/resources/views/partials/sidebar.blade.php -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('datos.index') }}" class="b-brand text-primary">
                <img src="{{ asset('logo/SISA.jpg') }}" class="img-fluid logo-lg" alt="logo">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item">
                    <a href="{{ route('datos.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>
                <li class="pc-item pc-caption">
                    <label>Secciones</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"><i class="bi bi-fork-knife"></i></span><span class="pc-mtext">Insumos</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('producto.create') }}">Productos</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('movimiento.create') }}">Movimiento</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('reportes.index') }}">Reportes</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('certificados.create') }}">Certificados</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="{{ route('bienes.index') }}" class="pc-link"><span class="pc-micon"><i class="bi bi-box-seam"></i></span><span class="pc-mtext">Bienes</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <!-- <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="#!">Agregar</a></li>
                            <li class="pc-item"><a class="pc-link" href="#!">Inventario</a></li>
                            <li class="pc-item"><a class="pc-link" href="#!">Reportes</a></li>
                        </ul> -->
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="{{ route('materiales.index') }}" class="pc-link"><span class="pc-micon"><i class="bi bi-layers"></i></i></span><span class="pc-mtext">Materiales</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <!-- <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="#!">Agregar</a></li>
                            <li class="pc-item"><a class="pc-link" href="#!">Inventario</a></li>
                            <li class="pc-item"><a class="pc-link" href="#!">Reportes</a></li>
                        </ul> -->
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="{{ route('equipos.index') }}" class="pc-link"><span class="pc-micon"><i class="bi bi-cpu"></i></i></span><span class="pc-mtext">Equipos</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <!-- <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="#!">Agregar</a></li>
                            <li class="pc-item"><a class="pc-link" href="#!">Inventario</a></li>
                            <li class="pc-item"><a class="pc-link" href="#!">Reportes</a></li>
                        </ul> -->
                    </li>
                
            </ul>
            
            <li class="pc-item pc-caption">
                <label>Usuario</label>
                <i class="ti ti-news"></i>
            </li>
            @if(!Auth::user())
            <li class="pc-item">
                <a href="{{ route('login') }}" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-lock"></i></span>
                    <span class="pc-mtext">Login</span>
                </a>
            </li>
            <li class="pc-item">
                <a href="{{ route('usuario.create') }}" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                    <span class="pc-mtext">Register</span>
                </a>
            </li>
            @else
            <li class="pc-item">
                <a class="pc-link" href="{{ route('logout') }}">
                    <span class="pc-micon"><i class="ti ti-logout"></i></span>
                    <span class="pc-text">Cerrar Sesion</span>
                </a>
            </li>
            @endif
            </ul>
        </div>
    </div>
</nav>
