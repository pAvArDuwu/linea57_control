<x-guest-layout title="Registro de Usuario">
    <div class="auth-fullscreen flex-column flex-lg-row">
        <!-- Columna Izquierda: Banner Visual con GIF y Pantalla Completa -->
        <div class="col-12 col-lg-5 auth-hero-side" 
             style="background: url('{{ asset('images/ttrafVehiculo2.gif') }}') center/cover no-repeat; min-height: 280px;">
            <div class="auth-hero-overlay"></div>
            
            <div class="auth-hero-content d-flex flex-column justify-content-between h-100">
                <!-- Header Brand -->
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow" 
                         style="width: 54px; height: 54px; background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(123,30,43,0.95) 100%); border: 1.5px solid rgba(255,255,255,0.35);">
                        <i class="bi bi-bus-front fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-white" style="letter-spacing: -0.5px;">Línea 61</h3>
                        <span class="text-white-50 small fw-medium">Control & Monitoreo de Flota</span>
                    </div>
                </div>

                <!-- Mensaje Central -->
                <div class="my-auto py-4 d-none d-lg-block">
                    <span class="badge rounded-pill px-3 py-2 mb-3" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); font-size: 0.82rem;">
                        <i class="bi bi-person-plus-fill text-warning me-1"></i> Registro de Personal
                    </span>
                    <h2 class="display-6 fw-bold text-white mb-3" style="line-height: 1.15; letter-spacing: -0.5px;">
                        Únete a la Red de <br>Control Línea 61
                    </h2>
                    <p class="text-white-50 mb-4 fs-6">
                        Gestión unificada de turnos, asignación de microbuses y seguimiento operativo en tiempo real.
                    </p>
                    
                    <div class="d-flex flex-column gap-3 fs-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle text-success fs-5"></i>
                            <span class="text-white">Monitoreo satelital GPS en vivo</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle text-success fs-5"></i>
                            <span class="text-white">Control de paradas y tiempos</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle text-success fs-5"></i>
                            <span class="text-white">Gestión de flota e internos</span>
                        </div>
                    </div>
                </div>

                <!-- Footer del Banner -->
                <div class="pt-3 border-top border-white border-opacity-15 d-flex justify-content-between align-items-center text-white-50 small">
                    <span>Santa Cruz · Bolivia</span>
                    <span><i class="bi bi-shield-lock me-1 text-success"></i>Registro Seguro</span>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Formulario de Registro Minimalista Pantalla Completa -->
        <div class="col-12 col-lg-7 auth-form-side py-4 py-lg-5">
            <div class="auth-form-container-wide">
                <div class="mb-4">
                    <div class="d-inline-block badge px-2 py-1 rounded-2 mb-2" style="background: rgba(11, 60, 120, 0.1); color: #0B3C78; font-weight: 700; font-size: 0.75rem;">
                        REGISTRO DE USUARIO
                    </div>
                    <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Crear Cuenta</h2>
                    <p class="text-muted small mb-0">Completa el formulario para registrar un nuevo usuario en la plataforma.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row g-3">
                        <!-- Nombres -->
                        <div class="col-12 col-md-6">
                            <label for="name" class="form-label-custom">Nombres</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted px-3" style="border-color: #E2E8F0;">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input id="name" class="form-control form-control-custom border-start-0 ps-2 @error('name') is-invalid @enderror" 
                                       type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="given-name" placeholder="Ej. Carlos">
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Apellidos -->
                        <div class="col-12 col-md-6">
                            <label for="apellido" class="form-label-custom">Apellidos</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted px-3" style="border-color: #E2E8F0;">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input id="apellido" class="form-control form-control-custom border-start-0 ps-2 @error('apellido') is-invalid @enderror" 
                                       type="text" name="apellido" value="{{ old('apellido') }}" required autocomplete="family-name" placeholder="Ej. Rojas Perez">
                            </div>
                            @error('apellido')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Carnet de Identidad -->
                        <div class="col-12 col-md-6">
                            <label for="ci" class="form-label-custom">Carnet de Identidad (CI)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted px-3" style="border-color: #E2E8F0;">
                                    <i class="bi bi-card-text"></i>
                                </span>
                                <input id="ci" class="form-control form-control-custom border-start-0 ps-2 @error('ci') is-invalid @enderror" 
                                       type="text" name="ci" value="{{ old('ci') }}" required placeholder="Ej. 8492019 SC">
                            </div>
                            @error('ci')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div class="col-12 col-md-6">
                            <label for="telefono" class="form-label-custom">Teléfono / Celular</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted px-3" style="border-color: #E2E8F0;">
                                    <i class="bi bi-telephone"></i>
                                </span>
                                <input id="telefono" class="form-control form-control-custom border-start-0 ps-2 @error('telefono') is-invalid @enderror" 
                                       type="text" name="telefono" value="{{ old('telefono') }}" required autocomplete="tel" placeholder="Ej. 70912345">
                            </div>
                            @error('telefono')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="col-12">
                            <label for="email" class="form-label-custom">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted px-3" style="border-color: #E2E8F0;">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input id="email" class="form-control form-control-custom border-start-0 ps-2 @error('email') is-invalid @enderror" 
                                       type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="usuario@correo.com">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="col-12 col-md-6">
                            <label for="password" class="form-label-custom">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted px-3" style="border-color: #E2E8F0;">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input id="password" class="form-control form-control-custom border-start-0 ps-2 @error('password') is-invalid @enderror" 
                                       type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-12 col-md-6">
                            <label for="password_confirmation" class="form-label-custom">Confirmar Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted px-3" style="border-color: #E2E8F0;">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input id="password_confirmation" class="form-control form-control-custom border-start-0 ps-2 @error('password_confirmation') is-invalid @enderror" 
                                       type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repite la contraseña">
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botón de Envío Azul Primario -->
                    <div class="d-grid mt-4 mb-3">
                        <button type="submit" class="btn-blue-auth">
                            <span>Registrarme</span>
                            <i class="bi bi-check-circle"></i>
                        </button>
                    </div>

                    <!-- Separador -->
                    <div class="auth-divider">
                        <span>o</span>
                    </div>

                    <!-- Enlace directo simplificado a Login con Botón Azul Secundario -->
                    <div class="d-grid">
                        <a href="{{ route('login') }}" class="btn-blue-outline">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Iniciar sesión</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
