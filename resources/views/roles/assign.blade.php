@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);"><i class="bi bi-person-lines-fill me-2"></i>Asignar Roles a Usuarios</h4>
            <p class="text-muted mb-0">Gestiona qué roles están asignados a cada usuario</p>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px; font-weight: 500;">
            <i class="bi bi-arrow-left me-2"></i>Volver a Roles
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Formulario de Asignación -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0 fw-semibold">{{ isset($edit_user) ? 'Modificar Roles' : 'Nueva Asignación' }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('roles.assign.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold text-muted" style="font-size: 0.9rem;">Usuario <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-select p-3 bg-light border-0" required style="border-radius: 10px;">
                                <option value="">Selecciona un usuario</option>
                                @foreach($allUsers as $u)
                                    <option value="{{ $u->id }}" {{ (isset($edit_user) && $edit_user->id == $u->id) ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted mb-3" style="font-size: 0.9rem;">Roles a asignar</label>
                            <div class="row g-2">
                                @foreach($roles as $role)
                                    @php
                                        $isChecked = isset($edit_user) && $edit_user->hasRole($role->name);
                                    @endphp
                                    <div class="col-12">
                                        <div class="form-check custom-checkbox p-3 bg-light rounded" style="border-radius: 10px !important;">
                                            <input class="form-check-input ms-1 me-2" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}" {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label fw-medium w-100 cursor-pointer" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-grid mt-4 pt-2 border-top">
                            <button type="submit" class="btn py-3 mt-2" style="border-radius: 10px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600;">
                                <i class="bi bi-save me-2"></i>{{ isset($edit_user) ? 'Actualizar Roles' : 'Asignar Roles' }}
                            </button>
                            @if(isset($edit_user))
                                <a href="{{ route('roles.assign', ['buscar' => request('buscar'), 'page' => request('page')]) }}" class="btn btn-light mt-2" style="border-radius: 10px;">Cancelar Edición</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Usuarios -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap" style="border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0 fw-semibold">Usuarios Registrados</h5>
                    <form method="GET" action="{{ route('roles.assign') }}" class="d-flex mt-2 mt-sm-0" style="width: 250px;">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="buscar" class="form-control border-start-0 bg-light form-control-sm" placeholder="Buscar usuario..." value="{{ $buscar ?? '' }}" style="border-radius: 0 10px 10px 0;">
                        </div>
                        @if(!empty($buscar))
                            <a href="{{ route('roles.assign') }}" class="btn btn-sm btn-outline-secondary ms-2" style="border-radius: 8px;"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr style="background: #f8f9fc;">
                                    <th class="ps-4 py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Usuario</th>
                                    <th class="py-3 text-muted fw-semibold" style="font-size: 0.82rem; text-transform: uppercase;">Roles Asignados</th>
                                    <th class="py-3 text-muted fw-semibold text-end pe-4" style="font-size: 0.82rem; text-transform: uppercase;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                                     style="width: 36px; height: 36px; background: #f0f0f0; color: #555; font-weight: 700; font-size: 0.8rem;">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $user->name }}</div>
                                                    <div class="text-muted small">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @forelse($user->roles as $role)
                                                <span class="badge rounded-pill me-1" style="background: #e0f2fe; color: #0284c7; font-weight: 500; font-size: 0.75rem;">
                                                    {{ $role->name }}
                                                </span>
                                            @empty
                                                <span class="badge rounded-pill bg-light text-muted fw-normal" style="border: 1px solid #ddd;">Sin roles</span>
                                            @endforelse
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="{{ route('roles.assign', ['edit_user' => $user->id, 'buscar' => request('buscar'), 'page' => request('page')]) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px; font-size: 0.75rem;">
                                                    <i class="bi bi-pencil-square me-1"></i>Modificar
                                                </a>
                                                @if($user->roles->count() > 0)
                                                    <form action="{{ route('roles.assign.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" onclick="return confirm('¿Quitar todos los roles a este usuario?')" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-size: 0.75rem;">
                                                            <i class="bi bi-trash me-1"></i>Eliminar Roles
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="bi bi-people d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                            No se encontraron usuarios.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                    <div class="card-footer bg-white border-top py-3 px-4" style="border-radius: 0 0 16px 16px;">
                        {{ $users->appends(['buscar' => request('buscar')])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
