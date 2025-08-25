<!-- [Mobile Media Block] start -->
<div class="me-auto pc-mob-drp">
    <ul class="list-unstyled">
        <li class="pc-h-item pc-sidebar-collapse">
            <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                <i class="ti ti-menu-2"></i>
            </a>
        </li>
        <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                <i class="ti ti-menu-2"></i>
            </a>
        </li>
    </ul>
</div>
<!-- [Mobile Media Block end] -->
<div class="ms-auto">
    <ul class="list-unstyled">
        <li class="dropdown pc-h-item header-user-profile">
            @if (Auth::check())
                <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                    <img src="{{ asset('logo/Logo-ACG.png') }}" alt="user-image" class="user-avtar wid-35">
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                    <div class="dropdown-header">
                        <div class="d-flex mb-1">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('logo/Logo-ACG.png') }}" alt="user-image" class="user-avtar wid-35">
                            </div>
                            <div class="flex-grow-1 ms-5">
                                <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                                <span>{{ Auth::user()->email }}</span>
                            </div>
                            <a href="{{ route('logout') }}" class="pc-head-link bg-transparent"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ti ti-power text-danger"></i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a class="pc-head-link me-0" href="#">
                    <img src="#!" alt="" class="">
                    <span>Usuario</span>
                </a>
            @endif
        </li>
    </ul>
</div>
