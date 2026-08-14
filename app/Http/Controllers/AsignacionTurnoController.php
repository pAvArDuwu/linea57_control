<?php

namespace App\Http\Controllers;

use App\Models\AsignacionTurno;
use App\Models\Conductor;
use App\Models\Interno;
use App\Models\Micro;
use App\Models\Ruta;
use App\Models\Turno;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AsignacionTurnoController extends Controller
{
    /**
     * Lista de asignaciones de turno.
     */
    public function index(Request $request): View
    {
        $buscar = $request->input('buscar');

        $asignaciones = AsignacionTurno::with(['turno', 'conductor', 'micro', 'interno', 'ruta'])
            ->when($buscar, function ($q) use ($buscar) {
                $q->whereHas('conductor', function ($c) use ($buscar) {
                    $c->where('nombre', 'LIKE', "%$buscar%")
                      ->orWhere('apellido', 'LIKE', "%$buscar%");
                })->orWhere('fecha', 'LIKE', "%$buscar%");
            })
            ->orderByDesc('fecha')
            ->paginate(10);

        return view('asignacion_turno.index', compact('asignaciones', 'buscar'));
    }

    /**
     * Formulario para crear una nueva asignación.
     */
    public function create(): View
    {
        $turnos     = Turno::where('estado', 'activo')->orderBy('nombre')->get();
        $rutas      = Ruta::where('estado', 'activo')->orderBy('nombre')->get();
        $micros     = Micro::where('estado', 'activo')->orderBy('placa')->get();
        $internos   = Interno::where('estado', '!=', 'inactivo')->orderBy('numero_interno')->get();
        $conductores = Conductor::where('estado', 'activo')->orderBy('nombre')->get();

        $asignacion = new AsignacionTurno();

        return view('asignacion_turno.create', compact(
            'asignacion', 'turnos', 'rutas', 'micros', 'internos', 'conductores'
        ));
    }

    /**
     * Almacena una nueva asignación de turno.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'fecha'        => ['required', 'date'],
            'turno_id'     => ['required', 'integer', 'exists:turno,id'],
            'ruta_id'      => ['required', 'integer', 'exists:ruta,id'],
            'micro_id'     => ['required', 'integer', 'exists:micro,id'],
            'interno_id'   => ['nullable', 'integer', 'exists:interno,id'],
            'conductor_id' => ['required', 'integer', 'exists:conductor,id'],
            'hora_salida'  => ['nullable', 'date_format:H:i'],
            'hora_llegada' => ['nullable', 'date_format:H:i'],
            'estado'       => ['required', 'in:pendiente,en_curso,completado,retrasado,cancelado'],
            'observaciones'=> ['nullable', 'string', 'max:1000'],
        ]);

        // Validación adicional: el turno seleccionado debe estar activo
        $turno = Turno::findOrFail($data['turno_id']);
        if ($turno->estado !== 'activo') {
            return back()->withErrors(['turno_id' => 'El turno seleccionado está inactivo.'])->withInput();
        }

        AsignacionTurno::create($data);

        return redirect()->route('asignacion-turno.index')
            ->with('success', 'Asignación de turno creada correctamente.');
    }

    /**
     * Detalle de una asignación.
     */
    public function show(int $id): View
    {
        $asignacion = AsignacionTurno::with(['turno', 'conductor', 'micro', 'interno', 'ruta'])
            ->findOrFail($id);
        return view('asignacion_turno.show', compact('asignacion'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(int $id): View
    {
        $asignacion  = AsignacionTurno::findOrFail($id);
        $turnos      = Turno::where('estado', 'activo')->orderBy('nombre')->get();
        $rutas       = Ruta::where('estado', 'activo')->orderBy('nombre')->get();
        $micros      = Micro::where('estado', 'activo')->orderBy('placa')->get();
        $internos    = Interno::where('estado', '!=', 'inactivo')->orderBy('numero_interno')->get();
        $conductores = Conductor::where('estado', 'activo')->orderBy('nombre')->get();

        return view('asignacion_turno.edit', compact(
            'asignacion', 'turnos', 'rutas', 'micros', 'internos', 'conductores'
        ));
    }

    /**
     * Actualiza una asignación de turno.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $asignacion = AsignacionTurno::findOrFail($id);

        $data = $request->validate([
            'fecha'        => ['required', 'date'],
            'turno_id'     => ['required', 'integer', 'exists:turno,id'],
            'ruta_id'      => ['required', 'integer', 'exists:ruta,id'],
            'micro_id'     => ['required', 'integer', 'exists:micro,id'],
            'interno_id'   => ['nullable', 'integer', 'exists:interno,id'],
            'conductor_id' => ['required', 'integer', 'exists:conductor,id'],
            'hora_salida'  => ['nullable', 'date_format:H:i'],
            'hora_llegada' => ['nullable', 'date_format:H:i'],
            'estado'       => ['required', 'in:pendiente,en_curso,completado,retrasado,cancelado'],
            'observaciones'=> ['nullable', 'string', 'max:1000'],
        ]);

        $turno = Turno::findOrFail($data['turno_id']);
        if ($turno->estado !== 'activo') {
            return back()->withErrors(['turno_id' => 'El turno seleccionado está inactivo.'])->withInput();
        }

        $asignacion->update($data);

        return redirect()->route('asignacion-turno.index')
            ->with('success', 'Asignación de turno actualizada correctamente.');
    }

    /**
     * Desactivación lógica (estado = cancelado) de la asignación.
     */
    public function destroy(int $id): RedirectResponse
    {
        $asignacion = AsignacionTurno::findOrFail($id);
        $asignacion->update(['estado' => 'cancelado']);

        return redirect()->route('asignacion-turno.index')
            ->with('info', 'Asignación cancelada correctamente.');
    }
}
