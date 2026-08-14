@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="mb-3">
                <a href="{{ route('turno.index') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left me-1"></i>Volver al catálogo
                </a>
            </div>
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold" style="color: var(--primary);">
                                <i class="bi bi-clock-fill me-2"></i>Detalle del Turno
                            </h5>
                            <p class="text-muted small mb-0">Información del catálogo de horarios</p>
                        </div>
                        @if($turno->estado === 'activo')
                            <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34;">Activo</span>
                        @else
                            <span class="badge rounded-pill px-3 py-2" style="background: #f0f0f0; color: #6c757d;">Inactivo</span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    @php
                        $iconos = ['mañana' => 'bi-sun', 'tarde' => 'bi-cloud-sun', 'noche' => 'bi-moon-stars'];
                        $colores = ['mañana' => '#fff8e1', 'tarde' => '#fff3e0', 'noche' => '#ede7f6'];
                        $iconColor = ['mañana' => '#f9a825', 'tarde' => '#ef6c00', 'noche' => '#5e35b1'];
                    @endphp

                    {{-- Ícono grande del turno --}}
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2"
                             style="width: 72px; height: 72px; background: {{ $colores[$turno->nombre] ?? '#e3f2fd' }}; font-size: 2rem; color: {{ $iconColor[$turno->nombre] ?? '#0B3C78' }};">
                            <i class="bi {{ $iconos[$turno->nombre] ?? 'bi-clock' }}"></i>
                        </div>
                        <h4 class="fw-bold mb-0" style="color: var(--primary);">{{ $turno->nombre_label }}</h4>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small fw-semibold mb-1">Hora de Inicio</div>
                                <div class="fw-bold font-monospace fs-5">
                                    {{ \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small fw-semibold mb-1">Hora de Fin</div>
                                <div class="fw-bold font-monospace fs-5">
                                    {{ \Carbon\Carbon::parse($turno->hora_fin)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                        @if($turno->descripcion)
                        <div class="col-12">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small fw-semibold mb-1">Descripción</div>
                                <div>{{ $turno->descripcion }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="col-12">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small fw-semibold mb-1">Asignaciones registradas</div>
                                <div class="fw-bold">{{ $turno->asignaciones()->count() }} asignación(es)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top pt-3 px-4 pb-4 d-flex gap-2 justify-content-end" style="border-radius: 0 0 16px 16px;">
                    <a href="{{ route('turno.edit', $turno->id) }}"
                       class="btn btn-outline-warning px-4" style="border-radius: 10px;">
                        <i class="bi bi-pencil me-1"></i>Editar
                    </a>
                    @if($turno->estado === 'activo')
                        <form action="{{ route('turno.destroy', $turno->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('¿Desactivar el turno {{ $turno->nombre_label }}?')"
                                    class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
                                <i class="bi bi-pause-circle me-1"></i>Desactivar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
