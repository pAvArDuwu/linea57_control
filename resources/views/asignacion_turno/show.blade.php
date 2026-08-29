@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="mb-3">
                <a href="{{ route('asignacion-turno.index') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left me-1"></i>Volver a asignaciones
                </a>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                {{-- Header --}}
                <div class="card-header py-3 px-4" style="border-radius: 16px 16px 0 0; background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-0 fw-bold text-white">
                                <i class="bi bi-calendar2-check me-2"></i>Detalle de Asignación
                            </h5>
                            <p class="text-white-50 small mb-0">
                                {{ \Carbon\Carbon::parse($asignacion->fecha)->format('l, d \d\e F \d\e Y') }}
                            </p>
                        </div>
                        @php
                            $estadoBadge = [
                                'pendiente'  => ['bg' => 'rgba(255,249,196,0.9)', 'color' => '#795548'],
                                'en_curso'   => ['bg' => 'rgba(227,242,253,0.9)', 'color' => '#1565c0'],
                                'completado' => ['bg' => 'rgba(230,244,234,0.9)', 'color' => '#1e7e34'],
                                'retrasado'  => ['bg' => 'rgba(255,243,224,0.9)', 'color' => '#e65100'],
                                'cancelado'  => ['bg' => 'rgba(240,240,240,0.9)', 'color' => '#6c757d'],
                            ];
                            $badge = $estadoBadge[$asignacion->estado] ?? ['bg' => 'rgba(240,240,240,0.9)', 'color' => '#6c757d'];
                        @endphp
                        <span class="badge rounded-pill px-3 py-2 fw-semibold"
                              style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }};">
                            {{ ucfirst(str_replace('_', ' ', $asignacion->estado)) }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        {{-- Turno --}}
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: #f8f9fc; border: 1px solid #eef2f7;">
                                <div class="text-muted small fw-semibold mb-2">
                                    <i class="bi bi-clock me-1"></i>Turno
                                </div>
                                @php
                                    $iconos = ['mañana' => '☀️', 'tarde' => '🌤️', 'noche' => '🌙'];
                                @endphp
                                <div class="fw-bold fs-5">
                                    {{ $iconos[$asignacion->turno?->nombre ?? ''] ?? '' }}
                                    {{ $asignacion->turno?->nombre_label ?? '—' }}
                                </div>
                                @if($asignacion->turno)
                                    <div class="text-muted small mt-1">
                                        Turno asignado sin horario programado visible
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Ruta --}}
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: #f8f9fc; border: 1px solid #eef2f7;">
                                <div class="text-muted small fw-semibold mb-2">
                                    <i class="bi bi-signpost-2 me-1"></i>Ruta
                                </div>
                                <div class="fw-bold">{{ $asignacion->ruta?->nombre ?? '—' }}</div>
                            </div>
                        </div>

                        {{-- Conductor --}}
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: #f8f9fc; border: 1px solid #eef2f7;">
                                <div class="text-muted small fw-semibold mb-2">
                                    <i class="bi bi-person-badge me-1"></i>Conductor
                                </div>
                                @if($asignacion->conductor)
                                    <div class="fw-bold">{{ $asignacion->conductor->nombre }} {{ $asignacion->conductor->apellido }}</div>
                                    <div class="text-muted small">CI: {{ $asignacion->conductor->ci }}</div>
                                @else
                                    <div class="text-muted">—</div>
                                @endif
                            </div>
                        </div>

                        {{-- Micro / Interno --}}
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: #f8f9fc; border: 1px solid #eef2f7;">
                                <div class="text-muted small fw-semibold mb-2">
                                    <i class="bi bi-bus-front me-1"></i>Micro / Interno
                                </div>
                                @if($asignacion->micro)
                                    <div class="fw-bold">{{ $asignacion->micro->placa }}</div>
                                    <div class="text-muted small">{{ $asignacion->micro->marca }} {{ $asignacion->micro->modelo }}</div>
                                @endif
                                @if($asignacion->interno)
                                    <div class="text-muted small mt-1">
                                        <i class="bi bi-hdd-stack me-1"></i>Interno {{ $asignacion->interno->numero_interno }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Horas operativas --}}
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f8f9fc; border: 1px solid #eef2f7;">
                                <div class="text-muted small fw-semibold mb-2">
                                    <i class="bi bi-clock-history me-1"></i>Horario operativo
                                </div>
                                <div class="d-flex gap-4">
                                    <div>
                                        <div class="text-muted small">Salida</div>
                                        <div class="fw-bold font-monospace">
                                            {{ $asignacion->hora_salida ? \Carbon\Carbon::parse($asignacion->hora_salida)->format('H:i') : '—' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Llegada</div>
                                        <div class="fw-bold font-monospace">
                                            {{ $asignacion->hora_llegada ? \Carbon\Carbon::parse($asignacion->hora_llegada)->format('H:i') : '—' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Observaciones --}}
                        @if($asignacion->observaciones)
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f8f9fc; border: 1px solid #eef2f7;">
                                <div class="text-muted small fw-semibold mb-2">
                                    <i class="bi bi-chat-text me-1"></i>Observaciones
                                </div>
                                <div class="small">{{ $asignacion->observaciones }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card-footer bg-white border-top py-3 px-4 d-flex gap-2 justify-content-end" style="border-radius: 0 0 16px 16px;">
                    <a href="{{ route('asignacion-turno.index') }}" class="btn btn-primary px-4" style="border-radius: 10px;">
                        <i class="bi bi-arrow-left me-1"></i>Volver
                    </a>
                    @if($asignacion->estado !== 'cancelado')
                        <form action="{{ route('asignacion-turno.destroy', $asignacion->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('¿Cancelar esta asignación?')"
                                    class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
                                <i class="bi bi-x-circle me-1"></i>Cancelar asignación
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
