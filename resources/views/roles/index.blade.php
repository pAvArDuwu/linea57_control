<!-- resources/views/roles/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-primary">Gestión de Roles</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                @if(session('info'))
                    <div class="alert alert-success">
                        {{ session('info') }}
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Lista de Roles</h5>
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">Crear Nuevo Rol</a>
                </div>

                <form action="{{ route('roles.index') }}" method="GET" class="d-flex mb-3">
                    <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar rol por nombre..." value="{{ $buscar }}">
                    <button type="submit" class="btn btn-outline-secondary">Buscar</button>
                    @if($buscar)
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary ms-2">Limpiar</a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Permisos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->permissions->pluck('name')->join(', ') }}</td>
                                    <td>
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Eliminar?')" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>