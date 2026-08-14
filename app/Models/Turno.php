<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Turno – catálogo estático de horarios de operación.
 *
 * Un turno representa un bloque horario (Mañana / Tarde / Noche).
 * NO se crea un registro por cada día; es un parámetro reutilizable.
 *
 * @property int    $id
 * @property string $nombre      Enum: mañana | tarde | noche
 * @property string $hora_inicio Hora habitual de inicio (HH:MM:SS)
 * @property string $hora_fin    Hora habitual de finalización (HH:MM:SS)
 * @property string|null $descripcion
 * @property string $estado      activo | inactivo
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Turno extends Model
{
    protected $table = 'turno';

    protected $perPage = 20;

    protected $fillable = [
        'nombre',
        'hora_inicio',
        'hora_fin',
        'descripcion',
        'estado',
    ];

    /**
     * Etiquetas legibles para el enum `nombre`.
     */
    public const NOMBRES = [
        'mañana' => 'Mañana',
        'tarde'  => 'Tarde',
        'noche'  => 'Noche',
    ];

    /**
     * Devuelve la etiqueta capitalizada del turno.
     */
    public function getNombreLabelAttribute(): string
    {
        return self::NOMBRES[$this->nombre] ?? ucfirst($this->nombre);
    }

    /**
     * Un turno puede estar asociado a muchas asignaciones de turno.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function asignaciones()
    {
        return $this->hasMany(\App\Models\AsignacionTurno::class, 'turno_id');
    }
}
