<x-guest-layout title="Verificar Correo">
    <div class="auth-single-screen">
        <div class="auth-single-box">
            <!-- Brand Header -->
            <div class="text-center mb-4">
                <div class="rounded-3 d-inline-flex align-items-center justify-content-center text-white shadow-sm mb-3" 
                     style="width: 52px; height: 52px; background: linear-gradient(135deg, #0B3C78 0%, #7B1E2B 100%);">
                    <i class="bi bi-envelope-check fs-4"></i>
                </div>
                <div class="d-block badge px-2 py-1 rounded-2 mb-2 mx-auto" style="background: rgba(11, 60, 120, 0.1); color: #0B3C78; font-weight: 700; font-size: 0.75rem; width: fit-content;">
                    VERIFICACIÓN
                </div>
                <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.3px;">Verifica tu Correo</h3>
                <p class="text-muted small mb-0">Hemos enviado un enlace de confirmación a tu dirección de correo electrónico.</p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success py-2 px-3 small rounded-3 mb-3" role="alert">
                    <i class="bi bi-check-circle me-1"></i>Se ha enviado un nuevo enlace de verificación a tu correo.
                </div>
            @endif

            <div class="d-flex flex-column gap-2 mt-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-blue-auth w-100">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span>Reenviar correo de verificación</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center mt-2">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none small text-muted">
                        <i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
