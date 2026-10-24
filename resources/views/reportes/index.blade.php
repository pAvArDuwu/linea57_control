@extends('layouts.app')
@section('content')
@vite('resources/css/reportes.css')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 reportes-title">
                <i class="bi bi-bar-chart-line-fill me-2 reportes-title-icon"></i>Módulo de Reportes
            </h4>
            <p class="text-muted mb-0">Listados de consulta del sistema con exportación a PDF y Excel</p>
        </div>
    </div>

    <div class="row g-4">
        @foreach($reportes as $item)
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100 reportes-card">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center reportes-card-avatar">
                                <i class="bi bi-file-earmark-bar-graph"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $item['titulo'] }}</h6>
                        </div>
                        <p class="text-muted small flex-grow-1">{{ $item['descripcion'] }}</p>
                        <a href="{{ route('reportes.show', $item['slug']) }}" class="btn btn-primary reportes-card-action">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Ver reporte
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
