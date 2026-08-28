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
        <style>
            :root {
                --primary: #0B3C78;
                --primary-dark: #072B52;
                --accent: #7B1E2B;
                --surface: #F4F6F9;
                --text: #1f2937;
            }
            body {
                font-family: 'Inter', sans-serif;
                background: var(--surface);
                color: var(--text);
            }
            .app-shell {
                min-height: 100vh;
                display: flex;
                background: var(--surface);
            }
            .sidebar {
                width: 270px;
                background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary) 100%);
                color: white;
                padding: 1.25rem 0.9rem;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
                transition: transform .25s ease, width .25s ease;
            }
            /* Slim scrollbar for sidebar */
            .sidebar::-webkit-scrollbar {
                width: 5px;
            }
            .sidebar::-webkit-scrollbar-track {
                background: transparent;
            }
            .sidebar::-webkit-scrollbar-thumb {
                background: rgba(255,255,255,0.2);
                border-radius: 10px;
            }
            .sidebar::-webkit-scrollbar-thumb:hover {
                background: rgba(255,255,255,0.35);
            }
            .sidebar.mobile-hidden {
                transform: translateX(-100%);
            }
            .sidebar.collapsed {
                width: 88px;
            }
            .sidebar-toggle {
                display: none;
                border: none;
                background: transparent;
                color: var(--text);
                font-size: 1.2rem;
            }
            @media (max-width: 992px) {
                .sidebar {
                    position: fixed;
                    z-index: 1050;
                    height: 100vh;
                    transform: translateX(-100%);
                }
                body.sidebar-open .sidebar {
                    transform: translateX(0);
                }
                .content-area {
                    min-height: 100vh;
                }
                .topbar {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }
                .sidebar-toggle {
                    display: inline-flex;
                }
            }
            .sidebar.collapsed {
                width: 88px;
            }
            .sidebar .nav-link {
                color: rgba(255,255,255,0.9);
                border-radius: 12px;
                padding: 0.75rem 0.85rem;
                margin-bottom: 0.3rem;
                transition: all .2s ease;
            }
            .sidebar .nav-link:hover,
            .sidebar .nav-link.active {
                background: var(--accent);
                color: #fff;
            }
            .sidebar .submenu {
                margin-top: 0.3rem;
                padding-left: 0.5rem;
            }
            .sidebar .submenu .nav-link {
                padding-left: 1.4rem;
                font-size: 0.93rem;
                color: rgba(255,255,255,0.86);
            }
            .sidebar .submenu .nav-link:hover,
            .sidebar .submenu .nav-link.active {
                background: rgba(255,255,255,0.12);
                color: #fff;
            }
            /* Caret transition and rotation */
            .sidebar .nav-link .bi-caret-down-fill {
                transition: transform 0.2s ease-in-out;
            }
            .sidebar .nav-link[aria-expanded="true"] .bi-caret-down-fill {
                transform: rotate(180deg);
            }
            .sidebar .menu-title {
                font-size: 0.72rem;
                letter-spacing: .16em;
                text-transform: uppercase;
                color: rgba(255,255,255,0.65);
                margin: 1rem 0 .45rem;
            }
            .content-area {
                flex: 1;
                display: flex;
                flex-direction: column;
            }
            .topbar {
                position: sticky;
                top: 0;
                z-index: 10;
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(8px);
                border-bottom: 1px solid #e5e7eb;
                padding: 0.95rem 1.3rem;
            }
            .card-soft {
                background: #fff;
                border: 1px solid #eef2f7;
                border-radius: 14px;
                box-shadow: 0 10px 30px rgba(11, 60, 120, 0.06);
            }
            .stat-card { border-top: 4px solid var(--primary); }
            .stat-card.accent { border-top-color: var(--accent); }
            .stat-icon {
                width: 54px;
                height: 54px;
                border-radius: 14px;
                display: grid;
                place-items: center;
                font-size: 1.35rem;
                background: var(--surface);
            }
            .map-panel {
                min-height: 420px;
                border-radius: 16px;
                background: linear-gradient(135deg, #f8fbff 0%, #eef4fb 100%);
                border: 1px solid #e5ebf2;
                position: relative;
                overflow: hidden;
            }
            .map-grid {
                position: absolute;
                inset: 0;
                background-image: linear-gradient(rgba(11,60,120,0.07) 1px, transparent 1px), linear-gradient(90deg, rgba(11,60,120,0.07) 1px, transparent 1px);
                background-size: 38px 38px;
            }
            .pulse-dot {
                position: absolute;
                width: 14px;
                height: 14px;
                border-radius: 50%;
                background: var(--accent);
                box-shadow: 0 0 0 8px rgba(123,30,43,0.16);
            }
            .table-soft thead th {
                color: #6b7280;
                font-weight: 600;
                font-size: 0.9rem;
                background: #fafbfc;
            }
        </style>
    </head>
    <body>
        <div class="app-shell">
            @include('layouts.navigation')
            <div class="content-area">
                <header class="topbar">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold" style="color: var(--primary);">Sistema de Control y Seguimiento de Micros</div>
                            <div class="text-muted small">Línea 61 · Santa Cruz - Bolivia</div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                        
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                                <div>
                                    <div class="fw-semibold small">{{ Auth::user()->name ?? 'Usuario' }}</div>
                                    <div class="text-muted small">{{ Auth::user()->roles->first()?->name ?? 'Administrador' }}</div>
                                </div>
                            </div>
                            <div>
                              <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="nav-link border-0 w-100 text-start"><i class="bi bi-box-arrow-right me-2"></i><span>Cerrar sesión</span></button>
                            </form>
                         </div>
                    </div>
                </header>

                <main class="p-4 p-lg-4">
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
