<?php

namespace App\Models;

use App\Models\Concerns\TieneEstadoLogico;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ruta
 *
 * @property int    $id
 * @property string $nombre
 * @property string $descripcion
 * @property string $estado
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection<Parada> $paradas
 * @property \Illuminate\Database\Eloquent\Collection<Parada> $paradasIda
 * @property \Illuminate\Database\Eloquent\Collection<Parada> $paradasVuelta
 * @property \Illuminate\Database\Eloquent\Collection<Turno>  $turnos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ruta extends Model
{
    use TieneEstadoLogico;

    protected $perPage = 20;

    protected $table = 'ruta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'descripcion', 'estado'];

    // ─── Relaciones ────────────────────────────────────────────────────────

    /**
     * Todos los turnos de esta ruta.
     */
    public function turnos()
    {
        return $this->hasMany(\App\Models\Turno::class, 'ruta_id', 'id');
    }

    /**
     * Todas las paradas de la ruta (Ida + Vuelta), ordenadas por orden dentro de cada sentido.
     * Útil para obtener el total de paradas o para carga eager genérica.
     */
    public function paradas()
    {
        return $this->belongsToMany(\App\Models\Parada::class, 'parada_ruta', 'ruta_id', 'parada_id')
                    ->withPivot(['orden', 'sentido', 'estado'])
                    ->orderByPivot('sentido')
                    ->orderByPivot('orden')
                    ->withTimestamps();
    }

    /**
     * Paradas del sentido Ida, ordenadas por orden ascendente.
     */
    public function paradasIda()
    {
        return $this->belongsToMany(\App\Models\Parada::class, 'parada_ruta', 'ruta_id', 'parada_id')
                    ->withPivot(['orden', 'sentido', 'estado'])
                    ->wherePivot('sentido', 'Ida')
                    ->orderByPivot('orden')
                    ->withTimestamps();
    }

    /**
     * Paradas del sentido Vuelta, ordenadas por orden ascendente.
     */
    public function paradasVuelta()
    {
        return $this->belongsToMany(\App\Models\Parada::class, 'parada_ruta', 'ruta_id', 'parada_id')
                    ->withPivot(['orden', 'sentido', 'estado'])
                    ->wherePivot('sentido', 'Vuelta')
                    ->orderByPivot('orden')
                    ->withTimestamps();
    }
}
