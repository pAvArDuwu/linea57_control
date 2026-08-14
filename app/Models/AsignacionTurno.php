<?php

namespace App\Models;

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
 * @property int|null    $interno_id
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
        'interno_id',
        'conductor_id',
        'hora_salida',
        'hora_llegada',
        'estado',
        'observaciones',
    ];

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
    public function interno()
    {
        return $this->belongsTo(\App\Models\Interno::class, 'interno_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function conductor()
    {
        return $this->belongsTo(\App\Models\Conductor::class, 'conductor_id');
    }
}
