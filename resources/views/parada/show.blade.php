@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('parada.index') }}" class="text-decoration-none">Paradas</a></li>
                    <li class="breadcrumb-item active">{{ $parada->nombre }}</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #e8f0fe 0%, #d4e4fc 100%);">
                                <i class="bi bi-geo-alt-fill" style="color: var(--primary);"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: var(--primary);">Detalle de Parada</h5>
                                <small class="text-muted">Información completa de la parada</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('parada.edit', $parada->id) }}" class="btn btn-outline-warning btn-sm px-3" style="border-radius: 8px;">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </a>
                            <form action="{{ route('parada.destroy', $parada->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Está seguro de eliminar esta parada?')"
                                        class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px;">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    {{-- Información --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1"><i class="bi bi-tag me-1"></i>Nombre</div>
                                <div class="fw-semibold">{{ $parada->nombre }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1"><i class="bi bi-signpost-2 me-1"></i>Referencia</div>
                                <div class="fw-semibold">{{ $parada->referencia ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1"><i class="bi bi-toggle-on me-1"></i>Estado</div>
                                <div>
                                    @if($parada->estado === 'activo')
                                        <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34;">
                                            <i class="bi bi-check-circle-fill me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background: #fce8e6; color: #c62828;">
                                            <i class="bi bi-x-circle-fill me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Coordenadas --}}
                    {{--<div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1"><i class="bi bi-crosshair me-1"></i>Latitud</div>
                                <div class="fw-semibold font-monospace">{{ $parada->latitud ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f8f9fc;">
                                <div class="text-muted small mb-1"><i class="bi bi-crosshair me-1"></i>Longitud</div>
                                <div class="fw-semibold font-monospace">{{ $parada->longitud ?? '—' }}</div>
                            </div>
                        </div>
                    </div>--}}

                    {{-- Map --}}
                    @if($parada->latitud && $parada->longitud)
                        <h6 class="fw-bold mb-3" style="color: var(--primary);">
                            <i class="bi bi-map me-2"></i>Ubicación en el Mapa
                        </h6>
                        <div class="border rounded-3 overflow-hidden" style="border-radius: 14px !important; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
                            <div id="showMap" style="height: 400px;"></div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                    <a href="{{ route('parada.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
                        <i class="bi bi-arrow-left me-2"></i>Volver a la lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($parada->latitud && $parada->longitud)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = {{ $parada->latitud }};
    var lng = {{ $parada->longitud }};

    var map = L.map('showMap').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup('<strong>{{ $parada->nombre }}</strong><br>{{ $parada->referencia ?? "" }}<br><small class="text-muted">Lat: ' + lat.toFixed(6) + ' | Lng: ' + lng.toFixed(6) + '</small>')
        .openPopup();

    setTimeout(function() { map.invalidateSize(); }, 200);
});
</script>
@endif
@endsection
