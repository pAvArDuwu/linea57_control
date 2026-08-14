@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="mb-3">
                <a href="{{ route('asignacion-turno.index') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left me-1"></i>Volver a asignaciones
                </a>
            </div>
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header py-3 px-4" style="border-radius: 16px 16px 0 0; background: linear-gradient(135deg, var(--accent) 0%, #a02035 100%);">
                    <h5 class="mb-0 fw-bold text-white">
                        <i class="bi bi-calendar2-plus me-2"></i>Nueva Asignación de Turno
                    </h5>
                    <p class="text-white-50 small mb-0">Asigna conductor, micro e interno a un turno y ruta para una fecha específica</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('asignacion-turno.store') }}">
                        @csrf
                        @include('asignacion_turno.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
