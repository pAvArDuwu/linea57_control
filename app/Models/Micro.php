<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Micro extends Model
{
    protected $perPage = 20;

    protected $table = 'micro';

    protected $fillable = [
        'propietario_id',
        'interno_id',
        'placa',
        'chasis',
        'anio_fabricacion',
        'modelo',
        'marca',
        'capacidad_pasajeros',
        'estado',
    ];

    public function propietario()
    {
        return $this->belongsTo(\App\Models\Dueño::class, 'propietario_id', 'id');
    }

    public function interno()
    {
        return $this->belongsTo(\App\Models\Interno::class, 'interno_id', 'id');
    }
}
