<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="dueño_id" class="form-label">{{ __('Dueño Id') }}</label>
            <input type="text" name="dueño_id" class="form-control @error('dueño_id') is-invalid @enderror" value="{{ old('dueño_id', $micro?->dueño_id) }}" id="dueño_id" placeholder="Dueño Id">
            {!! $errors->first('dueño_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="placa" class="form-label">{{ __('Placa') }}</label>
            <input type="text" name="placa" class="form-control @error('placa') is-invalid @enderror" value="{{ old('placa', $micro?->placa) }}" id="placa" placeholder="Placa">
            {!! $errors->first('placa', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="modelo" class="form-label">{{ __('Modelo') }}</label>
            <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror" value="{{ old('modelo', $micro?->modelo) }}" id="modelo" placeholder="Modelo">
            {!! $errors->first('modelo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="marca" class="form-label">{{ __('Marca') }}</label>
            <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror" value="{{ old('marca', $micro?->marca) }}" id="marca" placeholder="Marca">
            {!! $errors->first('marca', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="capacidad_pasajeros" class="form-label">{{ __('Capacidad Pasajeros') }}</label>
            <input type="text" name="capacidad_pasajeros" class="form-control @error('capacidad_pasajeros') is-invalid @enderror" value="{{ old('capacidad_pasajeros', $micro?->capacidad_pasajeros) }}" id="capacidad_pasajeros" placeholder="Capacidad Pasajeros">
            {!! $errors->first('capacidad_pasajeros', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>