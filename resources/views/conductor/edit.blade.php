@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center"><div class="col-lg-9">
        <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('conductor.index') }}" class="text-decoration-none">Conductores</a></li>
            <li class="breadcrumb-item active">Editar: {{ $conductor->nombre }} {{ $conductor->apellido }}</li>
        </ol></nav>
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: var(--primary);">Editar Conductor</h5>
            </div>
            <form method="POST" action="{{ route('conductor.update', $conductor->id) }}">
                @csrf @method('PUT')
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="user_id" class="form-label fw-semibold">Usuario de acceso</label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                <option value="">Sin cuenta vinculada</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" data-nombre="{{ $usuario->name }}" data-apellido="{{ $usuario->apellido }}" data-ci="{{ $usuario->ci }}" data-telefono="{{ $usuario->telefono }}" data-correo="{{ $usuario->email }}" {{ old('user_id', $conductor->user_id) == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }} — {{ $usuario->email }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Un usuario también puede estar vinculado como propietario.</div>
                            @error('user_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6"><label for="licencia" class="form-label fw-semibold">Licencia de conducir</label><input type="text" name="licencia" id="licencia" class="form-control @error('licencia') is-invalid @enderror" value="{{ old('licencia', $conductor->licencia) }}">@error('licencia')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
                        <div class="col-md-6">
                            <label for="nombre" class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $conductor->nombre) }}" readonly>
                            @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label fw-semibold">Apellido <span class="text-danger">*</span></label>
                            <input type="text" name="apellido" id="apellido" class="form-control" value="{{ old('apellido', $conductor->apellido) }}" readonly>
                            @error('apellido')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="ci" class="form-label fw-semibold">CI <span class="text-danger">*</span></label>
                            <input type="text" name="ci" id="ci" class="form-control" value="{{ old('ci', $conductor->ci) }}" readonly>
                            @error('ci')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="telefono" class="form-label fw-semibold">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $conductor->telefono) }}" readonly>
                            @error('telefono')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="estado" class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                                <option value="activo" {{ old('estado', $conductor->estado ?? 'activo') === 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado', $conductor->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="correo" class="form-label fw-semibold">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo', $conductor->correo) }}" readonly>
                            @error('correo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between" style="border-radius: 0 0 16px 16px;">
                    <a href="{{ route('conductor.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;"><i class="bi bi-arrow-left me-2"></i>Cancelar</a>
                    <button type="submit" class="btn px-4 py-2" style="border-radius: 10px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; font-weight: 600; box-shadow: 0 4px 15px rgba(11,60,120,0.25);">
                        <i class="bi bi-check-lg me-2"></i>Actualizar Conductor
                    </button>
                </div>
            </form>
        </div>
    </div></div>
</div>
<script>document.getElementById('user_id')?.addEventListener('change',function(){let o=this.options[this.selectedIndex];['nombre','apellido','ci','telefono','correo'].forEach(k=>document.getElementById(k).value=o.dataset[k]||'');});</script>
@endsection
