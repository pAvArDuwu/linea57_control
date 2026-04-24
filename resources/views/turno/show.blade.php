@extends('layouts.app')

@section('template_title')
    {{ $turno->name ?? __('Show') . " " . __('Turno') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Turno</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('turno.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Interno Id:</strong>
                                    {{ $turno->interno_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ruta Id:</strong>
                                    {{ $turno->ruta_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Hora Inicio:</strong>
                                    {{ $turno->hora_inicio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Hora Fin:</strong>
                                    {{ $turno->hora_fin }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Laboral:</strong>
                                    {{ $turno->fecha_laboral }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
