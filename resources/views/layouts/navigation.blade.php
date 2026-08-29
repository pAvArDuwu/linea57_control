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

    <!-- SECCIÓN 1: CONTROL OPERATIVO EN VIVO -->
    <div class="menu-title">Control Operativo</div>
    @if(Auth::user()->hasRole(['admin', 'propietario', 'fiscalizador', 'conductor']))
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-grid-1x2 me-2"></i><span>Panel de Control</span>
        </a>
    @endif
    @if(Auth::user()->hasRole(['admin', 'propietario', 'fiscalizador', 'conductor']))
        <a class="nav-link {{ request()->routeIs('seguimiento-rutas.*') || request()->routeIs('monitoreo.*') ? 'active' : '' }}" href="{{ route('seguimiento-rutas.index') }}">
            <i class="bi bi-map me-2"></i><span>Seguimiento GPS en Vivo</span>
        </a>
    @endif
    @if(Auth::user()->hasRole(['admin', 'propietario', 'fiscalizador']))
        <a class="nav-link {{ request()->routeIs('control-paradas.*') ? 'active' : '' }}" href="{{ route('control-paradas.index') }}">
            <i class="bi bi-pin-map me-2"></i><span>Control de Paradas</span>
        </a>
    @endif

    <!-- SECCIÓN 2: PROGRAMACIÓN Y RUTAS -->
    <div class="menu-title">Programación y Rutas</div>
    @if(Auth::user()->hasRole(['admin', 'fiscalizador']))
        <a class="nav-link {{ request()->routeIs('asignacion-turno.*') ? 'active' : '' }}" href="{{ route('asignacion-turno.index') }}">
            <i class="bi bi-calendar2-check me-2"></i><span>Asignación de Turnos</span>
        </a>
    @endif
    @if(Auth::user()->hasRole(['admin', 'propietario', 'fiscalizador']))
        <a class="nav-link {{ request()->routeIs('ruta.*') ? 'active' : '' }}" href="{{ route('ruta.index') }}">
            <i class="bi bi-signpost-2 me-2"></i><span>Rutas y Recorridos</span>
        </a>
        <a class="nav-link {{ request()->routeIs('parada.*') ? 'active' : '' }}" href="{{ route('parada.index') }}">
            <i class="bi bi-geo-alt me-2"></i><span>Paradas Autorizadas</span>
        </a>
        <a class="nav-link {{ request()->routeIs('turno.*') ? 'active' : '' }}" href="{{ route('turno.index') }}">
            <i class="bi bi-clock me-2"></i><span>Horarios y Turnos</span>
        </a>
    @endif

    <!-- SECCIÓN 3: FLOTA Y PERSONAL -->
    <div class="menu-title">Flota y Personal</div>
    @if(Auth::user()->hasRole(['admin', 'propietario', 'fiscalizador']))
        <a class="nav-link {{ request()->routeIs('micro.*') ? 'active' : '' }}" href="{{ route('micro.index') }}">
            <i class="bi bi-bus-front me-2"></i><span>Microbuses (Flota)</span>
        </a>
        <a class="nav-link {{ request()->routeIs('interno.*') ? 'active' : '' }}" href="{{ route('interno.index') }}">
            <i class="bi bi-hdd-stack me-2"></i><span>Números Internos</span>
        </a>
        <a class="nav-link {{ request()->routeIs('conductor.*') ? 'active' : '' }}" href="{{ route('conductor.index') }}">
            <i class="bi bi-person-badge me-2"></i><span>Conductores</span>
        </a>
        <a class="nav-link {{ request()->routeIs('propietario.*') ? 'active' : '' }}" href="{{ route('propietario.index') }}">
            <i class="bi bi-person-vcard me-2"></i><span>Propietarios</span>
        </a>
    @endif

    <!-- SECCIÓN 4: ADMINISTRACIÓN Y SEGURIDAD -->
    @if(Auth::user()->hasRole(['admin', 'propietario']))
        <div class="menu-title">Administración y Accesos</div>
        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <i class="bi bi-people me-2"></i><span>Usuarios del Sistema</span>
        </a>
        <a class="nav-link {{ request()->routeIs('roles.*') && !request()->routeIs('roles.assign') ? 'active' : '' }}" href="{{ route('roles.index') }}">
            <i class="bi bi-shield me-2"></i><span>Roles y Permisos</span>
        </a>
        <a class="nav-link {{ request()->routeIs('roles.assign') ? 'active' : '' }}" href="{{ route('roles.assign') }}">
            <i class="bi bi-person-gear me-2"></i><span>Asignar Roles</span>
        </a>
    @endif

    <!-- SECCIÓN 5: REPORTES Y CONSULTAS -->
    <div class="menu-title">Reportes y Consultas</div>
    <a class="nav-link" href="#">
        <i class="bi bi-file-earmark-bar-graph me-2"></i><span>Reporte de Rutas</span>
    </a>
    <a class="nav-link" href="#">
        <i class="bi bi-file-earmark-person me-2"></i><span>Reporte de Conductores</span>
    </a>
    <a class="nav-link" href="#">
        <i class="bi bi-file-earmark-spreadsheet me-2"></i><span>Reporte de Flota</span>
    </a>
</aside>
