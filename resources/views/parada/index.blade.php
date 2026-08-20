@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);">
                <i class="bi bi-geo-alt-fill me-2"></i>Gestión de Paradas
            </h4>
            <p class="text-muted mb-0">Administra las paradas del sistema de transporte</p>
        </div>
        <a href="{{ route('parada.create') }}" class="btn btn-primary-custom px-4 py-2">
            <i class="bi bi-plus-lg me-2"></i>Nueva Parada
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert"
             style="border-radius: 12px; border-left: 4px solid #198754 !important;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h6 class="mb-0 fw-semibold text-dark">
                    <i class="bi bi-list-ul me-2 text-muted"></i>Lista de Paradas
                </h6>
                <form method="GET" action="{{ route('parada.index') }}" class="d-flex gap-2" style="max-width: 400px; width: 100%;">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="buscar" class="form-control border-start-0 bg-light"
                               placeholder="Buscar paradas..." value="{{ $buscar ?? '' }}"
                               style="border-radius: 0 10px 10px 0;">
                    </div>
                    <button type="submit" class="btn btn-outline-primary" style="border-radius: 10px;">Buscar</button>
                    @if(!empty($buscar))
                        <a href="{{ route('parada.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background: #f8f9fc;">
                            <th class="ps-4 py-3 text-muted fw-semibold" style="font-size: 0.85rem;">No</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.85rem;">Nombre</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.85rem;">Referencia</th>
                            {{--<th class="py-3 text-muted fw-semibold" style="font-size: 0.85rem;">Coordenadas</th>--}}
                            <th class="py-3 text-muted fw-semibold" style="font-size: 0.85rem;">Estado</th>
                            <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size: 0.85rem;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paradas as $parada)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark fw-normal">{{ $loop->iteration + ($paradas->currentPage() - 1) * $paradas->perPage() }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                                             style="width: 36px; height: 36px; background: linear-gradient(135deg, #e8f0fe 0%, #d4e4fc 100%); flex-shrink: 0;">
                                            <i class="bi bi-geo-alt" style="color: var(--primary); font-size: 0.9rem;"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $parada->nombre }}</span>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $parada->referencia ?? '—' }}</td>
                                {{--<td>
                                    @if($parada->latitud && $parada->longitud)
                                        <small class="text-muted font-monospace">
                                            {{ number_format($parada->latitud, 6) }}, {{ number_format($parada->longitud, 6) }}
                                        </small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>--}}
                                <td>
                                    @if($parada->estado === 'activo')
                                        <span class="badge rounded-pill px-3 py-2" style="background: #e6f4ea; color: #1e7e34; font-weight: 500;">
                                            <i class="bi bi-check-circle-fill me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background: #fce8e6; color: #c62828; font-weight: 500;">
                                            <i class="bi bi-x-circle-fill me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        @if($parada->latitud && $parada->longitud)
                                            <button type="button" class="btn btn-sm btn-outline-info btn-action"
                                                    data-bs-toggle="modal" data-bs-target="#mapModal"
                                                    data-lat="{{ $parada->latitud }}" data-lng="{{ $parada->longitud }}"
                                                    data-nombre="{{ $parada->nombre }}" title="Ver en mapa">
                                                <i class="bi bi-map"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('parada.show', $parada->id) }}" class="btn btn-sm btn-outline-primary btn-action" title="Ver detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('parada.edit', $parada->id) }}" class="btn btn-sm btn-outline-warning btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('parada.destroy', $parada->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Está seguro de eliminar esta parada?')"
                                                    class="btn btn-sm btn-outline-danger btn-action" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-3"
                                             style="width: 64px; height: 64px; background: #f0f4f8;">
                                            <i class="bi bi-geo-alt text-muted" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <p class="text-muted mb-1">No se encontraron paradas</p>
                                        <a href="{{ route('parada.create') }}" class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="bi bi-plus-lg me-1"></i>Crear primera parada
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($paradas->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                {{ $paradas->appends(['buscar' => $buscar])->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Map Modal --}}
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-bottom py-3" style="background: var(--primary); color: white;">
                <h6 class="modal-title" id="mapModalLabel">
                    <i class="bi bi-map me-2"></i>Ubicación de la Parada
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="modalMap" style="height: 450px;"></div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        color: white;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(11, 60, 120, 0.25);
    }
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(11, 60, 120, 0.35);
        color: white;
    }
    .btn-action {
        border-radius: 8px;
        padding: 0.35rem 0.55rem;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-1px);
    }
    .table > tbody > tr {
        transition: background-color 0.15s ease;
    }
    .table > tbody > tr:hover {
        background-color: #f8f9fc !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalMap = null;
    var modalMarker = null;

    var mapModal = document.getElementById('mapModal');
    mapModal.addEventListener('shown.bs.modal', function(event) {
        var button = event.relatedTarget;
        var lat = parseFloat(button.getAttribute('data-lat'));
        var lng = parseFloat(button.getAttribute('data-lng'));
        var nombre = button.getAttribute('data-nombre');

        document.getElementById('mapModalLabel').innerHTML = '<i class="bi bi-map me-2"></i>Ubicación: ' + nombre;

        if (!modalMap) {
            modalMap = L.map('modalMap').setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(modalMap);
        } else {
            modalMap.setView([lat, lng], 16);
        }

        if (modalMarker) {
            modalMap.removeLayer(modalMarker);
        }

        modalMarker = L.marker([lat, lng]).addTo(modalMap)
            .bindPopup('<strong>' + nombre + '</strong><br>Lat: ' + lat.toFixed(6) + '<br>Lng: ' + lng.toFixed(6))
            .openPopup();

        setTimeout(function() { modalMap.invalidateSize(); }, 100);
    });
});
</script>
@endsection
