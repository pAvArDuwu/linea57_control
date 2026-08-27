<?php

namespace App\Models;

use App\Models\Concerns\TieneEstadoLogico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conductor extends Model
{
    use HasFactory, TieneEstadoLogico;

    protected $table = 'conductor';

    protected $fillable = [
        'user_id',
        'licencia',
        'nombre',
        'apellido',
        'telefono',
        'correo',
        'ci',
        'estado',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNombreAttribute($value): ?string { return $this->user?->name ?? $value; }
    public function getApellidoAttribute($value): ?string { return $this->user?->apellido ?? $value; }
    public function getTelefonoAttribute($value): ?string { return $this->user?->telefono ?? $value; }
    public function getCorreoAttribute($value): ?string { return $this->user?->email ?? $value; }
    public function getCiAttribute($value): ?string { return $this->user?->ci ?? $value; }
}
