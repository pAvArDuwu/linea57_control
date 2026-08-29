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
                --accent-hover: #941B2D;
                --card-navy: #182C4D;
                --surface: #F4F6F9;
                --text: #1f2937;
                --card-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.18), 0 8px 18px -6px rgba(15, 23, 42, 0.12);
            }
            body {
                font-family: 'Inter', sans-serif;
                background: var(--surface);
                color: var(--text);
            }
            .card {
                background: #ffffff !important;
                border: 0 !important;
                border-radius: 16px !important;
                box-shadow: var(--card-shadow) !important;
                overflow: hidden;
                height: 100%;
            }
            .card-header {
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
                color: #ffffff !important;
                border-bottom: 1px solid rgba(255,255,255,0.12) !important;
                border-radius: 16px 16px 0 0 !important;
            }
            .card-header .text-muted,
            .card-header .small,
            .card-header .fw-bold,
            .card-header h5,
            .card-header h4,
            .card-header span,
            .card-header i {
                color: #ffffff !important;
            }
            .card-body {
                background: #ffffff;
                border-radius: 0 0 16px 16px;
            }
            .card-footer {
                background: #ffffff !important;
                border-top: 1px solid #eef2f7 !important;
            }
            .dashboard-card {
                background-color: #182C4D !important;
                color: #ffffff !important;
                border: 1px solid rgba(255, 255, 255, 0.09) !important;
                border-radius: 16px !important;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.2) !important;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .app-shell {
                min-height: 100vh;
                display: flex;
                background: var(--surface);
            }
            .sidebar {
                width: 285px;
                min-width: 285px;
                background: linear-gradient(180deg, #071E3D 0%, #0B3C78 60%, #162E52 100%);
                color: white;
                padding: 1.4rem 1rem;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
                overflow-x: hidden;
                transition: transform .25s ease, width .25s ease;
                z-index: 100;
                box-shadow: 4px 0 20px rgba(7, 30, 61, 0.15);
            }
            /* Brand Logo & Title Styling */
            .brand-logo {
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, rgba(255,255,255,0.22) 0%, rgba(123,30,43,0.85) 100%);
                border: 1.5px solid rgba(255,255,255,0.3);
                color: #ffffff;
                box-shadow: 0 4px 12px rgba(0,0,0,0.25);
                flex-shrink: 0;
            }
            .brand-title {
                font-size: 1.35rem;
                letter-spacing: -0.3px;
                line-height: 1.15;
            }
            .brand-subtitle {
                font-size: 0.78rem;
                letter-spacing: 0.4px;
                color: rgba(255,255,255,0.75) !important;
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
            
            /* Sidebar Nav Links & Submenus: Forced single-line with nowrap */
            .sidebar .nav-link {
                color: rgba(255,255,255,0.9);
                border-radius: 10px;
                padding: 0.68rem 0.85rem;
                margin-bottom: 0.25rem;
                transition: all .2s ease;
                display: flex;
                align-items: center;
                white-space: nowrap !important;
                text-wrap: nowrap !important;
                overflow: hidden;
                font-size: 0.92rem;
                font-weight: 500;
            }
            .sidebar .nav-link i {
                font-size: 1.1rem;
                flex-shrink: 0;
            }
            .sidebar .nav-link span {
                white-space: nowrap !important;
                text-wrap: nowrap !important;
                overflow: hidden;
                text-overflow: ellipsis;
                display: inline-block;
            }
            .sidebar .nav-link:hover {
                background: rgba(255, 255, 255, 0.12);
                color: #fff;
                transform: translateX(3px);
            }
            .sidebar .nav-link.active {
                background: linear-gradient(135deg, var(--accent) 0%, #9E2235 100%);
                color: #fff;
                box-shadow: 0 4px 14px rgba(123, 30, 43, 0.45);
                font-weight: 600;
            }
            
            /* Submenus single-line */
            .sidebar .submenu {
                margin-top: 0.2rem;
                padding-left: 0.4rem;
            }
            .sidebar .submenu .nav-link {
                padding-left: 1.25rem;
                font-size: 0.88rem;
                color: rgba(255,255,255,0.85);
                white-space: nowrap !important;
                text-wrap: nowrap !important;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .sidebar .submenu .nav-link:hover,
            .sidebar .submenu .nav-link.active {
                background: rgba(255,255,255,0.15);
                color: #fff;
            }
            
            /* Caret transition and rotation */
            .sidebar .nav-link .bi-caret-down-fill {
                transition: transform 0.2s ease-in-out;
                margin-left: auto;
                flex-shrink: 0;
            }
            .sidebar .nav-link[aria-expanded="true"] .bi-caret-down-fill {
                transform: rotate(180deg);
            }
            .sidebar .menu-title {
                font-size: 0.72rem;
                letter-spacing: .15em;
                text-transform: uppercase;
                font-weight: 700;
                color: rgba(255,255,255,0.6);
                margin: 1.2rem 0 .45rem 0.25rem;
                white-space: nowrap !important;
                text-wrap: nowrap !important;
            }
            .content-area {
                flex: 1;
                display: flex;
                flex-direction: column;
                min-width: 0;
            }
            .topbar {
                position: sticky;
                top: 0;
                z-index: 10;
                background: rgba(255,255,255,0.96);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid #e2e8f0;
                padding: 0.95rem 1.6rem;
                box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            }
            .topbar-brand-title {
                font-size: 1.1rem;
                font-weight: 700;
                color: var(--primary);
                letter-spacing: -0.2px;
            }
            .topbar-brand-subtitle {
                font-size: 0.8rem;
                color: #64748b;
                font-weight: 500;
            }
            .logout-btn {
                color: #64748b;
                background: #f1f5f9;
                border-radius: 8px;
                padding: 0.4rem 0.85rem;
                font-size: 0.85rem;
                font-weight: 600;
                transition: all 0.2s ease;
                border: 1px solid #e2e8f0;
            }
            .logout-btn:hover {
                background: #fee2e2;
                color: var(--accent);
                border-color: #fca5a5;
            }
        </style>
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
