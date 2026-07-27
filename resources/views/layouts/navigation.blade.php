<aside class="sidebar" id="sidebar">
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-white d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(255,255,255,0.16);">
                <i class="bi bi-bus-front"></i>
            </div>
            <div class="fw-semibold">Línea 61</div>
        </a>
    </div>

    <!-- General Section -->
    <div class="menu-title">General</div>
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-grid-1x2 me-2"></i><span>Dashboard</span>
    </a>

    <!-- Módulos Section -->
    <div class="menu-title">Módulos</div>
    
    <!-- Seguridad Module (Collapsible) -->
    @php
        $seguridadActive = request()->routeIs('users.*') || request()->routeIs('roles.*');
    @endphp
    <a class="nav-link {{ $seguridadActive ? 'active' : '' }}" 
       data-bs-toggle="collapse" 
       href="#seguridadSubmenu" 
       role="button" 
       aria-expanded="{{ $seguridadActive ? 'true' : 'false' }}" 
       aria-controls="seguridadSubmenu">
        <i class="bi bi-shield-lock me-2"></i>
        <span>Seguridad</span>
        <i class="bi bi-caret-down-fill ms-auto"></i>
    </a>
    <div class="collapse submenu {{ $seguridadActive ? 'show' : '' }}" id="seguridadSubmenu">
        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <i class="bi bi-people me-2"></i><span>Usuarios</span>
        </a>
        <a class="nav-link {{ request()->routeIs('roles.*') && !request()->routeIs('roles.assign') ? 'active' : '' }}" href="{{ route('roles.index') }}">
            <i class="bi bi-shield me-2"></i><span>Roles</span>
        </a>
        <a class="nav-link {{ request()->routeIs('roles.assign') ? 'active' : '' }}" href="{{ route('roles.assign') }}">
            <i class="bi bi-person-gear me-2"></i><span>Asignar Roles</span>
        </a>
    </div>

    <!-- Parametrización Module (Collapsible) -->
    @php
        $parametrizacionActive = request()->routeIs('conductor.*') || 
                                 request()->routeIs('propietario.*') || 
                                 request()->routeIs('micro.*') || 
                                 request()->routeIs('interno.*') || 
                                 request()->routeIs('ruta.*') || 
                                 request()->routeIs('parada.*');
    @endphp
    <a class="nav-link {{ $parametrizacionActive ? 'active' : '' }}" 
       data-bs-toggle="collapse" 
       href="#parametrizacionSubmenu" 
       role="button" 
       aria-expanded="{{ $parametrizacionActive ? 'true' : 'false' }}" 
       aria-controls="parametrizacionSubmenu">
        <i class="bi bi-sliders me-2"></i>
        <span>Parametrización</span>
        <i class="bi bi-caret-down-fill ms-auto"></i>
    </a>
    <div class="collapse submenu {{ $parametrizacionActive ? 'show' : '' }}" id="parametrizacionSubmenu">
        <a class="nav-link {{ request()->routeIs('conductor.*') ? 'active' : '' }}" href="{{ route('conductor.index') }}">
            <i class="bi bi-person-badge me-2"></i><span>Conductores</span>
        </a>
        <a class="nav-link {{ request()->routeIs('propietario.*') ? 'active' : '' }}" href="{{ route('propietario.index') }}">
            <i class="bi bi-person-vcard me-2"></i><span>Propietarios</span>
        </a>
        <a class="nav-link {{ request()->routeIs('micro.*') ? 'active' : '' }}" href="{{ route('micro.index') }}">
            <i class="bi bi-bus-front me-2"></i><span>Micros</span>
        </a>
        <a class="nav-link {{ request()->routeIs('interno.*') ? 'active' : '' }}" href="{{ route('interno.index') }}">
            <i class="bi bi-hdd-stack me-2"></i><span>Internos</span>
        </a>
        <a class="nav-link {{ request()->routeIs('ruta.*') ? 'active' : '' }}" href="{{ route('ruta.index') }}">
            <i class="bi bi-signpost-2 me-2"></i><span>Rutas</span>
        </a>
        <a class="nav-link {{ request()->routeIs('parada.*') ? 'active' : '' }}" href="{{ route('parada.index') }}">
            <i class="bi bi-geo-alt me-2"></i><span>Paradas</span>
        </a>
    </div>

    <!-- Transacciones Module (Collapsible) -->
    @php
        $transaccionesActive = request()->routeIs('turno.*');
    @endphp
    <a class="nav-link {{ $transaccionesActive ? 'active' : '' }}" 
       data-bs-toggle="collapse" 
       href="#transaccionesSubmenu" 
       role="button" 
       aria-expanded="{{ $transaccionesActive ? 'true' : 'false' }}" 
       aria-controls="transaccionesSubmenu">
        <i class="bi bi-arrow-left-right me-2"></i>
        <span>Transacciones</span>
        <i class="bi bi-caret-down-fill ms-auto"></i>
    </a>
    <div class="collapse submenu {{ $transaccionesActive ? 'show' : '' }}" id="transaccionesSubmenu">
        <a class="nav-link {{ request()->routeIs('turno.*') ? 'active' : '' }}" href="{{ route('turno.index') }}">
            <i class="bi bi-calendar2-check me-2"></i><span>Asignación</span>
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-play-circle me-2"></i><span>Inicio recorrido</span>
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-stop-circle me-2"></i><span>Finalizar</span>
        </a>
    </div>

    <!-- Reportes Module (Collapsible) -->
    <a class="nav-link" 
       data-bs-toggle="collapse" 
       href="#reportesSubmenu" 
       role="button" 
       aria-expanded="false" 
       aria-controls="reportesSubmenu">
        <i class="bi bi-bar-chart-line me-2"></i>
        <span>Reportes</span>
        <i class="bi bi-caret-down-fill ms-auto"></i>
    </a>
    <div class="collapse submenu" id="reportesSubmenu">
        <a class="nav-link" href="#">
            <i class="bi bi-file-earmark-bar-graph me-2"></i><span>Reporte de Rutas</span>
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-file-earmark-person me-2"></i><span>Reporte de Conductores</span>
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-file-earmark-spreadsheet me-2"></i><span>Reporte de Micros</span>
        </a>
    </div>

    <!-- Configuración Section -->
    <div class="menu-title">Configuración</div>
    <a class="nav-link" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i><span>Perfil</span></a>
    <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button type="submit" class="nav-link border-0 w-100 text-start"><i class="bi bi-box-arrow-right me-2"></i><span>Cerrar sesión</span></button>
    </form>
</aside>
