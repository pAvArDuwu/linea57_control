{{-- Formulario compartido para crear/editar Asignación de Turno --}}
<div class="row g-4">

    {{-- Fecha --}}
    <div class="col-md-6">
        <label for="fecha" class="form-label fw-semibold">
            <i class="bi bi-calendar3 me-1 text-primary"></i>Fecha
        </label>
        <input type="date" name="fecha" id="fecha"
               class="form-control @error('fecha') is-invalid @enderror"
               value="{{ old('fecha', $asignacion->fecha ?? date('Y-m-d')) }}"
               style="border-radius: 10px;">
        @error('fecha')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Turno (catálogo) --}}
    <div class="col-md-6">
        <label for="turno_id" class="form-label fw-semibold">
            <i class="bi bi-clock me-1 text-primary"></i>Turno
        </label>
        <select name="turno_id" id="turno_id"
                class="form-select @error('turno_id') is-invalid @enderror"
                style="border-radius: 10px;">
            <option value="">— Seleccionar turno —</option>
            @foreach($turnos as $turno)
                @php
                    $iconos = ['mañana' => '☀️', 'tarde' => '🌤️', 'noche' => '🌙'];
                    $etiqueta = ($iconos[$turno->nombre] ?? '') . ' ' . $turno->nombre_label;
                    $etiqueta .= ' (' . \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') . ' - ' . \Carbon\Carbon::parse($turno->hora_fin)->format('H:i') . ')';
                @endphp
                <option value="{{ $turno->id }}"
                    {{ old('turno_id', $asignacion->turno_id ?? '') == $turno->id ? 'selected' : '' }}>
                    {{ $etiqueta }}
                </option>
            @endforeach
        </select>
        @error('turno_id')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
        <div class="form-text">Solo se muestran turnos activos. No es posible crear turnos aquí.</div>
    </div>

    
<div class="col-md-6">
    <label for="micro_id" class="form-label fw-semibold">
        <i class="bi bi-bus-front me-1" style="color: var(--primary);"></i>Micro
    </label>

    <select name="micro_id" id="micro_id"
            class="form-select @error('micro_id') is-invalid @enderror"
            style="border-radius: 10px;">

        <option value="">— Seleccionar micro —</option>

        @foreach($micros as $micro)
            <option value="{{ $micro->id }}"
                {{ old('micro_id', $asignacion->micro_id ?? '') == $micro->id ? 'selected' : '' }}>

                Interno {{ $micro->interno->numero_interno ?? 'Sin interno' }}
                — {{ $micro->placa }}
                — {{ $micro->marca }} {{ $micro->modelo }}

            </option>
        @endforeach

    </select>

    @error('micro_id')
        <div class="invalid-feedback">
            <strong>{{ $message }}</strong>
        </div>
    @enderror
</div>

    {{-- Conductor --}}
    <div class="col-md-6">
        <label for="conductor_id" class="form-label fw-semibold">
            <i class="bi bi-person-badge me-1" style="color: var(--primary);"></i>Conductor
        </label>
        <select name="conductor_id" id="conductor_id"
                class="form-select @error('conductor_id') is-invalid @enderror"
                style="border-radius: 10px;">
            <option value="">— Seleccionar conductor —</option>
            @foreach($conductores as $conductor)
                <option value="{{ $conductor->id }}"
                    {{ old('conductor_id', $asignacion->conductor_id ?? '') == $conductor->id ? 'selected' : '' }}>
                    {{ $conductor->nombre }} {{ $conductor->apellido }} (CI: {{ $conductor->ci }})
                </option>
            @endforeach
        </select>
        @error('conductor_id')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Ruta --}}
    <div class="col-md-6">
        <label for="ruta_id" class="form-label fw-semibold">
            <i class="bi bi-signpost-2 me-1" style="color: var(--primary);"></i>Ruta
        </label>
        <select name="ruta_id" id="ruta_id"
                class="form-select @error('ruta_id') is-invalid @enderror"
                style="border-radius: 10px;">
            <option value="">— Seleccionar ruta —</option>
            @foreach($rutas as $ruta)
                <option value="{{ $ruta->id }}"
                    {{ old('ruta_id', $asignacion->ruta_id ?? '') == $ruta->id ? 'selected' : '' }}>
                    {{ $ruta->nombre }}
                </option>
            @endforeach
        </select>
        @error('ruta_id')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Hora de salida --}}
    <div class="col-md-3">
        <label for="hora_salida" class="form-label fw-semibold">
            <i class="bi bi-play-circle me-1 text-success"></i>Hora de salida
        </label>
        <input type="time" name="hora_salida" id="hora_salida"
               class="form-control @error('hora_salida') is-invalid @enderror"
               value="{{ old('hora_salida', isset($asignacion->hora_salida) ? \Carbon\Carbon::parse($asignacion->hora_salida)->format('H:i') : '') }}"
               style="border-radius: 10px;">
        @error('hora_salida')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Hora de llegada --}}
    <div class="col-md-3">
        <label for="hora_llegada" class="form-label fw-semibold">
            <i class="bi bi-stop-circle me-1" style="color: var(--accent);"></i>Hora de llegada
        </label>
        <input type="time" name="hora_llegada" id="hora_llegada"
               class="form-control @error('hora_llegada') is-invalid @enderror"
               value="{{ old('hora_llegada', isset($asignacion->hora_llegada) ? \Carbon\Carbon::parse($asignacion->hora_llegada)->format('H:i') : '') }}"
               style="border-radius: 10px;">
        @error('hora_llegada')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Estado --}}
    <div class="col-md-6">
        <label for="estado" class="form-label fw-semibold">
            <i class="bi bi-toggle-on me-1 text-primary"></i>Estado
        </label>
        <select name="estado" id="estado"
                class="form-select @error('estado') is-invalid @enderror"
                style="border-radius: 10px;">
            <option value="pendiente"  {{ old('estado', $asignacion->estado ?? 'pendiente') === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
            <option value="en_curso"   {{ old('estado', $asignacion->estado ?? 'pendiente') === 'en_curso'   ? 'selected' : '' }}>En curso</option>
            <option value="completado" {{ old('estado', $asignacion->estado ?? 'pendiente') === 'completado' ? 'selected' : '' }}>Completado</option>
            <option value="retrasado"  {{ old('estado', $asignacion->estado ?? 'pendiente') === 'retrasado'  ? 'selected' : '' }}>Retrasado</option>
            <option value="cancelado"  {{ old('estado', $asignacion->estado ?? 'pendiente') === 'cancelado'  ? 'selected' : '' }}>Cancelado</option>
        </select>
        @error('estado')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Observaciones --}}
    <div class="col-md-12">
        <label for="observaciones" class="form-label fw-semibold">
            <i class="bi bi-chat-text me-1 text-secondary"></i>Observaciones <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <textarea name="observaciones" id="observaciones" rows="3"
                  class="form-control @error('observaciones') is-invalid @enderror"
                  placeholder="Observaciones adicionales sobre esta asignación..."
                  style="border-radius: 10px;" maxlength="1000">{{ old('observaciones', $asignacion->observaciones ?? '') }}</textarea>
        @error('observaciones')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- Botones --}}
    <div class="col-md-12 mt-2 d-flex justify-content-between">
        <a href="{{ route('asignacion-turno.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-1"></i>Cancelar
        </a>
        <button type="submit" class="btn px-5 fw-semibold"
                style="border-radius: 10px; background: linear-gradient(135deg, var(--accent) 0%, #a02035 100%); color: white; box-shadow: 0 4px 15px rgba(123,30,43,0.2);">
            <i class="bi bi-check-lg me-1"></i>Guardar Asignación
        </button>
    </div>
</div>
