@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary);">
                <i class="bi bi-person-lines-fill me-2"></i>Asignación Usuario - Rol
            </h4>
            <p class="text-muted mb-0">Consulta y administra las asignaciones de roles por usuario</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px; font-weight: 500;">
                <i class="bi bi-arrow-left me-2"></i>Volver a Roles
            </a>
            <a href="{{ route('roles.assign.create') }}" class="btn px-4 py-2"
               style="border-radius: 12px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
                <i class="bi bi-plus-lg me-2"></i>Registrar Asignación
            </a>
        </div>
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

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-radius: 16px 16px 0 0;">
            <h5 class="mb-0 fw-semibold">Asignaciones registradas</h5>
            <form method="GET" action="{{ route('roles.assign') }}" class="d-flex" style="width: 280px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0 bg-light form-control-sm" placeholder="Buscar por usuario o correo..." value="{{ $buscar ?? '' }}" style="border-radius: 0 10px 10px 0;">
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
                                        <a href="{{ route('roles.assign.edit', $user->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px; font-size: 0.75rem;">
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
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
