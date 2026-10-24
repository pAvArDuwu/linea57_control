<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <img src="{{ asset('images/logo-linea61.png') }}" alt="Línea 61" class="reportes-logo">
        <div>
            <h4 class="fw-bold mb-1 reportes-title">
                <i class="bi bi-file-earmark-bar-graph-fill me-2 reportes-title-icon"></i>{{ $titulo }}
            </h4>
            <p class="text-muted mb-0 small">{{ $descripcion }}</p>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 reportes-actions">
        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary reportes-action">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
        <button type="button" class="btn btn-outline-primary reportes-action" onclick="window.print()">
            <i class="bi bi-printer me-1"></i>Imprimir
        </button>
        <a href="{{ route('reportes.pdf', array_merge(['reporte' => $reporteSlug], $filtros)) }}"
           class="btn btn-danger reportes-action" target="_blank" rel="noopener">
            <i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF
        </a>
        <a href="{{ route('reportes.excel', array_merge(['reporte' => $reporteSlug], $filtros)) }}"
           class="btn btn-success reportes-action">
            <i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel
        </a>
    </div>
</div>
