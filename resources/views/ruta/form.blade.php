<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="origen" class="form-label">{{ __('Origen') }}</label>
            <input type="text" name="origen" class="form-control @error('origen') is-invalid @enderror" value="{{ old('origen', $rutum?->origen) }}" id="origen" placeholder="Origen">
            {!! $errors->first('origen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="destino" class="form-label">{{ __('Destino') }}</label>
            <input type="text" name="destino" class="form-control @error('destino') is-invalid @enderror" value="{{ old('destino', $rutum?->destino) }}" id="destino" placeholder="Destino">
            {!! $errors->first('destino', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre_ruta" class="form-label">{{ __('Nombre Ruta') }}</label>
            <input type="text" name="nombre_ruta" class="form-control @error('nombre_ruta') is-invalid @enderror" value="{{ old('nombre_ruta', $rutum?->nombre_ruta) }}" id="nombre_ruta" placeholder="Nombre Ruta">
            {!! $errors->first('nombre_ruta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>