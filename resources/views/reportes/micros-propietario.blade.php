@extends('layouts.app')
@section('content')
@vite('resources/css/reportes.css')
<div class="container-fluid py-4">
    @include('reportes.partials.encabezado')

    <div class="card border-0 shadow-sm mb-4 reportes-filter-card">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('reportes.show', $reporteSlug) }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Propietario</label>
                    <select name="propietario_id" class="form-select reportes-control">
                        <option value="">— Todos los propietarios —</option>
                        @foreach($opciones['propietarios'] as $propietario)
                            <option value="{{ $propietario->id }}" {{ (string) ($filtros['propietario_id'] ?? '') === (string) $propietario->id ? 'selected' : '' }}>
                                {{ trim($propietario->nombre.' '.$propietario->apellido) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Interno asignado</label>
                    <select name="interno_id" class="form-select reportes-control">
                        <option value="">— Todos —</option>
                        @foreach($opciones['internos'] as $interno)
                            <option value="{{ $interno->id }}" {{ (string) ($filtros['interno_id'] ?? '') === (string) $interno->id ? 'selected' : '' }}>
                                {{ $interno->numero_interno }}
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
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="buscar" class="form-control border-start-0 reportes-control"
                               placeholder="Placa, marca, modelo, interno..." value="{{ $filtros['buscar'] ?? '' }}">
                    </div>
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
