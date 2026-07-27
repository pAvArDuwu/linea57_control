<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ruta;

class parada extends Model
{
    protected $table = 'paradas';
    protected $fillable = [
        'nombre',
        'referencia',
        'latitud',
        'longitud',
        'estado',
    ];

    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'parada_ruta', 'parada_id', 'ruta_id')
                    ->withPivot(['orden', 'estado'])
                    ->withTimestamps();
    }
}
