<x-guest-layout title="Confirmar Contraseña">
    <div class="auth-single-screen">
        <div class="auth-single-box">
            <!-- Brand Header -->
            <div class="text-center mb-4">
                <div class="rounded-3 d-inline-flex align-items-center justify-content-center text-white shadow-sm mb-3" 
                     style="width: 52px; height: 52px; background: linear-gradient(135deg, #0B3C78 0%, #7B1E2B 100%);">
                    <i class="bi bi-shield-lock fs-4"></i>
                </div>
                <div class="d-block badge px-2 py-1 rounded-2 mb-2 mx-auto" style="background: rgba(11, 60, 120, 0.1); color: #0B3C78; font-weight: 700; font-size: 0.75rem; width: fit-content;">
                    CONFIRMACIÓN
                </div>
                <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.3px;">Área Protegida</h3>
                <p class="text-muted small mb-0">Por seguridad, confirma tu contraseña antes de continuar.</p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div class="mb-4">
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

                <div class="d-grid">
                    <button type="submit" class="btn-blue-auth">
                        <span>Confirmar</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
