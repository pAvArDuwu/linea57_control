<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-primary">Gestión de Rutas y Paradas (Relación M:N)</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Asociación de Paradas a Rutas</h5>
                    <a href="{{ route('rutas-paradas.create') }}" class="btn btn-primary">Asociar Parada a Ruta</a>
                </div>

                <form method="GET" action="{{ route('rutas-paradas.index') }}" class="d-flex gap-2 mb-3">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar por ruta o parada..." value="{{ $buscar ?? '' }}">
                    <button type="submit" class="btn btn-secondary">Buscar</button>
                    @if(!empty($buscar))
                        <a href="{{ route('rutas-paradas.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Ruta</th>
                                <th>Parada</th>
                                <th>Orden</th>
                                <th>Sentido</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rutasParadas as $rp)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($rp->ruta)
                                            <strong>{{ $rp->ruta->nombre }}</strong>
                                            @if($rp->ruta->descripcion)
                                                <span class="text-muted">({{ $rp->ruta->descripcion }})</span>
                                            @endif
                                        @else
                                            <span class="text-danger">Ruta Eliminada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rp->parada)
                                            {{ $rp->parada->nombre }}
                                        @else
                                            <span class="text-danger">Parada Eliminada</span>
                                        @endif
                                    </td>
                                    <td>{{ $rp->orden }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($rp->sentido) }}</span></td>
                                    <td>
                                        <span class="badge {{ $rp->estado === 'activo' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($rp->estado) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('rutas-paradas.show', $rp->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-size: 0.75rem;">
                                                <i class="bi bi-eye me-1"></i>Ver
                                            </a>
                                            <a href="{{ route('rutas-paradas.edit', $rp->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px; font-size: 0.75rem;">
                                                <i class="bi bi-pencil-square me-1"></i>Editar
                                            </a>
                                            @if($rp->estado === 'activo')
                                                <form action="{{ route('rutas-paradas.destroy', $rp->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('¿Eliminar esta asociación?')" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-size: 0.75rem;">
                                                        <i class="bi bi-trash me-1"></i>Eliminar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No se encontraron asociaciones.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $rutasParadas->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
