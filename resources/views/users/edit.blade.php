@extends('layouts.app')

@section('header')
    <h2 class="h4 text-primary mb-0">Editar Usuario: {{ $user->name }}</h2>
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0" style="max-width: 600px;">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña <span class="text-muted small">(dejar en blanco para no cambiar)</span></label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control">
                </div>

                {{-- ====================================================
                     ASIGNACIÓN DE ROLES
                     Aquí se conecta el Usuario con los Roles de Spatie.
                     Spatie guarda esta relación en la tabla `model_has_roles`.
                     Al guardar, se llama $user->syncRoles($request->roles)
                     en el controlador, que actualiza esa tabla automáticamente.
                ===================================================== --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Asignar Rol</label>
                    <p class="text-muted small mb-2">Marca los roles que quieres asignarle a este usuario.</p>
                    @foreach($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                id="role_{{ $role->id }}"
                                {{ in_array($role->name, old('roles', $userRoles)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" style="background-color:#E07B15;border-color:#E07B15;">
                        Actualizar Usuario
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
