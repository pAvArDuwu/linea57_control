<?php

namespace App\Http\Controllers;

use App\Models\AsignacionTurno;
use App\Models\ControlRecorrido;
use App\Models\Ruta;
use App\Services\ControlRecorridoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ControlRecorridoController extends Controller
{
    public function __construct(
        protected ControlRecorridoService $controlService
    ) {}

    /**
     * Pantalla 1: Listado de asignaciones con control de recorrido.
     */
    public function index(Request $request): View
    {
        $fecha  = $request->input('fecha', now()->toDateString());
        $rutaId = $request->input('ruta_id');
        $estado = $request->input('estado');

        $query = AsignacionTurno::where('fecha', $fecha)
            ->where('estado', '!=', 'cancelado')
            ->when($rutaId, fn ($q) => $q->where('ruta_id', $rutaId))
            ->when($estado, fn ($q) => $q->where('estado', $estado))
            ->with([
                'turno',
                'conductor.user',
                'micro.interno',
                'ruta',
                'controlesRecorrido',
                'seguimientosGps' => fn ($q) => $q->latest('fecha_hora_gps')->take(1),
            ])
            ->orderByDesc('id');

        $asignaciones = $query->paginate(10);

        // Calcular métricas de progreso para cada asignación
        $asignaciones->each(function (AsignacionTurno $a) {
            $totalParadas = $a->ruta
                ? $a->ruta->rutaParadas()->where('estado', 'activo')->count()
                : 0;
            $cumplidas = $a->controlesRecorrido->where('estado', 'cumplido')->count();

            $a->setAttribute('total_paradas', $totalParadas);
            $a->setAttribute('paradas_cumplidas', $cumplidas);
            $a->setAttribute('porcentaje', $totalParadas > 0 ? round(($cumplidas / $totalParadas) * 100) : 0);
            $a->setAttribute('codigo', 'AT-' . str_pad($a->id, 3, '0', STR_PAD_LEFT));
        });

        $rutas = Ruta::where('estado', 'activo')->orderBy('nombre')->get();

        return view('control_recorrido.index', compact('asignaciones', 'rutas', 'fecha', 'rutaId', 'estado'));
    }

    /**
     * Pantalla 2: Detalle del recorrido con mapa y métricas en tiempo real.
     */
    public function show(int $id): View
    {
        $asignacion = AsignacionTurno::with([
            'turno',
            'conductor.user',
            'micro.interno',
            'ruta.rutaParadas' => fn ($q) => $q->where('estado', 'activo')->with('parada')->orderBy('sentido')->orderBy('orden'),
            'controlesRecorrido.rutaParada.parada',
            'seguimientosGps' => fn ($q) => $q->latest('fecha_hora_gps')->take(1),
        ])->findOrFail($id);

        $paradasIda    = $asignacion->ruta?->rutaParadas()->where('sentido', 'Ida')->where('estado', 'activo')->with('parada')->orderBy('orden')->get() ?? collect();
        $paradasVuelta = $asignacion->ruta?->rutaParadas()->where('sentido', 'Vuelta')->where('estado', 'activo')->with('parada')->orderBy('orden')->get() ?? collect();

        $cumplidasIds = $asignacion->controlesRecorrido->where('estado', 'cumplido')->pluck('ruta_parada_id')->toArray();

        $todasIdaCumplidas = $paradasIda->isNotEmpty()
            && $paradasIda->every(fn ($rp) => in_array($rp->id, $cumplidasIds));

        $sentidoActivo  = ($todasIdaCumplidas && $paradasVuelta->isNotEmpty()) ? 'Vuelta' : 'Ida';
        $secuenciaActiva = $sentidoActivo === 'Ida' ? $paradasIda : $paradasVuelta;

        $siguienteParada = $secuenciaActiva->first(fn ($rp) => !in_array($rp->id, $cumplidasIds));

        $totalParadas    = $paradasIda->count() + $paradasVuelta->count();
        $paradasCumplidas = count($cumplidasIds);
        $porcentaje      = $totalParadas > 0 ? round(($paradasCumplidas / $totalParadas) * 100) : 0;

        $ultimoGps = $asignacion->seguimientosGps->first();

        $codigo = 'AT-' . str_pad($asignacion->id, 3, '0', STR_PAD_LEFT);

        $paradasIdaJson = $paradasIda->map(fn ($rp) => [
            'id' => $rp->id,
            'lat' => (float)($rp->parada->latitud ?? 0),
            'lng' => (float)($rp->parada->longitud ?? 0),
            'nombre' => $rp->parada->nombre ?? '?',
            'orden' => $rp->orden,
            'sentido' => 'Ida',
        ])->values()->all();

        $paradasVueltaJson = $paradasVuelta->map(fn ($rp) => [
            'id' => $rp->id,
            'lat' => (float)($rp->parada->latitud ?? 0),
            'lng' => (float)($rp->parada->longitud ?? 0),
            'nombre' => $rp->parada->nombre ?? '?',
            'orden' => $rp->orden,
            'sentido' => 'Vuelta',
        ])->values()->all();

        return view('control_recorrido.show', compact(
            'asignacion',
            'paradasIda',
            'paradasVuelta',
            'paradasIdaJson',
            'paradasVueltaJson',
            'cumplidasIds',
            'sentidoActivo',
            'siguienteParada',
            'totalParadas',
            'paradasCumplidas',
            'porcentaje',
            'ultimoGps',
            'codigo'
        ));
    }

    /**
     * Pantalla 3: Historial exhaustivo de paradas con horas previstas vs reales.
     */
    public function historial(int $id): View
    {
        $asignacion = AsignacionTurno::with([
            'turno',
            'conductor.user',
            'micro.interno',
            'ruta',
            'ruta.rutaParadas' => fn ($q) => $q->where('estado', 'activo')->with('parada')->orderBy('sentido')->orderBy('orden'),
            'controlesRecorrido.rutaParada',
        ])->findOrFail($id);

        $paradasIda    = $asignacion->ruta?->rutaParadas()->where('sentido', 'Ida')->where('estado', 'activo')->with('parada')->orderBy('orden')->get() ?? collect();
        $paradasVuelta = $asignacion->ruta?->rutaParadas()->where('sentido', 'Vuelta')->where('estado', 'activo')->with('parada')->orderBy('orden')->get() ?? collect();

        // Calcular hora prevista basada en hora_salida del turno más intervalos estimados (5 min por parada)
        $horaSalida = $asignacion->hora_salida
            ? \Carbon\Carbon::parse($asignacion->fecha . ' ' . $asignacion->hora_salida)
            : null;

        $todasParadas = $paradasIda->concat($paradasVuelta)->values();

        $filas = $todasParadas->map(function ($rp, $index) use ($asignacion, $horaSalida) {
            $control = $asignacion->controlesRecorrido->firstWhere('ruta_parada_id', $rp->id);
            $horaPrevista = $horaSalida?->copy()->addMinutes($index * 5);

            return [
                'numero'        => $index + 1,
                'parada'        => $rp->parada,
                'ruta_parada'   => $rp,
                'sentido'       => $rp->sentido,
                'hora_prevista' => $horaPrevista?->format('H:i'),
                'hora_paso'     => $control?->fecha_hora?->format('H:i:s'),
                'estado'        => $control?->estado ?? 'pendiente',
                'distancia'     => $control?->distancia_metros,
                'observacion'   => $control?->observacion,
            ];
        });

        $codigo = 'AT-' . str_pad($asignacion->id, 3, '0', STR_PAD_LEFT);

        return view('control_recorrido.historial', compact('asignacion', 'filas', 'paradasIda', 'paradasVuelta', 'codigo'));
    }

    /**
     * Pantalla 4: Vista de la ruta en modo mapa ampliado.
     */
    public function mapaAmpliado(int $id): View
    {
        $asignacion = AsignacionTurno::with([
            'turno',
            'conductor.user',
            'micro.interno',
            'ruta',
            'ruta.rutaParadas' => fn ($q) => $q->where('estado', 'activo')->with('parada')->orderBy('sentido')->orderBy('orden'),
            'controlesRecorrido',
            'seguimientosGps' => fn ($q) => $q->latest('fecha_hora_gps')->take(1),
        ])->findOrFail($id);

        $paradasIda    = $asignacion->ruta?->rutaParadas()->where('sentido', 'Ida')->where('estado', 'activo')->with('parada')->orderBy('orden')->get() ?? collect();
        $paradasVuelta = $asignacion->ruta?->rutaParadas()->where('sentido', 'Vuelta')->where('estado', 'activo')->with('parada')->orderBy('orden')->get() ?? collect();

        $cumplidasIds = $asignacion->controlesRecorrido->where('estado', 'cumplido')->pluck('ruta_parada_id')->toArray();

        $cumplidas = $asignacion->controlesRecorrido->where('estado', 'cumplido')->count();
        $omitidas  = $asignacion->controlesRecorrido->where('estado', 'omitido')->count();
        $total     = $paradasIda->count() + $paradasVuelta->count();
        $pendientes = $total - $cumplidas - $omitidas;
        $porcentaje = $total > 0 ? round(($cumplidas / $total) * 100) : 0;

        $ultimoGps = $asignacion->seguimientosGps->first();
        $codigo    = 'AT-' . str_pad($asignacion->id, 3, '0', STR_PAD_LEFT);

        $paradasIdaJson = $paradasIda->map(fn ($rp) => [
            'id' => $rp->id,
            'lat' => (float)($rp->parada->latitud ?? 0),
            'lng' => (float)($rp->parada->longitud ?? 0),
            'nombre' => $rp->parada->nombre ?? '?',
            'orden' => $rp->orden,
            'sentido' => 'Ida',
        ])->values()->all();

        $paradasVueltaJson = $paradasVuelta->map(fn ($rp) => [
            'id' => $rp->id,
            'lat' => (float)($rp->parada->latitud ?? 0),
            'lng' => (float)($rp->parada->longitud ?? 0),
            'nombre' => $rp->parada->nombre ?? '?',
            'orden' => $rp->orden,
            'sentido' => 'Vuelta',
        ])->values()->all();

        return view('control_recorrido.mapa', compact(
            'asignacion',
            'paradasIda',
            'paradasVuelta',
            'paradasIdaJson',
            'paradasVueltaJson',
            'cumplidasIds',
            'cumplidas',
            'omitidas',
            'pendientes',
            'total',
            'porcentaje',
            'ultimoGps',
            'codigo'
        ));
    }
}
