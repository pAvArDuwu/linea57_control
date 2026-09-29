<aside class="sidebar" id="sidebar">
    <!-- Header Brand -->
    <div class="brand-container d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-white border-opacity-10 px-2">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-white d-flex align-items-center gap-3">
            <div class="brand-logo rounded-3 d-flex align-items-center justify-content-center shadow-sm">
                <i class="bi bi-bus-front fs-3"></i>
            </div>
            <div class="brand-text">
                <div class="brand-title fw-bold text-white mb-0">Línea 61</div>
            </div>
        </a>
        <button id="sidebarToggle" class="sidebar-toggle" type="button" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4 text-white"></i>
        </button>
    </div>

    <!-- General Section -->
    <div class="menu-title">General</div>
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-grid-1x2 me-2"></i><span>Dashboard</span>
    </a>

    <!-- Módulos Section -->
    <div class="menu-title">Módulos</div>

    <!-- MÓDULO 1: PARAMETRIZACIÓN -->
    @if(Auth::user()->hasRole(['admin', 'propietario', 'fiscalizador']))
    @php
        $parametrizacionActive = request()->routeIs('conductor.*') || 
                                 request()->routeIs('propietario.*') || 
                                 request()->routeIs('micro.*') || 
                                 request()->routeIs('interno.*') || 
                                 request()->routeIs('ruta.*') || 
                                 request()->routeIs('parada.*') ||
                                 request()->routeIs('turno.*');
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
        <a class="nav-link {{ request()->routeIs('turno.*') ? 'active' : '' }}" href="{{ route('turno.index') }}">
            <i class="bi bi-clock me-2"></i><span>Turnos</span>
        </a>
    </div>
    @endif

    <!-- MÓDULO 2: SEGURIDAD -->
    @if(Auth::user()->hasRole(['admin', 'propietario']))
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
    @endif

    <!-- MÓDULO 3: TRANSACCIONES -->
    @if(Auth::user()->hasRole(['admin', 'propietario', 'fiscalizador']))
    @php
        $transaccionesActive = request()->routeIs('asignacion-turno.*') || 
                               request()->routeIs('seguimiento-rutas.*') || 
                               request()->routeIs('monitoreo.*') || 
                               request()->routeIs('control-paradas.*') ||
                               request()->routeIs('control-recorrido.*');
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
        <a class="nav-link {{ request()->routeIs('asignacion-turno.*') ? 'active' : '' }}" href="{{ route('asignacion-turno.index') }}">
            <i class="bi bi-calendar2-check me-2"></i><span>Asignación de Turnos</span>
        </a>
        <a class="nav-link {{ request()->routeIs('control-recorrido.*') ? 'active' : '' }}" href="{{ route('control-recorrido.index') }}">
            <i class="bi bi-signpost-split me-2"></i><span>Control de Recorrido</span>
        </a>
        <a class="nav-link {{ request()->routeIs('seguimiento-rutas.*') || request()->routeIs('monitoreo.*') ? 'active' : '' }}" href="{{ route('monitoreo.index') }}">
            <i class="bi bi-geo-alt me-2"></i><span>Seguimiento GPS / Monitoreo</span>
        </a>
    </div>
    @endif

    <!-- MÓDULO 4: REPORTES -->
    @if(Auth::user()->hasRole(['admin', 'propietario', 'fiscalizador']))
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
            <i class="bi bi-file-earmark-spreadsheet me-2"></i><span>Reporte de Flota</span>
        </a>
    </div>
    @endif

    <!-- Configuración Section -->
    <div class="menu-title">Configuración</div>
    <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
        <i class="bi bi-gear me-2"></i><span>Perfil</span>
    </a>
    <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button type="submit" class="nav-link border-0 w-100 text-start bg-transparent">
            <i class="bi bi-box-arrow-right me-2"></i><span>Cerrar sesión</span>
        </button>
    </form>
</aside>
