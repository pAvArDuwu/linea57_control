<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="interno_id" class="form-label">{{ __('Interno Id') }}</label>
            <input type="text" name="interno_id" class="form-control @error('interno_id') is-invalid @enderror" value="{{ old('interno_id', $turno?->interno_id) }}" id="interno_id" placeholder="Interno Id">
            {!! $errors->first('interno_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ruta_id" class="form-label">{{ __('Ruta Id') }}</label>
            <input type="text" name="ruta_id" class="form-control @error('ruta_id') is-invalid @enderror" value="{{ old('ruta_id', $turno?->ruta_id) }}" id="ruta_id" placeholder="Ruta Id">
            {!! $errors->first('ruta_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="hora_inicio" class="form-label">{{ __('Hora Inicio') }}</label>
            <input type="text" name="hora_inicio" class="form-control @error('hora_inicio') is-invalid @enderror" value="{{ old('hora_inicio', $turno?->hora_inicio) }}" id="hora_inicio" placeholder="Hora Inicio">
            {!! $errors->first('hora_inicio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="hora_fin" class="form-label">{{ __('Hora Fin') }}</label>
            <input type="text" name="hora_fin" class="form-control @error('hora_fin') is-invalid @enderror" value="{{ old('hora_fin', $turno?->hora_fin) }}" id="hora_fin" placeholder="Hora Fin">
            {!! $errors->first('hora_fin', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_laboral" class="form-label">{{ __('Fecha Laboral') }}</label>
            <input type="text" name="fecha_laboral" class="form-control @error('fecha_laboral') is-invalid @enderror" value="{{ old('fecha_laboral', $turno?->fecha_laboral) }}" id="fecha_laboral" placeholder="Fecha Laboral">
            {!! $errors->first('fecha_laboral', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>