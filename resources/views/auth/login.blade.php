<x-guest-layout title="Iniciar Sesión">
    <div class="auth-fullscreen flex-column flex-lg-row">
        <!-- Columna Izquierda: Banner Visual Pantalla Completa donde se ve todo el micro -->
        <div class="col-12 col-lg-6 auth-hero-side position-relative" 
             style="background: #071E3D url('{{ asset('images/auth-bg.png') }}') center center / contain no-repeat; min-height: 380px;">
            <div class="auth-hero-overlay" style="background: linear-gradient(180deg, rgba(7, 30, 61, 0.55) 0%, rgba(11, 60, 120, 0.3) 50%, rgba(123, 30, 43, 0.65) 100%);"></div>
            
            <div class="auth-hero-content d-flex flex-column justify-content-between h-100">
                <!-- Header Brand -->
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow" 
                         style="width: 56px; height: 56px; background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(123,30,43,0.95) 100%); border: 1.5px solid rgba(255,255,255,0.35);">
                        <i class="bi bi-bus-front fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-white" style="letter-spacing: -0.5px;">Línea 61</h3>
                        <span class="text-white-50 small fw-medium">Control & Monitoreo de Flota</span>
                    </div>
                </div>

                <!-- Mensaje Inferior Minimalista (deja libre el centro para ver el micro) -->
                <div class="mt-auto pt-4">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(7, 30, 61, 0.85); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2);">
                        <i class="bi bi-broadcast text-info"></i>
                        <span class="text-white small fw-semibold">Monitoreo Satelital en Tiempo Real</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-white-50 small pt-2 border-top border-white border-opacity-15">
                        <span>Santa Cruz · Bolivia</span>
                        <span><i class="bi bi-shield-check me-1 text-success"></i>Acceso Seguro SSL</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Formulario Minimalista Pantalla Completa -->
        <div class="col-12 col-lg-6 auth-form-side">
            <div class="auth-form-container">
                <div class="mb-4">
                    <div class="d-inline-block badge px-2 py-1 rounded-2 mb-2" style="background: rgba(11, 60, 120, 0.1); color: #0B3C78; font-weight: 700; font-size: 0.75rem;">
                        LÍNEA 61 · CONTROL OPERATIVO
                    </div>
                    <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Iniciar Sesión</h2>
                    <p class="text-muted small mb-0">Ingresa para administrar la operación diaria de la línea.</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-3" role="alert">
                        <i class="bi bi-check-circle me-1"></i>{{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label-custom">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted px-3" style="border-color: #E2E8F0;">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input id="email" class="form-control form-control-custom border-start-0 ps-2 @error('email') is-invalid @enderror" 
                                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="usuario@correo.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label-custom">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted px-3" style="border-color: #E2E8F0;">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input id="password" class="form-control form-control-custom border-start-0 ps-2 @error('password') is-invalid @enderror" 
                                   type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Recordarme & Recuperar Contraseña -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check mb-0">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember" style="cursor: pointer;">
                            <label class="form-check-label text-muted small" for="remember_me" style="cursor: pointer; user-select: none;">Recordarme</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="link-guindo" href="{{ route('password.request') }}">
                                Recuperar contraseña
                            </a>
                        @endif
                    </div>

                    <!-- Botón de Envío Azul Primario -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn-blue-auth">
                            <span>Ingresar</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>

                    <!-- Separador -->
                    <div class="auth-divider">
                        <span>o</span>
                    </div>

                    <!-- Enlace directo simplificado a Registro con Botón Azul Secundario -->
                    @if (Route::has('register'))
                        <div class="d-grid">
                            <a href="{{ route('register') }}" class="btn-blue-outline">
                                <i class="bi bi-person-plus"></i>
                                <span>Crear cuenta</span>
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
