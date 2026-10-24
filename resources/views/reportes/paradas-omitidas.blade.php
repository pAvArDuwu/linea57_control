@extends('layouts.app')
@section('content')
@vite('resources/css/reportes.css')
<div class="container-fluid py-4">
    @include('reportes.partials.encabezado')

    <div class="card border-0 shadow-sm mb-4 reportes-filter-card">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('reportes.show', $reporteSlug) }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Ruta</label>
                    <select name="ruta_id" class="form-select reportes-control">
                        <option value="">— Todas las rutas —</option>
                        @foreach($opciones['rutas'] as $ruta)
                            <option value="{{ $ruta->id }}" {{ (string) ($filtros['ruta_id'] ?? '') === (string) $ruta->id ? 'selected' : '' }}>
                                {{ $ruta->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
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
                    <label class="form-label small fw-semibold text-muted mb-1">Desde</label>
                    <input type="date" name="desde" value="{{ $filtros['desde'] ?? '' }}" class="form-control reportes-control">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Hasta</label>
                    <input type="date" name="hasta" value="{{ $filtros['hasta'] ?? '' }}" class="form-control reportes-control">
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
