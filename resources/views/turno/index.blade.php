<!-- resources/views/turno/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-primary">Gestión de Turnos</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Lista de Turnos</h5>
                    <a href="{{ route('turno.create') }}" class="btn btn-primary">Crear Nuevo Turno</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Interno ID</th>
                                <th>Ruta ID</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Fecha Laboral</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($turnos as $turno)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $turno->interno_id }}</td>
                                    <td>{{ $turno->ruta_id }}</td>
                                    <td>{{ $turno->hora_inicio }}</td>
                                    <td>{{ $turno->hora_fin }}</td>
                                    <td>{{ $turno->fecha_laboral }}</td>
                                    <td>
                                        <a href="{{ route('turno.show', $turno->id) }}" class="btn btn-sm btn-info me-1">Ver</a>
                                        <a href="{{ route('turno.edit', $turno->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>
                                        <form action="{{ route('turno.destroy', $turno->id) }}" method="POST" class="d-inline">
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

                <div class="mt-3">
                    {{ $turnos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
