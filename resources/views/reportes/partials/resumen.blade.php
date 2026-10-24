<div class="row g-3 mb-4">
    @foreach($resumen as $etiqueta => $valor)
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100 reportes-stat-card">
                <div class="card-body py-3">
                    <div class="text-muted reportes-stat-label">{{ $etiqueta }}</div>
                    <div class="fw-bold text-primary reportes-stat-value">{{ $valor }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
