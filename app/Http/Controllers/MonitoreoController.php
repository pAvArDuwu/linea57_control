<?php

namespace App\Http\Controllers;

use App\Models\AsignacionTurno;
use App\Models\Parada;
use App\Models\Ruta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoreoController extends Controller
{
    /**
     * Vista principal de Monitoreo en Vivo y Control de Paradas.
     */
    public function index(Request $request): View
    {
        $asignacionesActivas = AsignacionTurno::whereIn('estado', ['en_curso', 'pendiente', 'retrasado'])
            ->where('fecha', now()->toDateString())
            ->with([
                'turno',
                'conductor.user',
                'micro.interno',
                'ruta.paradas' => fn ($q) => $q->orderByPivot('sentido')->orderByPivot('orden'),
                'controlesRecorrido.rutaParada.parada',
                'seguimientosGps' => fn ($q) => $q->latest('fecha_hora_gps')->take(1),
            ])
            ->get();

        $rutas = Ruta::where('estado', 'activo')->with(['paradas' => fn ($q) => $q->orderByPivot('sentido')->orderByPivot('orden')])->get();

        return view('monitoreo.index', compact('asignacionesActivas', 'rutas'));
    }

    /**
     * Endpoint JSON para polling de posiciones en vivo y control de paradas en el mapa (SDD Sección 16).
     */
    public function posicionesEnVivo(): JsonResponse
    {
        $asignaciones = AsignacionTurno::whereIn('estado', ['en_curso', 'pendiente', 'retrasado'])
            ->where('fecha', now()->toDateString())
            ->with([
                'turno',
                'conductor.user',
                'micro.interno',
                'ruta.paradas' => fn ($q) => $q->orderByPivot('sentido')->orderByPivot('orden'),
                'controlesRecorrido.rutaParada.parada',
                'seguimientosGps' => fn ($q) => $q->latest('fecha_hora_gps')->take(1),
            ])
            ->get();

        $unidades = $asignaciones->map(function ($a) {
            $ultimoGps = $a->seguimientosGps->first();
            $paradasRuta = $a->ruta?->paradas ?? collect();
            $controlesCumplidos = $a->controlesRecorrido->where('estado', 'cumplido');

            return [
                'asignacion_id' => $a->id,
                'placa' => $a->micro->placa ?? 'S/P',
                'interno' => $a->micro->interno->numero_interno ?? 'S/I',
                'conductor' => $a->conductor ? ($a->conductor->nombre . ' ' . $a->conductor->apellido) : 'Sin conductor',
                'ruta'     => $a->ruta->nombre ?? 'Sin ruta',
                'sentido'  => $this->determinarSentidoActivo($a),
                'turno' => ucfirst($a->turno->nombre ?? ''),
                'estado' => $a->estado,
                'hora_salida' => $a->hora_salida,
                'latitud' => $ultimoGps ? (float)$ultimoGps->latitud : ($paradasRuta->first()?->latitud ? (float)$paradasRuta->first()->latitud : -17.7830),
                'longitud' => $ultimoGps ? (float)$ultimoGps->longitud : ($paradasRuta->first()?->longitud ? (float)$paradasRuta->first()->longitud : -63.1820),
                'velocidad' => $ultimoGps ? (float)$ultimoGps->velocidad : 0.0,
                'ultima_actualizacion' => $ultimoGps ? $ultimoGps->fecha_hora_gps->format('H:i:s') : 'Sin reporte',
                'total_paradas' => $paradasRuta->count(),
                'paradas_cumplidas' => $controlesCumplidos->count(),
                'paradas' => $paradasRuta->map(function ($p) use ($controlesCumplidos) {
                    $cumplida = $controlesCumplidos->firstWhere('ruta_parada_id', $p->pivot->id ?? $p->id);
                    return [
                        'id' => $p->id,
                        'nombre' => $p->nombre,
                        'sentido' => $p->pivot->sentido ?? 'Ida',
                        'latitud' => (float)$p->latitud,
                        'longitud' => (float)$p->longitud,
                        'orden' => $p->pivot->orden ?? 1,
                        'cumplida' => $cumplida !== null,
                        'hora_cumplida' => $cumplida ? $cumplida->fecha_hora->format('H:i:s') : null,
                    ];
                }),
            ];
        });

        return response()->json([
            'timestamp' => now()->toIso8601String(),
            'total_activas' => $unidades->count(),
            'unidades' => $unidades,
        ]);
    }

    /**
     * Determina si la asignación está actualmente en trayecto de Ida o de Vuelta.
     */
    protected function determinarSentidoActivo(AsignacionTurno $asignacion): string
    {
        if (!$asignacion->ruta) {
            return 'Ida';
        }

        $paradasIda = $asignacion->ruta->paradas->filter(fn ($p) => ($p->pivot->sentido ?? 'Ida') === 'Ida');
        if ($paradasIda->isEmpty()) {
            return 'Vuelta';
        }

        $cumplidasIds = $asignacion->controlesRecorrido
            ->where('estado', 'cumplido')
            ->pluck('ruta_parada_id')
            ->toArray();

        $todasIdaCumplidas = $paradasIda->every(fn ($p) => in_array($p->pivot->id ?? $p->id, $cumplidasIds));

        return $todasIdaCumplidas ? 'Vuelta' : 'Ida';
    }
}
