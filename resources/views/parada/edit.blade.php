@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('parada.index') }}" class="text-decoration-none">Paradas</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);">
                            <i class="bi bi-pencil" style="color: #e65100;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color: var(--primary);">Editar Parada</h5>
                            <small class="text-muted">{{ $parada->nombre }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('parada.update', $parada->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('parada.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
