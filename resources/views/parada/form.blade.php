{{-- Información de la Parada --}}
<div class="mb-4">
    <h6 class="fw-bold mb-3" style="color: var(--primary);">
        <i class="bi bi-info-circle me-2"></i>Información de la Parada
    </h6>
    <div class="row g-3">
        <div class="col-md-5">
            <label for="nombre" class="form-label fw-semibold">Nombre de la Parada <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-geo-alt text-muted"></i></span>
                <input type="text" name="nombre" id="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre', $parada->nombre) }}"
                       placeholder="Ej: Parada Central" required>
            </div>
            @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-5">
            <label for="referencia" class="form-label fw-semibold">Referencia</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-signpost-2 text-muted"></i></span>
                <input type="text" name="referencia" id="referencia"
                       class="form-control @error('referencia') is-invalid @enderror"
                       value="{{ old('referencia', $parada->referencia) }}"
                       placeholder="Ej: Frente al mercado principal">
            </div>
            @error('referencia')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-2">
            <label for="estado" class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                <option value="activo" {{ old('estado', $parada->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ old('estado', $parada->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
            @error('estado')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<hr class="my-4">

{{-- Ubicación Geográfica --}}
<div class="mb-4">
    <h6 class="fw-bold mb-3" style="color: var(--primary);">
        <i class="bi bi-globe-americas me-2"></i>Ubicación Geográfica
    </h6>
    <p class="text-muted small mb-3">
        <i class="bi bi-info-circle me-1"></i>
        Busque una ubicación o haga clic directamente en el mapa para seleccionar la posición de la parada.
    </p>

    {{-- Search Bar --}}
    <div class="row g-2 mb-3">
        <div class="col">
            <div class="input-group">
                <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="searchLocation" class="form-control"
                       placeholder="Buscar ubicación... (Ej: Plaza 24 de Septiembre, Santa Cruz)"
                       style="border-radius: 0 10px 10px 0;">
            </div>
        </div>
        <div class="col-auto">
            <button type="button" id="btnSearch" class="btn px-4" style="border-radius: 10px; background: var(--primary); color: white;">
                <i class="bi bi-search me-1"></i>Buscar
            </button>
        </div>
    </div>

    {{-- Search Results --}}
    <div id="searchResults" class="list-group mb-3" style="display: none; max-height: 200px; overflow-y: auto; border-radius: 12px;"></div>

    {{-- Map --}}
    <div class="border rounded-3 overflow-hidden mb-3" style="border-radius: 14px !important; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div id="paradaMap" style="height: 400px; z-index: 1;"></div>
    </div>

    {{-- Coordinates --}}
    <div class="row g-3">
        <div class="col-md-6">
            <label for="latitud" class="form-label fw-semibold">
                <i class="bi bi-crosshair me-1 text-muted"></i>Latitud
            </label>
            <input type="text" name="latitud" id="latitud"
                   class="form-control bg-light @error('latitud') is-invalid @enderror"
                   value="{{ old('latitud', $parada->latitud) }}"
                   placeholder="Seleccione en el mapa" readonly>
            @error('latitud')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="longitud" class="form-label fw-semibold">
                <i class="bi bi-crosshair me-1 text-muted"></i>Longitud
            </label>
            <input type="text" name="longitud" id="longitud"
                   class="form-control bg-light @error('longitud') is-invalid @enderror"
                   value="{{ old('longitud', $parada->longitud) }}"
                   placeholder="Seleccione en el mapa" readonly>
            @error('longitud')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<hr class="my-4">

{{-- Buttons --}}
<div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('parada.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
        <i class="bi bi-arrow-left me-2"></i>Cancelar
    </a>
    <button type="submit" class="btn px-4 py-2" style="border-radius: 10px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
        <i class="bi bi-check-lg me-2"></i>Guardar Parada
    </button>
</div>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Default location: Santa Cruz, Bolivia
    var defaultLat = {{ old('latitud', $parada->latitud) ?: -17.7833 }};
    var defaultLng = {{ old('longitud', $parada->longitud) ?: -63.1821 }};
    var hasLocation = {{ ($parada->latitud && $parada->longitud) || old('latitud') ? 'true' : 'false' }};

    var map = L.map('paradaMap').setView([defaultLat, defaultLng], hasLocation ? 16 : 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    var marker = null;

    // Custom icon
    var customIcon = L.divIcon({
        html: '<div style="background: var(--primary, #0B3C78); width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3);"></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30],
        className: ''
    });

    function setMarker(lat, lng) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
        document.getElementById('latitud').value = lat.toFixed(8);
        document.getElementById('longitud').value = lng.toFixed(8);
    }

    // If editing and has location, place marker
    if (hasLocation) {
        setMarker(defaultLat, defaultLng);
    }

    // Click on map
    map.on('click', function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
        map.setView(e.latlng, Math.max(map.getZoom(), 16));
    });

    // Search with Nominatim
    var searchInput = document.getElementById('searchLocation');
    var btnSearch = document.getElementById('btnSearch');
    var resultsContainer = document.getElementById('searchResults');

    function searchLocation() {
        var query = searchInput.value.trim();
        if (!query) return;

        btnSearch.disabled = true;
        btnSearch.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Buscando...';

        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query) + '&limit=5&addressdetails=1')
            .then(function(response) { return response.json(); })
            .then(function(data) {
                resultsContainer.innerHTML = '';
                if (data.length === 0) {
                    resultsContainer.innerHTML = '<div class="list-group-item text-muted text-center py-3"><i class="bi bi-emoji-frown me-2"></i>No se encontraron resultados</div>';
                } else {
                    data.forEach(function(item) {
                        var div = document.createElement('button');
                        div.type = 'button';
                        div.className = 'list-group-item list-group-item-action d-flex align-items-start gap-2 py-2';
                        div.innerHTML = '<i class="bi bi-geo-alt-fill mt-1" style="color: var(--accent); flex-shrink: 0;"></i><div><div class="fw-semibold" style="font-size: 0.9rem;">' + item.display_name.split(',').slice(0, 2).join(', ') + '</div><small class="text-muted">' + item.display_name + '</small></div>';
                        div.addEventListener('click', function() {
                            var lat = parseFloat(item.lat);
                            var lng = parseFloat(item.lon);
                            setMarker(lat, lng);
                            map.setView([lat, lng], 17);
                            resultsContainer.style.display = 'none';
                            searchInput.value = item.display_name.split(',').slice(0, 3).join(', ');
                        });
                        resultsContainer.appendChild(div);
                    });
                }
                resultsContainer.style.display = 'block';
            })
            .catch(function(error) {
                resultsContainer.innerHTML = '<div class="list-group-item text-danger text-center py-3"><i class="bi bi-exclamation-triangle me-2"></i>Error al buscar. Intente nuevamente.</div>';
                resultsContainer.style.display = 'block';
            })
            .finally(function() {
                btnSearch.disabled = false;
                btnSearch.innerHTML = '<i class="bi bi-search me-1"></i>Buscar';
            });
    }

    btnSearch.addEventListener('click', searchLocation);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchLocation();
        }
    });

    // Ensure map renders properly
    setTimeout(function() { map.invalidateSize(); }, 200);
});
</script>
