<aside class="sidebar" id="sidebar">
    <!-- Header Brand -->
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-white d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(255,255,255,0.16);">
                <i class="bi bi-bus-front fs-5"></i>
            </div>
            <div>
                <div class="fw-bold text-white mb-0" style="line-height: 1.1;">Línea 61</div>
                <div class="small text-white-50" style="font-size: 0.7rem;">Control & Monitoreo</div>
            </div>
        </a>
        <button id="sidebarToggle" class="sidebar-toggle me-2" type="button"><i class="bi bi-list"></i></button>
    </div>

    <!-- SECCIÓN 1: CONTROL OPERATIVO EN VIVO -->
    <div class="menu-title">Control Operativo</div>
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-grid-1x2 me-2"></i><span>Panel General</span>
    </a>
    <a class="nav-link {{ request()->routeIs('seguimiento-rutas.*') || request()->routeIs('monitoreo.*') ? 'active' : '' }}" href="{{ route('seguimiento-rutas.index') }}">
        <i class="bi bi-map me-2"></i><span>Seguimiento GPS en Vivo</span>
    </a>
    <a class="nav-link {{ request()->routeIs('control-paradas.*') ? 'active' : '' }}" href="{{ route('control-paradas.index') }}">
        <i class="bi bi-pin-map me-2"></i><span>Control de Paradas</span>
    </a>

    <!-- SECCIÓN 2: PROGRAMACIÓN Y RUTAS -->
    <div class="menu-title">Programación y Rutas</div>
    <a class="nav-link {{ request()->routeIs('asignacion-turno.*') ? 'active' : '' }}" href="{{ route('asignacion-turno.index') }}">
        <i class="bi bi-calendar2-check me-2"></i><span>Asignación de Turnos</span>
    </a>
    <a class="nav-link {{ request()->routeIs('ruta.*') ? 'active' : '' }}" href="{{ route('ruta.index') }}">
        <i class="bi bi-signpost-2 me-2"></i><span>Rutas y Recorridos</span>
    </a>
    <a class="nav-link {{ request()->routeIs('parada.*') ? 'active' : '' }}" href="{{ route('parada.index') }}">
        <i class="bi bi-geo-alt me-2"></i><span>Paradas Autorizadas</span>
    </a>
    <a class="nav-link {{ request()->routeIs('turno.*') ? 'active' : '' }}" href="{{ route('turno.index') }}">
        <i class="bi bi-clock me-2"></i><span>Horarios y Turnos</span>
    </a>

    <!-- SECCIÓN 3: FLOTA Y PERSONAL -->
    <div class="menu-title">Flota y Personal</div>
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

    <!-- SECCIÓN 4: ADMINISTRACIÓN Y SEGURIDAD -->
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
