@extends('layouts.app')

@section('template_title')
    {{ $interno->name ?? __('Show') . " " . __('Interno') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Interno</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('internos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $interno->estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Micro Id:</strong>
                                    {{ $interno->micro_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Conductor Id:</strong>
                                    {{ $interno->conductor_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Ingreso:</strong>
                                    {{ $interno->fecha_ingreso }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
