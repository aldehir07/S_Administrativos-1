<!-- [Mobile Media Block] start -->
<div class="me-auto pc-mob-drp">
  <ul class="list-unstyled">
    <!-- ======= Menu collapse Icon ===== -->
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
    <!-- <li class="dropdown pc-h-item d-inline-flex d-md-none">
      <a
        class="pc-head-link dropdown-toggle arrow-none m-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        aria-expanded="false"
      >
        <i class="ti ti-search"></i>
      </a> -->
    <!-- <div class="dropdown-menu pc-h-dropdown drp-search">
        <form class="px-3">
          <div class="form-group mb-0 d-flex align-items-center">
            <i data-feather="search"></i>
            <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
          </div>
        </form>
      </div> -->
    <!-- </li>
    <li class="pc-h-item d-none d-md-inline-flex">
      <form class="header-search">
        <i data-feather="search" class="icon-search"></i>
        <input type="search" class="form-control" placeholder="Search here. . .">
      </form>
    </li> -->

  </ul>
</div>
<!-- [Mobile Media Block end] -->
<div class="ms-auto">
  <ul class="list-unstyled">

      <!-- Conteo de notificaciones -->
       <!-- <li class="dropdown pc-h-item">
      @php
      $notificaciones = ($vencidos->count() ?? 0) + ($proximos->count() ?? 0);
      @endphp
      <a
        class="pc-head-link dropdown-toggle arrow-none me-0 position-relative"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        aria-expanded="false">
        <i class="ti ti-mail fs-4"></i>
        @if($notificaciones > 0)
        <span class="notification-badge-custom">
          {{ $notificaciones }}
        </span>
        @endif
      </a>
      <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
        <div class="dropdown-header d-flex align-items-center justify-content-between">
          <h5 class="m-0">Notificaciones</h5>
        </div>
        <div class="dropdown-divider"></div>
        <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
          <div class="list-group list-group-flush w-100">
            @forelse($vencidos as $movimiento)
            <a class="list-group-item list-group-item-action bg-danger bg-opacity-10">
              <div class="d-flex">
                <div class="flex-shrink-0">
                  <i class="ti ti-alert-triangle text-danger fs-4"></i>
                </div>
                <div class="flex-grow-1 ms-2">
                  <span class="float-end text-danger fw-bold">VENCIDO</span>
                  <p class="mb-1">
                    <b>{{ $movimiento->producto->nombre ?? 'Sin nombre' }}</b> venció el {{ \Carbon\Carbon::parse($movimiento->fecha_vencimiento)->format('d/m/Y') }}
                  </p>
                  <span class="text-muted">Stock: {{ $movimiento->producto->stock_actual ?? '-' }}</span>
                </div>
              </div>
            </a>
            @empty
            @endforelse

            @forelse($proximos as $movimiento)
            <a class="list-group-item list-group-item-action bg-warning bg-opacity-10">
              <div class="d-flex">
                <div class="flex-shrink-0">
                  <i class="ti ti-clock text-warning fs-4"></i>
                </div>
                <div class="flex-grow-1 ms-2">
                  <span class="float-end text-warning fw-bold">Próx. a vencer</span>
                  <p class="mb-1">
                    <b>{{ $movimiento->producto->nombre ?? 'Sin Nombre' }}</b> vence el {{ \Carbon\Carbon::parse($movimiento->fecha_vencimiento)->format('d/m/Y') }}
                  </p>
                  <span class="text-muted">Stock: {{ $movimiento->producto->stock_actual ?? '-' }}</span>
                </div>
              </div>
            </a>
            @empty
            @endforelse

            @if($vencidos->isEmpty() && $proximos->isEmpty())
            <div class="text-center text-muted py-3">Sin notificaciones de vencimiento</div>
            @endif
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="text-center py-2">
          <a href="{{ route('producto.index') }}" class="link-primary">Ver todos los productos</a>
        </div>
      </div>
      </li> -->
    </li>
    <li class="dropdown pc-h-item header-user-profile">
        <a
        class="pc-head-link dropdown-toggle arrow-none me-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        data-bs-auto-close="outside"
        aria-expanded="false">
        <img src="{{ asset('logo/Logo-ACG.png') }}" alt="Logo Academia" style="height:32px; width:auto; margin-right:8px; border-radius:50%; vertical-align:middle;">
        <span>{{ Auth::user()->name ?? 'Usuario' }}</span>
      </a>
    </li>

      <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
        <div class="dropdown-header">
          <div class="d-flex mb-1">
            <div class="flex-shrink-0">
              <img src="#!" alt="image" class="user-avtar wid-35">
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-1">{{ Auth::user()->name ?? 'Usuario'}}</h6>
              <span>{{ Auth::user()->email ?? 'Usuario'}}</span>
            </div>
            <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-power text-danger"></i></a>
          </div>
        </div>
        <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active"
              id="drp-t1"
              data-bs-toggle="tab"
              data-bs-target="#drp-tab-1"
              type="button"
              role="tab"
              aria-controls="drp-tab-1"
              aria-selected="true"><i class="ti ti-user"></i> Profile</button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="drp-t2"
              data-bs-toggle="tab"
              data-bs-target="#drp-tab-2"
              type="button"
              role="tab"
              aria-controls="drp-tab-2"
              aria-selected="false"><i class="ti ti-settings"></i> Setting</button>
          </li>
        </ul>
        <div class="tab-content" id="mysrpTabContent">
          <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel" aria-labelledby="drp-t1" tabindex="0">
            <a href="#!" class="dropdown-item">
              <i class="ti ti-edit-circle"></i>
              <span>Edit Profile</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-user"></i>
              <span>View Profile</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-clipboard-list"></i>
              <span>Social Profile</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-wallet"></i>
              <span>Billing</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-power"></i>
              <span>Logout</span>
            </a>
          </div>
          <div class="tab-pane fade" id="drp-tab-2" role="tabpanel" aria-labelledby="drp-t2" tabindex="0">
            <a href="#!" class="dropdown-item">
              <i class="ti ti-help"></i>
              <span>Support</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-user"></i>
              <span>Account Settings</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-lock"></i>
              <span>Privacy Center</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-messages"></i>
              <span>Feedback</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-list"></i>
              <span>History</span>
            </a>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>