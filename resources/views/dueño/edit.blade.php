@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('propietario.index') }}" class="text-decoration-none">Propietarios</a></li>
                    <li class="breadcrumb-item active">Editar: {{ $dueño->nombre }} {{ $dueño->apellido }}</li>
                </ol>
            </nav>
            <form method="POST" action="{{ route('propietario.update', $dueño->id) }}">
                @csrf
                @method('PATCH')
                @include('dueño.form')
            </form>
        </div>
    </div>
</div>
@endsection
