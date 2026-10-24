@php
    $coloresEstado = [
        'Activo' => ['#e6f4ea', '#1e7e34'],
        'Completado' => ['#e6f4ea', '#1e7e34'],
        'En curso' => ['#e3f2fd', '#1565c0'],
        'Pendiente' => ['#fff9c4', '#795548'],
        'Retrasado' => ['#fff3e0', '#e65100'],
        'Cancelado' => ['#f0f0f0', '#6c757d'],
        'Inactivo' => ['#f0f0f0', '#6c757d'],
    ];
@endphp
<div class="card border-0 shadow-sm reportes-table-card">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-list-check text-primary fs-5"></i>
            <span class="fw-bold text-dark">{{ $titulo }} ({{ $filas->total() }} registros)</span>
        </div>
        @if(!empty($filtrosAplicados))
            <div class="d-flex flex-wrap gap-1">
                @foreach($filtrosAplicados as $etiqueta => $valor)
                    <span class="badge bg-light text-dark border reportes-filter-badge">{{ $etiqueta }}: {{ $valor }}</span>
                @endforeach
            </div>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 reportes-table">
                <thead>
                    <tr>
                        @foreach($columnas as $etiqueta)
                            <th class="py-3 text-muted fw-semibold reportes-table-heading">{{ $etiqueta }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($filas as $fila)
                        <tr>
                            @foreach($claves as $clave)
                                <td class="{{ $loop->first ? 'reportes-cell-first' : 'text-muted' }}">
                                    @if($clave === 'estado')
                                        @php $color = $coloresEstado[$fila[$clave]] ?? ['#f0f0f0', '#6c757d']; @endphp
                                        <span class="badge rounded-pill px-3 py-2 reportes-badge"
                                              style="background: {{ $color[0] }}; color: {{ $color[1] }};">
                                            {{ $fila[$clave] }}
                                        </span>
                                    @elseif($clave === 'origen')
                                        <span class="badge rounded-pill px-3 py-2 reportes-badge"
                                              style="background: {{ $fila[$clave] === 'Registrado' ? '#fdecea' : '#eef2f7' }}; color: {{ $fila[$clave] === 'Registrado' ? '#b3261e' : '#475569' }};">
                                            {{ $fila[$clave] }}
                                        </span>
                                    @else
                                        {{ $fila[$clave] }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columnas) }}" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-30"></i>
                                No se encontraron registros para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($filas->hasPages())
        <div class="card-footer bg-white border-top py-3 px-4 reportes-pagination">
            {{ $filas->appends($filtros)->links() }}
        </div>
    @endif
</div>
