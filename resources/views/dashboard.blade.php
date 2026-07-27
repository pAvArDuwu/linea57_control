@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 fw-semibold mb-1" style="color: var(--primary);">Panel general</h2>
            <p class="text-muted mb-0">Monitoreo operativo de la línea 61 · Santa Cruz</p>
        </div>
        <div class="text-muted small">Última sincronización: 08:42</div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-soft stat-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Micros activos</div>
                        <div class="display-6 fw-semibold">24</div>
                    </div>
                    <div class="stat-icon text-primary"><i class="bi bi-bus-front"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-soft stat-card accent p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Conductores disponibles</div>
                        <div class="display-6 fw-semibold">18</div>
                    </div>
                    <div class="stat-icon" style="color: var(--accent);"><i class="bi bi-person-badge"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-soft stat-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Recorridos activos</div>
                        <div class="display-6 fw-semibold">11</div>
                    </div>
                    <div class="stat-icon text-primary"><i class="bi bi-map"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-soft stat-card accent p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Micros fuera de servicio</div>
                        <div class="display-6 fw-semibold">3</div>
                    </div>
                    <div class="stat-icon" style="color: var(--accent);"><i class="bi bi-tools"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card-soft p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-semibold mb-1">Monitoreo en tiempo real</h5>
                        <p class="text-muted small mb-0">Seguimiento de la flota sobre la ruta principal de la ciudad</p>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(11,60,120,0.08); color: var(--primary);">En vivo</span>
                </div>
                <div class="map-panel">
                    <div class="map-grid"></div>
                    <div class="pulse-dot" style="top: 24%; left: 20%;"></div>
                    <div class="pulse-dot" style="top: 42%; left: 60%;"></div>
                    <div class="pulse-dot" style="top: 68%; left: 35%;"></div>
                    <div class="position-absolute bottom-0 start-0 p-3">
                        <div class="card-soft px-3 py-2 small">
                            <div class="fw-semibold">Ruta 61 · Centro - Terminal</div>
                            <div class="text-muted">Última actualización hace 2 min</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card-soft p-4">
                <h5 class="fw-semibold mb-3">Actividad reciente</h5>
                <div class="d-grid gap-3">
                    <div class="border rounded-3 p-3">
                        <div class="fw-semibold">Inicio de recorrido</div>
                        <div class="text-muted small">Micro 201 · Línea 61 · 07:20</div>
                    </div>
                    <div class="border rounded-3 p-3">
                        <div class="fw-semibold">Fin de recorrido</div>
                        <div class="text-muted small">Micro 145 · Terminal · 06:55</div>
                    </div>
                    <div class="border rounded-3 p-3">
                        <div class="fw-semibold">Última ubicación recibida</div>
                        <div class="text-muted small">Av. Ballivián · Lat -17.78 / Lon -63.18</div>
                    </div>
                    <div class="border rounded-3 p-3" style="border-color: rgba(123,30,43,0.2) !important;">
                        <div class="fw-semibold" style="color: var(--accent);">Alerta</div>
                        <div class="text-muted small">Micro 308 con retraso de 12 min</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-soft p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-semibold mb-1">Estado de la flota</h5>
                <p class="text-muted small mb-0">Última actualización del sistema</p>
            </div>
            <a href="{{ route('micro.index') }}" class="btn btn-sm text-white" style="background: var(--primary);">Ver micros</a>
        </div>
        <div class="table-responsive">
            <table class="table table-soft align-middle mb-0">
                <thead>
                    <tr>
                        <th>Unidad</th>
                        <th>Conductor</th>
                        <th>Ruta</th>
                        <th>Estado</th>
                        <th>Velocidad</th>
                        <th>Última actualización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">Micro 201</td>
                        <td>René Morales</td>
                        <td>Ruta 61</td>
                        <td><span class="badge rounded-pill px-3 py-2" style="background: rgba(11,60,120,0.08); color: var(--primary);">En recorrido</span></td>
                        <td>38 km/h</td>
                        <td>08:41</td>
                        <td><a href="#" class="text-decoration-none" style="color: var(--accent);">Ver detalle</a></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Micro 145</td>
                        <td>María Paredes</td>
                        <td>Ruta 61</td>
                        <td><span class="badge rounded-pill px-3 py-2" style="background: rgba(123,30,43,0.1); color: var(--accent);">En terminal</span></td>
                        <td>0 km/h</td>
                        <td>08:30</td>
                        <td><a href="#" class="text-decoration-none" style="color: var(--accent);">Ver detalle</a></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Micro 308</td>
                        <td>Jorge Lanza</td>
                        <td>Ruta 61</td>
                        <td><span class="badge rounded-pill px-3 py-2" style="background: rgba(123,30,43,0.12); color: var(--accent);">Retrasado</span></td>
                        <td>24 km/h</td>
                        <td>08:35</td>
                        <td><a href="#" class="text-decoration-none" style="color: var(--accent);">Ver detalle</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
