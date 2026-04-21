<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $interno?->estado) }}" id="estado" placeholder="Estado">
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="micro_id" class="form-label">{{ __('Micro Id') }}</label>
            <input type="text" name="micro_id" class="form-control @error('micro_id') is-invalid @enderror" value="{{ old('micro_id', $interno?->micro_id) }}" id="micro_id" placeholder="Micro Id">
            {!! $errors->first('micro_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="conductor_id" class="form-label">{{ __('Conductor Id') }}</label>
            <input type="text" name="conductor_id" class="form-control @error('conductor_id') is-invalid @enderror" value="{{ old('conductor_id', $interno?->conductor_id) }}" id="conductor_id" placeholder="Conductor Id">
            {!! $errors->first('conductor_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_ingreso" class="form-label">{{ __('Fecha Ingreso') }}</label>
            <input type="text" name="fecha_ingreso" class="form-control @error('fecha_ingreso') is-invalid @enderror" value="{{ old('fecha_ingreso', $interno?->fecha_ingreso) }}" id="fecha_ingreso" placeholder="Fecha Ingreso">
            {!! $errors->first('fecha_ingreso', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>