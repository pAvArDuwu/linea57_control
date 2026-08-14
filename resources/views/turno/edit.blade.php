@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="mb-3">
                <a href="{{ route('turno.index') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left me-1"></i>Volver al catálogo
                </a>
            </div>
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0 fw-bold" style="color: var(--primary);">
                        <i class="bi bi-pencil-square me-2"></i>Editar Turno
                    </h5>
                    <p class="text-muted small mb-0">Modifica los datos del turno <strong>{{ $turno->nombre_label }}</strong></p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('turno.update', $turno->id) }}">
                        @csrf
                        @method('PATCH')
                        @include('turno.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
