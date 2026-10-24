@extends('layouts.app')
@section('content')
@vite('resources/css/reportes.css')
<div class="container-fluid py-4">
    @include('reportes.partials.encabezado')

    @php
        $anioSeleccionado = (int) ($filtros['anio'] ?? now()->year);
        $mesSeleccionado = (int) ($filtros['mes'] ?? now()->month);
    @endphp

    <div class="card border-0 shadow-sm mb-4 reportes-filter-card">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('reportes.show', $reporteSlug) }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Mes</label>
                    <select name="mes" class="form-select reportes-control">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $mesSeleccionado === $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Año</label>
                    <select name="anio" class="form-select reportes-control">
                        @foreach(range(now()->year - 3, now()->year + 1) as $anio)
                            <option value="{{ $anio }}" {{ $anioSeleccionado === $anio ? 'selected' : '' }}>{{ $anio }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-semibold text-muted mb-1">Conductor</label>
                    <select name="conductor_id" class="form-select reportes-control">
                        <option value="">— Todos los conductores —</option>
                        @foreach($opciones['conductores'] as $conductor)
                            <option value="{{ $conductor->id }}" {{ (string) ($filtros['conductor_id'] ?? '') === (string) $conductor->id ? 'selected' : '' }}>
                                {{ $conductor->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Estado</label>
                    <select name="estado" class="form-select reportes-control">
                        <option value="">— Todos —</option>
                        @foreach($opciones['estados'] as $valor => $etiqueta)
                            <option value="{{ $valor }}" {{ ($filtros['estado'] ?? '') === $valor ? 'selected' : '' }}>{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 reportes-filter-button">
                        <i class="bi bi-funnel me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('reportes.show', $reporteSlug) }}" class="btn btn-outline-secondary reportes-filter-button" title="Limpiar filtros">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    @include('reportes.partials.resumen')
    @include('reportes.partials.tabla')
</div>
@endsection
