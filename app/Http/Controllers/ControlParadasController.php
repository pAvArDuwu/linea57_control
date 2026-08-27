<?php

namespace App\Http\Controllers;

use App\Models\AsignacionTurno;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ControlParadasController extends Controller
{
    /**
     * Vista de Control y Auditoría de Paradas (Transaccional).
     */
    public function index(Request $request): View
    {
        $fecha = $request->input('fecha', now()->toDateString());
        $rutaId = $request->input('ruta_id');

        $asignaciones = AsignacionTurno::where('fecha', $fecha)
            ->where('estado', '!=', 'cancelado')
            ->when($rutaId, fn ($q) => $q->where('ruta_id', $rutaId))
            ->with([
                'turno',
                'conductor.user',
                'micro.interno',
                'ruta.paradas' => fn ($q) => $q->orderBy('orden'),
                'controlesRecorrido.rutaParada.parada',
                'seguimientosGps' => fn ($q) => $q->latest('fecha_hora_gps')->take(1)
            ])
            ->orderByDesc('id')
            ->paginate(10);

        $rutas = Ruta::where('estado', 'activo')->orderBy('nombre')->get();

        return view('control_paradas.index', compact('asignaciones', 'rutas', 'fecha', 'rutaId'));
    }
}
