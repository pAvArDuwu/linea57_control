<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo AsignacionTurno – entidad transaccional diaria.
 *
 * Representa: en una fecha determinada, un conductor conduce un micro/interno
 * en un turno determinado sobre una ruta determinada.
 *
 * @property int         $id
 * @property string      $fecha
 * @property int         $turno_id
 * @property int         $ruta_id
 * @property int         $micro_id
 * @property int         $conductor_id
 * @property string|null $hora_salida
 * @property string|null $hora_llegada
 * @property string      $estado       pendiente | en_curso | completado | retrasado | cancelado
 * @property string|null $observaciones
 */
class AsignacionTurno extends Model
{
    protected $table = 'asignacion_turnos';

    protected $fillable = [
        'fecha',
        'turno_id',
        'ruta_id',
        'micro_id',
        'conductor_id',
        'hora_salida',
        'hora_llegada',
        'estado',
        'observaciones',
    ];

    // ─── Scopes ────────────────────────────────────────────────────────

    public function scopeBuscarPorConductor(Builder $query, string $criterio): Builder
    {
        return $query->where(function ($q) use ($criterio) {
            $q->whereHas('conductor', function ($c) use ($criterio) {
                $c->where('nombre', 'like', "%{$criterio}%")
                  ->orWhere('apellido', 'like', "%{$criterio}%");
            })->orWhere('fecha', 'like', "%{$criterio}%");
        });
    }

    // ─── Accessors ─────────────────────────────────────────────────────

    protected function estadoBadge(): Attribute
    {
        return Attribute::get(function () {
            $badges = [
                'pendiente'  => ['bg' => '#fff9c4', 'color' => '#795548', 'label' => 'Pendiente'],
                'en_curso'   => ['bg' => '#e3f2fd', 'color' => '#1565c0', 'label' => 'En curso'],
                'completado' => ['bg' => '#e6f4ea', 'color' => '#1e7e34', 'label' => 'Completado'],
                'retrasado'  => ['bg' => '#fff3e0', 'color' => '#e65100', 'label' => 'Retrasado'],
                'cancelado'  => ['bg' => '#f0f0f0', 'color' => '#6c757d', 'label' => 'Cancelado'],
            ];

            return $badges[$this->estado] ?? ['bg' => '#f0f0f0', 'color' => '#6c757d', 'label' => ucfirst($this->estado)];
        });
    }

    protected function turnoEmoji(): Attribute
    {
        return Attribute::get(function () {
            $iconos = ['mañana' => '☀️', 'tarde' => '🌤️', 'noche' => '🌙'];
            $nombre = $this->turno?->nombre ?? '';

            return $iconos[$nombre] ?? '';
        });
    }

    public function estaActivo(): bool
    {
        return !in_array($this->estado, ['cancelado', 'completado']);
    }

    // ─── Relaciones ────────────────────────────────────────────────────

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function turno()
    {
        return $this->belongsTo(\App\Models\Turno::class, 'turno_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function ruta()
    {
        return $this->belongsTo(\App\Models\Ruta::class, 'ruta_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function micro()
    {
        return $this->belongsTo(\App\Models\Micro::class, 'micro_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function conductor()
    {
        return $this->belongsTo(\App\Models\Conductor::class, 'conductor_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function seguimientosGps()
    {
        return $this->hasMany(\App\Models\SeguimientoGps::class, 'asignacion_turno_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function controlesRecorrido()
    {
        return $this->hasMany(\App\Models\ControlRecorrido::class, 'asignacion_turno_id');
    }
}
