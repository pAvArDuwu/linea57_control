<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-primary">Detalle de la Asociación</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card bg-white shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold text-primary mb-4">Información de la Relación Ruta-Parada</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Ruta</p>
                        <p class="fs-5 fw-semibold">
                            @if($rutaParada->ruta)
                                {{ $rutaParada->ruta->nombre_ruta }} <span class="text-muted fs-6">({{ $rutaParada->ruta->origen }} - {{ $rutaParada->ruta->destino }})</span>
                            @else
                                <span class="text-danger">Ruta no asignada/eliminada</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6">
                        <p class="text-muted mb-1">Parada</p>
                        <p class="fs-5 fw-semibold">
                            @if($rutaParada->parada)
                                {{ $rutaParada->parada->nombre }} <span class="text-muted fs-6">({{ $rutaParada->parada->referencia }})</span>
                            @else
                                <span class="text-danger">Parada no asignada/eliminada</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-4">
                        <p class="text-muted mb-1">Orden en el recorrido</p>
                        <p class="fs-5 fw-semibold">{{ $rutaParada->orden }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="text-muted mb-1">Sentido</p>
                        <p class="fs-5"><span class="badge bg-secondary px-3 py-2 fs-6">{{ ucfirst($rutaParada->sentido) }}</span></p>
                    </div>

                    <div class="col-md-4">
                        <p class="text-muted mb-1">Estado</p>
                        <p class="fs-5">
                            <span class="badge {{ $rutaParada->estado === 'activo' ? 'bg-success' : 'bg-danger' }} px-3 py-2 fs-6">
                                {{ ucfirst($rutaParada->estado) }}
                            </span>
                        </p>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('rutas-paradas.index') }}" class="btn btn-outline-secondary">Volver al Listado</a>
                    <a href="{{ route('rutas-paradas.edit', $rutaParada->id) }}" class="btn btn-warning px-4">Editar</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
