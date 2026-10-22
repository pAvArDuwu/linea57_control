@extends('layouts.app')

@section('content')
@vite('resources/js/roles.js')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="role-assignment-card">
                <div class="role-assignment-header">
                    <div class="role-assignment-header-icon">
                        <i class="bi bi-person-gear"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold">
                            {{ isset($edit_user) ? 'Modificar asignación de usuario y roles' : 'Registrar asignación de usuario y roles' }}
                        </h4>
                        <p class="mb-0">Selecciona un usuario y define los roles que tendrá asignados</p>
                    </div>
                </div>

                <div class="role-assignment-body">
                    <form action="{{ route('roles.assign.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="user_id" class="form-label role-assignment-label">
                                Usuario <span class="text-danger">*</span>
                            </label>
                            <select name="user_id" id="user_id"
                                    class="form-select role-assignment-select @error('user_id') is-invalid @enderror"
                                    required>
                                <option value="">Selecciona un usuario</option>
                                @foreach($allUsers as $u)
                                    <option value="{{ $u->id }}" {{ old('user_id', $edit_user->id ?? '') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-end mb-1">
                                <div>
                                    <label class="form-label role-assignment-label mb-1">Roles a asignar</label>
                                    <p class="text-muted small mb-0">Puedes seleccionar uno o varios roles permitidos.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            @foreach($roles as $role)
                                @php
                                    $selectedRoles = old('roles', isset($edit_user) ? $edit_user->roles->pluck('name')->toArray() : []);
                                    $isChecked = in_array($role->name, $selectedRoles, true);
                                    $roleIcon = match (strtolower($role->name)) {
                                        'admin', 'administrador' => 'bi-shield',
                                        'conductor' => 'bi-car-front',
                                        'fiscalizador' => 'bi-shield-check',
                                        'propietario' => 'bi-house',
                                        default => 'bi-person-badge',
                                    };
                                @endphp
                                <div class="col-12 col-md-6">
                                    <label class="role-option {{ $isChecked ? 'selected' : '' }}" for="role_{{ $role->id }}">
                                        <input class="role-option-input" type="checkbox" name="roles[]"
                                               value="{{ $role->name }}" id="role_{{ $role->id }}"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <span class="role-option-check"><i class="bi bi-check"></i></span>
                                        <span class="role-option-icon"><i class="bi {{ $roleIcon }}"></i></span>
                                        <span class="role-option-name">{{ $role->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        @error('roles')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        @error('roles.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <div class="role-selection-summary mt-3">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <span><strong id="selected-role-count">0</strong> <span id="selected-role-label">roles seleccionados</span></span>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('roles.assign') }}" class="btn role-cancel-button px-4 py-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn role-save-button px-4 py-2">
                                <i class="bi bi-save me-2"></i>{{ isset($edit_user) ? 'Actualizar asignación' : 'Guardar asignación' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .role-assignment-card {
        overflow: hidden;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 28px rgba(15, 55, 100, 0.14);
    }

    .role-assignment-header {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.35rem 2.75rem;
        color: #fff;
        background: linear-gradient(135deg, #16457f 0%, #0b3264 100%);
    }

    .role-assignment-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 3.2rem;
        height: 3.2rem;
        border-right: 1px solid rgba(255, 255, 255, 0.35);
        padding-right: 1.2rem;
        box-sizing: content-box;
        font-size: 2.05rem;
    }

    .role-assignment-header h4 {
        font-size: 1.45rem;
    }

    .role-assignment-header p {
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.95rem;
    }

    .role-assignment-body {
        padding: 2rem 2.75rem 1.55rem;
    }

    .role-assignment-label {
        color: #163e72;
        font-size: 1rem;
        font-weight: 700;
    }

    .role-assignment-select {
        min-height: 3.2rem;
        padding: 0.75rem 1rem;
        color: #557092;
        border: 1px solid #d7e2f0;
        border-radius: 9px;
        box-shadow: 0 1px 4px rgba(25, 70, 120, 0.06);
    }

    .role-assignment-select:focus {
        border-color: #2879e8;
        box-shadow: 0 0 0 0.2rem rgba(40, 121, 232, 0.12);
    }

    .role-option {
        display: flex;
        align-items: center;
        min-height: 5.8rem;
        gap: 1rem;
        padding: 1rem 1.25rem;
        cursor: pointer;
        border: 1px solid #dce6f1;
        border-radius: 10px;
        color: #163e72;
        transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
    }

    .role-option:hover,
    .role-option.selected {
        border-color: #2879e8;
        background: #f5f9ff;
        box-shadow: 0 2px 7px rgba(40, 121, 232, 0.08);
    }

    .role-option-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .role-option-check {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.45rem;
        height: 1.45rem;
        flex: 0 0 1.45rem;
        border: 2px solid #a9bdd5;
        border-radius: 4px;
        color: #fff;
        font-size: 0.9rem;
    }

    .role-option.selected .role-option-check {
        border-color: #2879e8;
        background: #2879e8;
    }

    .role-option-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.8rem;
        height: 2.8rem;
        flex: 0 0 2.8rem;
        border-radius: 50%;
        background: #edf5ff;
        color: #1b5ea8;
        font-size: 1.55rem;
    }

    .role-option-name {
        font-size: 1rem;
        font-weight: 700;
    }

    .role-selection-summary {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.85rem;
        border-radius: 999px;
        background: #eaf3ff;
        color: #1e6dcc;
        font-size: 0.88rem;
    }

    .role-assignment-body hr {
        border-color: #dce6f1;
        opacity: 1;
    }

    .role-cancel-button {
        border: 1px solid #d7e2f0;
        border-radius: 9px;
        color: #536b8b;
        font-weight: 600;
    }

    .role-save-button {
        border-radius: 9px;
        background: #1769dc;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(23, 105, 220, 0.24);
    }

    .role-save-button:hover,
    .role-save-button:focus {
        background: #1058bd;
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .role-assignment-header,
        .role-assignment-body {
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        .role-assignment-header h4 {
            font-size: 1.15rem;
        }

        .role-assignment-header p {
            font-size: 0.8rem;
        }

        .role-assignment-header-icon {
            display: none;
        }
    }
</style>

@endsection
