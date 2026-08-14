<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TurnoController extends Controller
{
    /**
     * Lista todos los turnos del catálogo.
     */
    public function index(Request $request): View
    {
        $buscar = $request->input('buscar');

        $turnos = Turno::when($buscar, function ($q) use ($buscar) {
                        $q->where('nombre', 'LIKE', '%' . $buscar . '%')
                          ->orWhere('descripcion', 'LIKE', '%' . $buscar . '%');
                    })
                    ->orderBy('id')
                    ->paginate(10);

        return view('turno.index', compact('turnos', 'buscar'));
    }

    /**
     * Formulario de creación de un nuevo turno del catálogo.
     */
    public function create(): View
    {
        $turno = new Turno();
        return view('turno.create', compact('turno'));
    }

    /**
     * Almacena un nuevo turno del catálogo.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'      => ['required', 'in:mañana,tarde,noche'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin'    => ['required', 'date_format:H:i'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'estado'      => ['required', 'in:activo,inactivo'],
        ]);

        Turno::create($data);

        return redirect()->route('turno.index')
            ->with('success', 'Turno creado correctamente.');
    }

    /**
     * Muestra los detalles de un turno del catálogo.
     */
    public function show(int $id): View
    {
        $turno = Turno::findOrFail($id);
        return view('turno.show', compact('turno'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(int $id): View
    {
        $turno = Turno::findOrFail($id);
        return view('turno.edit', compact('turno'));
    }

    /**
     * Actualiza el turno del catálogo.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $turno = Turno::findOrFail($id);

        $data = $request->validate([
            'nombre'      => ['required', 'in:mañana,tarde,noche'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin'    => ['required', 'date_format:H:i'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'estado'      => ['required', 'in:activo,inactivo'],
        ]);

        $turno->update($data);

        return redirect()->route('turno.index')
            ->with('success', 'Turno actualizado correctamente.');
    }

    /**
     * Desactivación lógica (no eliminación física).
     */
    public function destroy(int $id): RedirectResponse
    {
        $turno = Turno::findOrFail($id);
        $turno->update(['estado' => 'inactivo']);

        return redirect()->route('turno.index')
            ->with('info', 'Turno desactivado correctamente.');
    }
}
