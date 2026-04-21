@extends('layouts.app')

@section('template_title')
    {{ $micro->name ?? __('Show') . " " . __('Micro') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Micro</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('micros.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Dueño Id:</strong>
                                    {{ $micro->dueño_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Placa:</strong>
                                    {{ $micro->placa }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Modelo:</strong>
                                    {{ $micro->modelo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Marca:</strong>
                                    {{ $micro->marca }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Capacidad Pasajeros:</strong>
                                    {{ $micro->capacidad_pasajeros }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
