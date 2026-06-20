<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransporteSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Propietarios
        $propietarioId = DB::table('propietarios')->insertGetId([
            'nombre' => 'Juan',
            'apellidos' => 'Perez',
            'ci' => '1234567',
            'telefono' => '77712345',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2. Conductor
        $conductorId = DB::table('conductor')->insertGetId([
            'nombre' => 'Carlos',
            'apellido' => 'Gomez',
            'telefono' => '77798765',
            'correo' => 'carlos@example.com',
            'ci' => '9876543',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 3. Interno
        $internoId = DB::table('interno')->insertGetId([
            'numero_interno' => 'A-01',
            'estado' => 'activo',
            'fecha_ingreso' => $now,
            'observaciones' => 'Vehículo en perfectas condiciones',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 4. Micro
        $microId = DB::table('micro')->insertGetId([
            'propietario_id' => $propietarioId,
            'interno_id' => $internoId,
            'placa' => '1234-ABC',
            'chasis' => 'CH123456789',
            'anio_fabricacion' => 2018,
            'modelo' => 'Coaster',
            'marca' => 'Toyota',
            'capacidad_pasajeros' => 30,
            'estado' => 'activo',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 5. Ruta
        $rutaId = DB::table('ruta')->insertGetId([
            'origen' => 'Plaza Principal',
            'destino' => 'Terminal de Buses',
            'nombre_ruta' => 'Línea 57 - Centro',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 6. Paradas
        $parada1Id = DB::table('paradas')->insertGetId([
            'nombre' => 'Parada Mercado Central',
            'ubicacion' => 'Av. San Martín esq. Bolívar',
            'latitud' => -17.3935,
            'longitud' => -66.1570,
            'estado' => 'activo',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $parada2Id = DB::table('paradas')->insertGetId([
            'nombre' => 'Parada Hospital',
            'ubicacion' => 'Av. Papa Paulo',
            'latitud' => -17.3850,
            'longitud' => -66.1500,
            'estado' => 'activo',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 7. Parada_Ruta
        DB::table('parada_ruta')->insert([
            [
                'ruta_id' => $rutaId,
                'parada_id' => $parada1Id,
                'orden' => 1,
                'sentido' => 'ida',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ruta_id' => $rutaId,
                'parada_id' => $parada2Id,
                'orden' => 2,
                'sentido' => 'ida',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // 8. Fiscalizadores
        $fiscalizadorId = DB::table('fiscalizadors')->insertGetId([
            'nombres' => 'Mario',
            'apellidos' => 'Vargas',
            'ci' => '5554433',
            'telefono' => '77700011',
            'estado' => 'activo',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 9. Turno (Plantilla)
        $turnoId = DB::table('turno')->insertGetId([
            'tipo' => 'mañana',
            'hora_inicio' => '06:00:00',
            'hora_fin' => '14:00:00',
            'fiscalizador_id' => $fiscalizadorId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 10. Asignacion Turnos
        DB::table('asignacion_turnos')->insert([
            'turno_id' => $turnoId,
            'ruta_id' => $rutaId,
            'micro_id' => $microId,
            'conductor_id' => $conductorId,
            'fecha' => $now->toDateString(),
            'hora_salida' => '06:05:00',
            'hora_llegada' => null,
            'estado' => 'en_curso',
            'observaciones' => 'Salida normal sin retrasos',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
