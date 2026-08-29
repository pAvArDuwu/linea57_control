{{-- Ruta Info Card --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center justify-content-center rounded-circle"
                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #fde8ec 0%, #f8c4ce 100%);">
                <i class="bi bi-signpost-2" style="color: var(--accent);"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold" style="color: var(--primary);">
                    {{ $ruta->exists ? 'Editar Ruta' : 'Crear Nueva Ruta' }}
                </h5>
                <small class="text-muted">Complete la información general y configure las paradas de Ida y Vuelta</small>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-8">
                <label for="nombre" class="form-label fw-semibold">Nombre de la Ruta <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-signpost-2 text-muted"></i></span>
                    <input type="text" name="nombre" id="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $ruta->nombre) }}"
                           placeholder="Ej: Línea 61 - Troncal Central" required>
                </div>
                @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="estado" class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                    <option value="activo" {{ old('estado', $ruta->estado ?? 'activo') === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $ruta->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="2"
                          class="form-control @error('descripcion') is-invalid @enderror"
                          placeholder="Descripción del recorrido de la ruta (opcional)">{{ old('descripcion', $ruta->descripcion) }}</textarea>
                @error('descripcion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

{{-- Paradas de la Ruta Card (Ida y Vuelta) --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-circle"
                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #e8f0fe 0%, #d4e4fc 100%);">
                    <i class="bi bi-geo-alt" style="color: var(--primary);"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--primary);">Parametrización del Recorrido</h5>
                    <small class="text-muted">Configure las paradas ordenadas para el sentido de <strong>IDA</strong> y de <strong>VUELTA</strong></small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <span class="badge rounded-pill px-3 py-2" style="background: #e8f0fe; color: #1565c0;">
                    <i class="bi bi-arrow-right me-1"></i>Ida: <strong id="contadorIda">0</strong>
                </span>
                <span class="badge rounded-pill px-3 py-2" style="background: #f3e5f5; color: #7b1fa2;">
                    <i class="bi bi-arrow-left me-1"></i>Vuelta: <strong id="contadorVuelta">0</strong>
                </span>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            {{-- Columna 1: Catálogo de Paradas Disponibles --}}
            <div class="col-lg-4">
                <div class="border rounded-3 p-0 h-100" style="border-radius: 14px !important; background: #fafbfc;">
                    <div class="p-3 border-bottom" style="background: linear-gradient(135deg, #f0f4f8 0%, #e8ecf0 100%); border-radius: 14px 14px 0 0;">
                        <h6 class="fw-bold mb-2" style="color: var(--primary);">
                            <i class="bi bi-collection me-2"></i>Catálogo de Paradas
                        </h6>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchParada" class="form-control bg-white" placeholder="Buscar parada...">
                        </div>
                    </div>
                    <div id="listaDisponibles" class="p-2" style="max-height: 480px; overflow-y: auto;">
                        @foreach($paradas as $parada)
                            <div class="parada-catalogo d-flex align-items-center justify-content-between p-2 mb-1 rounded-2 bg-white border"
                                 data-id="{{ $parada->id }}" data-nombre="{{ $parada->nombre }}" data-referencia="{{ $parada->referencia }}"
                                 style="transition: all 0.2s ease;">
                                <div class="d-flex align-items-center gap-2 overflow-hidden">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                         style="width: 28px; height: 28px; background: #e8f0fe;">
                                        <i class="bi bi-geo-alt" style="color: var(--primary); font-size: 0.75rem;"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <div class="fw-semibold text-truncate" style="font-size: 0.85rem;" title="{{ $parada->nombre }}">{{ $parada->nombre }}</div>
                                        @if($parada->referencia)
                                            <small class="text-muted text-truncate d-block" style="font-size: 0.75rem;">{{ $parada->referencia }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-group btn-group-sm ms-2 flex-shrink-0">
                                    <button type="button" class="btn btn-outline-primary btn-add-ida" title="Agregar a IDA" style="border-radius: 6px 0 0 6px; font-size: 0.75rem; padding: 0.2rem 0.45rem;">
                                        + Ida
                                    </button>
                                    <button type="button" class="btn btn-outline-purple btn-add-vuelta" title="Agregar a VUELTA" style="border-radius: 0 6px 6px 0; font-size: 0.75rem; padding: 0.2rem 0.45rem;">
                                        + Vta
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        <div id="noParadasMsg" class="text-center text-muted py-4" style="display: none;">
                            <i class="bi bi-emoji-frown" style="font-size: 1.5rem;"></i>
                            <p class="mb-0 mt-2 small">No se encontraron paradas</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna 2: Paradas de IDA --}}
            <div class="col-lg-4">
                <div class="border rounded-3 p-0 h-100" style="border-radius: 14px !important; border-color: #90caf9 !important; border-width: 2px !important;">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center"
                         style="background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); border-radius: 12px 12px 0 0;">
                        <h6 class="fw-bold mb-0 text-white">
                            <i class="bi bi-arrow-right-circle me-1"></i> Paradas IDA
                        </h6>
                        <span id="badgeIdaCount" class="badge bg-white text-primary fw-bold">0</span>
                    </div>
                    <div id="listaIda" class="p-2" style="min-height: 250px; max-height: 480px; overflow-y: auto; background: #f8fbff;">
                        <div id="emptyIdaMsg" class="text-center text-muted py-5">
                            <i class="bi bi-arrow-right-square" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="mb-0 mt-2 small">Agregue paradas de Ida desde el catálogo</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna 3: Paradas de VUELTA --}}
            <div class="col-lg-4">
                <div class="border rounded-3 p-0 h-100" style="border-radius: 14px !important; border-color: #ce93d8 !important; border-width: 2px !important;">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center"
                         style="background: linear-gradient(135deg, #7b1fa2 0%, #4a148c 100%); border-radius: 12px 12px 0 0;">
                        <h6 class="fw-bold mb-0 text-white">
                            <i class="bi bi-arrow-left-circle me-1"></i> Paradas VUELTA
                        </h6>
                        <span id="badgeVueltaCount" class="badge bg-white text-purple fw-bold" style="color: #7b1fa2;">0</span>
                    </div>
                    <div id="listaVuelta" class="p-2" style="min-height: 250px; max-height: 480px; overflow-y: auto; background: #fdf8ff;">
                        <div id="emptyVueltaMsg" class="text-center text-muted py-5">
                            <i class="bi bi-arrow-left-square" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="mb-0 mt-2 small">Agregue paradas de Vuelta desde el catálogo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden inputs containers --}}
<div id="hiddenIdaInputs"></div>
<div id="hiddenVueltaInputs"></div>

{{-- Buttons --}}
<div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('ruta.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
        <i class="bi bi-arrow-left me-2"></i>Cancelar
    </a>
    <button type="submit" class="btn px-4 py-2" style="border-radius: 10px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
        <i class="bi bi-check-lg me-2"></i>Guardar Ruta Completa
    </button>
</div>

<style>
    .btn-outline-purple {
        color: #7b1fa2;
        border-color: #ba68c8;
    }
    .btn-outline-purple:hover {
        background-color: #7b1fa2;
        color: white;
    }
    .parada-catalogo:hover {
        background: #f0f4f8 !important;
        transform: translateX(2px);
    }
    .parada-item {
        background: white;
        border: 1px solid #e0e5ec;
        transition: all 0.2s ease;
    }
    .parada-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .btn-orden {
        width: 26px;
        height: 26px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 0.7rem;
        transition: all 0.15s ease;
    }
    .btn-orden:hover {
        transform: scale(1.1);
    }
    #listaDisponibles::-webkit-scrollbar,
    #listaIda::-webkit-scrollbar,
    #listaVuelta::-webkit-scrollbar {
        width: 5px;
    }
    #listaDisponibles::-webkit-scrollbar-thumb,
    #listaIda::-webkit-scrollbar-thumb,
    #listaVuelta::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.15);
        border-radius: 10px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var listaDisponibles = document.getElementById('listaDisponibles');
    var listaIda = document.getElementById('listaIda');
    var listaVuelta = document.getElementById('listaVuelta');
    var hiddenIdaInputs = document.getElementById('hiddenIdaInputs');
    var hiddenVueltaInputs = document.getElementById('hiddenVueltaInputs');
    var emptyIdaMsg = document.getElementById('emptyIdaMsg');
    var emptyVueltaMsg = document.getElementById('emptyVueltaMsg');
    var contadorIda = document.getElementById('contadorIda');
    var contadorVuelta = document.getElementById('contadorVuelta');
    var badgeIdaCount = document.getElementById('badgeIdaCount');
    var badgeVueltaCount = document.getElementById('badgeVueltaCount');
    var searchInput = document.getElementById('searchParada');
    var noParadasMsg = document.getElementById('noParadasMsg');

    // Cargar paradas existentes (cuando se edita)
    var paradasIdaExistentes = @json($paradasIda ?? collect());
    var paradasVueltaExistentes = @json($paradasVuelta ?? collect());

    if (paradasIdaExistentes.length > 0) {
        paradasIdaExistentes.forEach(function(parada) {
            addParada('ida', parada.id, parada.nombre, parada.referencia || '');
        });
    }

    if (paradasVueltaExistentes.length > 0) {
        paradasVueltaExistentes.forEach(function(parada) {
            addParada('vuelta', parada.id, parada.nombre, parada.referencia || '');
        });
    }

    // Eventos para agregar desde el catálogo
    listaDisponibles.addEventListener('click', function(e) {
        var btnIda = e.target.closest('.btn-add-ida');
        var btnVuelta = e.target.closest('.btn-add-vuelta');
        if (!btnIda && !btnVuelta) return;

        var item = (btnIda || btnVuelta).closest('.parada-catalogo');
        var id = item.getAttribute('data-id');
        var nombre = item.getAttribute('data-nombre');
        var referencia = item.getAttribute('data-referencia') || '';

        if (btnIda) {
            addParada('ida', id, nombre, referencia);
        } else if (btnVuelta) {
            addParada('vuelta', id, nombre, referencia);
        }
    });

    // Delegación en listas seleccionadas (subir, bajar, quitar)
    [listaIda, listaVuelta].forEach(function(container) {
        container.addEventListener('click', function(e) {
            var btn = e.target.closest('button');
            if (!btn) return;

            var item = btn.closest('.parada-item');
            if (!item) return;

            var sentido = container === listaIda ? 'ida' : 'vuelta';

            if (btn.classList.contains('btn-up')) {
                moveUp(item, sentido);
            } else if (btn.classList.contains('btn-down')) {
                moveDown(item, sentido);
            } else if (btn.classList.contains('btn-remove')) {
                item.remove();
                updateOrder(sentido);
            }
        });
    });

    // Filtro de búsqueda
    searchInput.addEventListener('input', function() {
        var query = this.value.toLowerCase().trim();
        var items = listaDisponibles.querySelectorAll('.parada-catalogo');
        var visibleCount = 0;

        items.forEach(function(item) {
            var nombre = item.getAttribute('data-nombre').toLowerCase();
            var referencia = (item.getAttribute('data-referencia') || '').toLowerCase();

            if (nombre.indexOf(query) !== -1 || referencia.indexOf(query) !== -1) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        noParadasMsg.style.display = visibleCount === 0 ? 'block' : 'none';
    });

    function addParada(sentido, id, nombre, referencia) {
        var container = sentido === 'ida' ? listaIda : listaVuelta;
        var badgeColor = sentido === 'ida' ? 'background: #1565c0;' : 'background: #7b1fa2;';
        var count = container.querySelectorAll('.parada-item').length + 1;

        var div = document.createElement('div');
        div.className = 'parada-item d-flex align-items-center justify-content-between p-2 mb-1 rounded-2';
        div.setAttribute('data-id', id);
        div.innerHTML = '' +
            '<div class="d-flex align-items-center gap-2 overflow-hidden">' +
                '<span class="badge orden-badge text-white fw-bold" style="' + badgeColor + ' min-width: 26px; font-size: 0.75rem;">' + count + '</span>' +
                '<div class="text-truncate">' +
                    '<div class="fw-semibold text-truncate" style="font-size: 0.84rem;">' + escapeHtml(nombre) + '</div>' +
                    (referencia ? '<small class="text-muted text-truncate d-block" style="font-size: 0.72rem;">' + escapeHtml(referencia) + '</small>' : '') +
                '</div>' +
            '</div>' +
            '<div class="d-flex gap-1 flex-shrink-0">' +
                '<button type="button" class="btn btn-outline-secondary btn-orden btn-up" title="Subir">' +
                    '<i class="bi bi-arrow-up"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-secondary btn-orden btn-down" title="Bajar">' +
                    '<i class="bi bi-arrow-down"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-danger btn-orden btn-remove" title="Quitar">' +
                    '<i class="bi bi-x-lg"></i>' +
                '</button>' +
            '</div>';

        container.appendChild(div);
        updateOrder(sentido);
    }

    function moveUp(item, sentido) {
        var prev = item.previousElementSibling;
        if (prev && prev.classList.contains('parada-item')) {
            item.parentNode.insertBefore(item, prev);
            updateOrder(sentido);
        }
    }

    function moveDown(item, sentido) {
        var next = item.nextElementSibling;
        if (next && next.classList.contains('parada-item')) {
            item.parentNode.insertBefore(next, item);
            updateOrder(sentido);
        }
    }

    function updateOrder(sentido) {
        var container = sentido === 'ida' ? listaIda : listaVuelta;
        var hiddenContainer = sentido === 'ida' ? hiddenIdaInputs : hiddenVueltaInputs;
        var inputName = sentido === 'ida' ? 'paradas_ida[]' : 'paradas_vuelta[]';
        var emptyMsg = sentido === 'ida' ? emptyIdaMsg : emptyVueltaMsg;
        var badgeCounter = sentido === 'ida' ? badgeIdaCount : badgeVueltaCount;
        var headerCounter = sentido === 'ida' ? contadorIda : contadorVuelta;

        var items = container.querySelectorAll('.parada-item');
        hiddenContainer.innerHTML = '';

        items.forEach(function(item, index) {
            var badge = item.querySelector('.orden-badge');
            if (badge) badge.textContent = index + 1;

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = inputName;
            input.value = item.getAttribute('data-id');
            hiddenContainer.appendChild(input);
        });

        badgeCounter.textContent = items.length;
        headerCounter.textContent = items.length;
        emptyMsg.style.display = items.length === 0 ? 'block' : 'none';
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }
});
</script>