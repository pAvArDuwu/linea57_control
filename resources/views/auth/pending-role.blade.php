<x-guest-layout title="Perfil pendiente">
    <div class="auth-single-screen">
        <div class="auth-single-box" style="max-width: 760px;">
            <div class="text-center mb-4">
                <div class="rounded-3 d-inline-flex align-items-center justify-content-center text-white shadow-sm mb-3"
                     style="width: 64px; height: 64px; background: linear-gradient(135deg, #0B3C78 0%, #7B1E2B 100%);">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
                <div class="d-block badge px-2 py-1 rounded-2 mb-2 mx-auto" style="background: rgba(11, 60, 120, 0.08); color: #0B3C78; font-weight: 700; font-size: 0.76rem; width: fit-content; letter-spacing: 0.08em;">
                    ACCESO PENDIENTE
                </div>
                <h3 class="fw-bold text-dark mb-2" style="letter-spacing: -0.4px;">Tu perfil aún no está activo</h3>
                <p class="text-muted mb-0" style="line-height: 1.6;">
                    Tu correo ya fue validado, pero aún no se te ha asignado un rol dentro del sistema.
                    Mientras tanto, no podrás acceder a la operación ni al panel principal.
                </p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(180deg, #f9fbff 0%, #f2f6fb 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(11, 60, 120, 0.1); color: #0B3C78;">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Estado actual</div>
                            <div class="small text-muted">Validación de correo completa</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="rounded-3 p-3 h-100" style="background: rgba(11,60,120,0.04); border: 1px solid rgba(11,60,120,0.08);">
                                <div class="small text-muted mb-2">1. Tu correo</div>
                                <div class="fw-semibold text-dark">{{ auth()->user()->email ?? 'usuario@correo.com' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rounded-3 p-3 h-100" style="background: rgba(123,30,43,0.04); border: 1px solid rgba(123,30,43,0.08);">
                                <div class="small text-muted mb-2">2. Estado del usuario</div>
                                <div class="fw-semibold text-dark">Sin rol asignado</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                <div class="rounded-3 p-3 border" style="background: rgba(12, 74, 110, 0.03); border-color: rgba(12,74,110,0.12);">
                    <div class="fw-semibold text-dark mb-2">Qué sigue</div>
                    <ul class="mb-0 ps-3 text-muted small" style="line-height: 1.8;">
                        <li>Se revisará tu perfil y se te asignará el acceso correspondiente.</li>
                        <li>Cuando el administrador o responsable confirme tu rol, podrás ingresar al sistema.</li>
                        <li>Durante este tiempo solo puedes esperar la validación del acceso.</li>
                    </ul>
                </div>

                <div class="rounded-3 p-3 border" style="background: rgba(123,30,43,0.03); border-color: rgba(123,30,43,0.12);">
                    <div class="fw-semibold text-dark mb-2">¿Qué puedes hacer ahora?</div>
                    <ul class="mb-0 ps-3 text-muted small" style="line-height: 1.8;">
                        <li>Revisa tu correo para confirmar que te llegó la validación.</li>
                        <li>Espera a que te asignen el rol correcto dentro del sistema.</li>
                        <li>Si tienes dudas, contacta con administración.</li>
                    </ul>
                </div>
            </div>

            <div class="d-flex flex-column gap-2 mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-blue-auth w-100">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Cerrar sesión</span>
                    </button>
                </form>

                <a href="{{ url('/') }}" class="btn btn-outline-secondary w-100 rounded-3">
                    <i class="bi bi-house-door me-2"></i>Volver al inicio
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
