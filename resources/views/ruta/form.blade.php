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
                <small class="text-muted">Complete la información de la ruta</small>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label fw-semibold">Nombre de la Ruta <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-signpost-2 text-muted"></i></span>
                    <input type="text" name="nombre" id="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $ruta->nombre) }}"
                           placeholder="Ej: Ruta Principal Norte" required>
                </div>
                @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="sentido" class="form-label fw-semibold">Sentido <span class="text-danger">*</span></label>
                <select name="sentido" id="sentido" class="form-select @error('sentido') is-invalid @enderror">
                    <option value="Ida" {{ old('sentido', $ruta->sentido) === 'Ida' ? 'selected' : '' }}>
                        ➡️ Ida
                    </option>
                    <option value="Vuelta" {{ old('sentido', $ruta->sentido) === 'Vuelta' ? 'selected' : '' }}>
                        ⬅️ Vuelta
                    </option>
                </select>
                @error('sentido')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="estado" class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                    <option value="activo" {{ old('estado', $ruta->estado ?? 'activo') === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $ruta->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3"
                          class="form-control @error('descripcion') is-invalid @enderror"
                          placeholder="Descripción de la ruta (opcional)">{{ old('descripcion', $ruta->descripcion) }}</textarea>
                @error('descripcion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

{{-- Paradas de la Ruta Card --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center justify-content-center rounded-circle"
                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #e8f0fe 0%, #d4e4fc 100%);">
                <i class="bi bi-geo-alt" style="color: var(--primary);"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold" style="color: var(--primary);">Paradas de la Ruta</h5>
                <small class="text-muted">Seleccione y ordene las paradas que pertenecen a esta ruta</small>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            {{-- Left Column: Available Stops --}}
            <div class="col-md-6">
                <div class="border rounded-3 p-0 h-100" style="border-radius: 14px !important; background: #fafbfc;">
                    <div class="p-3 border-bottom" style="background: linear-gradient(135deg, #f0f4f8 0%, #e8ecf0 100%); border-radius: 14px 14px 0 0;">
                        <h6 class="fw-bold mb-2" style="color: var(--primary);">
                            <i class="bi bi-collection me-2"></i>Paradas Disponibles
                        </h6>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchParada" class="form-control bg-white" placeholder="Buscar parada...">
                        </div>
                    </div>
                    <div id="listaDisponibles" class="p-2" style="max-height: 400px; overflow-y: auto;">
                        @foreach($paradas as $parada)
                            <div class="parada-disponible d-flex align-items-center justify-content-between p-2 mb-1 rounded-2 bg-white"
                                 data-id="{{ $parada->id }}" data-nombre="{{ $parada->nombre }}" data-referencia="{{ $parada->referencia }}"
                                 style="transition: all 0.2s ease; cursor: default;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                                         style="width: 30px; height: 30px; background: #e8f0fe; flex-shrink: 0;">
                                        <i class="bi bi-geo-alt" style="color: var(--primary); font-size: 0.75rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="font-size: 0.88rem;">{{ $parada->nombre }}</div>
                                        @if($parada->referencia)
                                            <small class="text-muted">{{ Str::limit($parada->referencia, 35) }}</small>
                                        @endif
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-add-parada"
                                        style="background: var(--primary); color: white; border-radius: 8px; padding: 0.2rem 0.5rem;"
                                        title="Agregar a la ruta">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        @endforeach
                        <div id="noParadasMsg" class="text-center text-muted py-4" style="display: none;">
                            <i class="bi bi-emoji-frown" style="font-size: 1.5rem;"></i>
                            <p class="mb-0 mt-2 small">No se encontraron paradas</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Selected Stops --}}
            <div class="col-md-6">
                <div class="border rounded-3 p-0 h-100" style="border-radius: 14px !important; border-color: var(--primary) !important; border-width: 2px !important;">
                    <div class="p-3 border-bottom" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: 12px 12px 0 0;">
                        <h6 class="fw-bold mb-0 text-white">
                            <i class="bi bi-list-ol me-2"></i>Paradas Seleccionadas
                            <span id="contadorSeleccionadas" class="badge bg-white ms-2" style="color: var(--primary);">0</span>
                        </h6>
                    </div>
                    <div id="listaSeleccionadas" class="p-2" style="min-height: 200px; max-height: 400px; overflow-y: auto;">
                        <div id="emptySeleccionadas" class="text-center text-muted py-5">
                            <i class="bi bi-arrow-left-circle" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="mb-0 mt-2 small">Agregue paradas desde la lista izquierda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden inputs container --}}
<div id="hiddenParadasInputs"></div>

{{-- Buttons --}}
<div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('ruta.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
        <i class="bi bi-arrow-left me-2"></i>Cancelar
    </a>
    <button type="submit" class="btn px-4 py-2" style="border-radius: 10px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
        <i class="bi bi-check-lg me-2"></i>Guardar Ruta
    </button>
</div>

<style>
    .parada-disponible:hover {
        background: #e8f0fe !important;
        transform: translateX(2px);
    }
    .parada-seleccionada {
        background: white;
        border: 1px solid #e0e5ec;
        transition: all 0.2s ease;
    }
    .parada-seleccionada:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(11,60,120,0.1);
    }
    .btn-orden {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 0.75rem;
        transition: all 0.15s ease;
    }
    .btn-orden:hover {
        transform: scale(1.1);
    }
    #listaDisponibles::-webkit-scrollbar,
    #listaSeleccionadas::-webkit-scrollbar {
        width: 5px;
    }
    #listaDisponibles::-webkit-scrollbar-thumb,
    #listaSeleccionadas::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.15);
        border-radius: 10px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var listaDisponibles = document.getElementById('listaDisponibles');
    var listaSeleccionadas = document.getElementById('listaSeleccionadas');
    var hiddenInputs = document.getElementById('hiddenParadasInputs');
    var emptyMsg = document.getElementById('emptySeleccionadas');
    var contador = document.getElementById('contadorSeleccionadas');
    var searchInput = document.getElementById('searchParada');
    var noParadasMsg = document.getElementById('noParadasMsg');

    // Load existing selected stops (when editing)
    var paradasExistentes = @json($paradasSeleccionadas ?? collect());

    // Initialize with existing stops
    if (paradasExistentes.length > 0) {
        paradasExistentes.forEach(function(parada) {
            addParadaToSelected(parada.id, parada.nombre, parada.referencia || '');
            // Hide from available list
            var available = listaDisponibles.querySelector('.parada-disponible[data-id="' + parada.id + '"]');
            if (available) {
                available.style.display = 'none';
            }
        });
    }

    // Add button click handler (delegated)
    listaDisponibles.addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-add-parada');
        if (!btn) return;

        var item = btn.closest('.parada-disponible');
        var id = item.getAttribute('data-id');
        var nombre = item.getAttribute('data-nombre');
        var referencia = item.getAttribute('data-referencia') || '';

        addParadaToSelected(id, nombre, referencia);
        item.style.display = 'none';
    });

    // Delegated event handling for selected list
    listaSeleccionadas.addEventListener('click', function(e) {
        var btn = e.target.closest('button');
        if (!btn) return;

        var item = btn.closest('.parada-seleccionada');
        if (!item) return;

        var id = item.getAttribute('data-id');

        if (btn.classList.contains('btn-up')) {
            moveUp(item);
        } else if (btn.classList.contains('btn-down')) {
            moveDown(item);
        } else if (btn.classList.contains('btn-remove')) {
            // Show back in available list
            var available = listaDisponibles.querySelector('.parada-disponible[data-id="' + id + '"]');
            if (available) {
                available.style.display = 'flex';
            }
            item.remove();
            updateOrder();
        }
    });

    // Search filter
    searchInput.addEventListener('input', function() {
        var query = this.value.toLowerCase().trim();
        var items = listaDisponibles.querySelectorAll('.parada-disponible');
        var visibleCount = 0;

        items.forEach(function(item) {
            var nombre = item.getAttribute('data-nombre').toLowerCase();
            var referencia = (item.getAttribute('data-referencia') || '').toLowerCase();
            var isHidden = item.style.display === 'none' && item.getAttribute('data-hidden-selected') === 'true';
            
            if (isHidden) return; // Already selected

            if (nombre.indexOf(query) !== -1 || referencia.indexOf(query) !== -1) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        noParadasMsg.style.display = visibleCount === 0 ? 'block' : 'none';
    });

    function addParadaToSelected(id, nombre, referencia) {
        // Mark as hidden due to selection
        var available = listaDisponibles.querySelector('.parada-disponible[data-id="' + id + '"]');
        if (available) {
            available.setAttribute('data-hidden-selected', 'true');
        }

        emptyMsg.style.display = 'none';

        var count = listaSeleccionadas.querySelectorAll('.parada-seleccionada').length + 1;

        var div = document.createElement('div');
        div.className = 'parada-seleccionada d-flex align-items-center justify-content-between p-2 mb-1 rounded-2';
        div.setAttribute('data-id', id);
        div.innerHTML = '' +
            '<div class="d-flex align-items-center gap-2">' +
                '<span class="badge orden-badge fw-bold" style="background: var(--accent); min-width: 28px;">' + count + '</span>' +
                '<div>' +
                    '<div class="fw-semibold" style="font-size: 0.88rem;">' + escapeHtml(nombre) + '</div>' +
                    (referencia ? '<small class="text-muted">' + escapeHtml(referencia.substring(0, 35)) + '</small>' : '') +
                '</div>' +
            '</div>' +
            '<div class="d-flex gap-1">' +
                '<button type="button" class="btn btn-outline-primary btn-orden btn-up" title="Subir">' +
                    '<i class="bi bi-arrow-up"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-primary btn-orden btn-down" title="Bajar">' +
                    '<i class="bi bi-arrow-down"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-danger btn-orden btn-remove" title="Quitar">' +
                    '<i class="bi bi-x-lg"></i>' +
                '</button>' +
            '</div>';

        listaSeleccionadas.appendChild(div);
        updateOrder();
    }

    function moveUp(item) {
        var prev = item.previousElementSibling;
        if (prev && prev.classList.contains('parada-seleccionada')) {
            item.parentNode.insertBefore(item, prev);
            updateOrder();
        }
    }

    function moveDown(item) {
        var next = item.nextElementSibling;
        if (next && next.classList.contains('parada-seleccionada')) {
            item.parentNode.insertBefore(next, item);
            updateOrder();
        }
    }

    function updateOrder() {
        var items = listaSeleccionadas.querySelectorAll('.parada-seleccionada');
        hiddenInputs.innerHTML = '';

        items.forEach(function(item, index) {
            // Update visual order badge
            var badge = item.querySelector('.orden-badge');
            if (badge) badge.textContent = index + 1;

            // Create hidden input
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'paradas[]';
            input.value = item.getAttribute('data-id');
            hiddenInputs.appendChild(input);
        });

        contador.textContent = items.length;
        emptyMsg.style.display = items.length === 0 ? 'block' : 'none';
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }
});
</script>