<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="app-shell">
            @include('layouts.navigation')
            <div class="content-area">
                <header class="topbar">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <button id="sidebarToggle" class="sidebar-toggle btn p-0 me-1 d-lg-none" type="button">
                                <i class="bi bi-list fs-2 text-primary"></i>
                            </button>
                            <div>
                                <div class="topbar-brand-title">Sistema de Control y Seguimiento de Micros</div>
                                <div class="topbar-brand-subtitle">
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold me-1 px-2 py-1" style="font-size: 0.75rem;">Línea 61</span>
                                    Santa Cruz de la Sierra · Bolivia
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center gap-2 px-2 py-1 bg-light rounded-3 border">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="pe-2">
                                    <div class="fw-bold small text-dark" style="line-height: 1.1;">{{ Auth::user()->name ?? 'Usuario' }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ Auth::user()->roles->first()?->name ?? 'Administrador' }}</div>
                                </div>
                            </div>
                            <div>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="logout-btn btn d-flex align-items-center gap-1">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Cerrar sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="p-3 p-lg-4">
                    @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    @yield('content')
                    {{ $slot ?? '' }}
                </main>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var toggle = document.getElementById('sidebarToggle');
                if (toggle) {
                    toggle.addEventListener('click', function () {
                        document.body.classList.toggle('sidebar-open');
                    });
                }
            });
        </script>
    </body>
</html>
