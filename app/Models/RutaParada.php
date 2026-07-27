<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutaParada extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parada_ruta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ruta_id',
        'parada_id',
        'orden',
        'estado',
    ];

    /**
     * Get the route associated with this connection.
     */
    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    /**
     * Get the stop associated with this connection.
     */
    public function parada()
    {
        return $this->belongsTo(parada::class, 'parada_id');
    }
}
