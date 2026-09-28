<?php

namespace Tests\Feature;

use App\Models\AsignacionTurno;
use App\Models\Conductor;
use App\Models\ControlRecorrido;
use App\Models\Micro;
use App\Models\Parada;
use App\Models\Propietario;
use App\Models\Ruta;
use App\Models\RutaParada;
use App\Models\SeguimientoGps;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ControlRecorridoTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected AsignacionTurno $asignacion;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $this->admin = User::factory()->create([
            'ci' => '1234567',
        ]);
        $this->admin->assignRole($role);

        $turno = Turno::create([
            'nombre' => 'mañana',
            'hora_inicio' => '06:00:00',
            'hora_fin' => '14:00:00',
            'dias_semana' => ['lunes', 'martes', 'miercoles'],
            'estado' => 'activo',
        ]);

        $ruta = Ruta::create([
            'nombre' => 'Línea 61 Troncal',
            'estado' => 'activo',
        ]);

        $parada1 = Parada::create([
            'nombre' => 'Parada 1',
            'latitud' => -17.7830,
            'longitud' => -63.1820,
            'estado' => 'activo',
        ]);

        RutaParada::create([
            'ruta_id' => $ruta->id,
            'parada_id' => $parada1->id,
            'orden' => 1,
            'sentido' => 'Ida',
            'estado' => 'activo',
        ]);

        $propietario = Propietario::create([
            'nombre' => 'Carlos',
            'apellido' => 'Suarez',
            'telefono' => '71111111',
            'estado' => 'activo',
        ]);

        $micro = Micro::create([
            'placa' => '2020-ABC',
            'modelo' => 'Coaster',
            'marca' => 'Toyota',
            'chasis' => 'CHASIS-12345',
            'anio_fabricacion' => 2018,
            'capacidad_pasajeros' => 30,
            'estado' => 'activo',
            'propietario_id' => $propietario->id,
        ]);

        $conductor = Conductor::create([
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'telefono' => '70000000',
            'licencia' => 'CAT-C-1234',
            'categoria_licencia' => 'C',
            'estado' => 'activo',
        ]);

        $this->asignacion = AsignacionTurno::create([
            'fecha' => now()->toDateString(),
            'turno_id' => $turno->id,
            'ruta_id' => $ruta->id,
            'micro_id' => $micro->id,
            'conductor_id' => $conductor->id,
            'hora_salida' => '06:30:00',
            'estado' => 'en_curso',
        ]);
    }

    public function test_index_screen_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('control-recorrido.index'));
        $response->assertOk();
        $response->assertSee('Control de Recorrido');
        $response->assertSee('2020-ABC');
    }

    public function test_show_screen_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('control-recorrido.show', $this->asignacion->id));
        $response->assertOk();
        $response->assertSee('Detalle de Recorrido');
        $response->assertSee('Parada 1');
    }

    public function test_historial_screen_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('control-recorrido.historial', $this->asignacion->id));
        $response->assertOk();
        $response->assertSee('Secuencia de Paradas');
        $response->assertSee('Parada 1');
    }

    public function test_mapa_ampliado_screen_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('control-recorrido.mapa', $this->asignacion->id));
        $response->assertOk();
        $response->assertSee('mapaAmpliado');
    }

    public function test_admin_fiscalizador_propietario_can_access_monitoreo(): void
    {
        // 1. Admin
        $response = $this->actingAs($this->admin)->get(route('monitoreo.index'));
        $response->assertOk();

        // 2. Fiscalizador
        $roleFisc = Role::firstOrCreate(['name' => 'fiscalizador', 'guard_name' => 'web']);
        $fisc = User::factory()->create(['ci' => '7777777']);
        $fisc->assignRole($roleFisc);
        $this->actingAs($fisc)->get(route('monitoreo.index'))->assertOk();

        // 3. Propietario
        $roleProp = Role::firstOrCreate(['name' => 'propietario', 'guard_name' => 'web']);
        $prop = User::factory()->create(['ci' => '8888888']);
        $prop->assignRole($roleProp);
        $this->actingAs($prop)->get(route('monitoreo.index'))->assertOk();
    }

    public function test_control_recorrido_relations_work_via_has_through(): void
    {
        $control = ControlRecorrido::create([
            'asignacion_turno_id' => $this->asignacion->id,
            'fecha_hora_inicio' => now(),
            'fecha_hora' => now(),
            'estado' => 'en_curso',
        ]);

        $gps = SeguimientoGps::create([
            'control_recorrido_id' => $control->id,
            'fecha_hora_gps' => now(),
            'latitud' => -17.7830,
            'longitud' => -63.1820,
            'velocidad' => 15.0,
        ]);

        $this->assertNotNull($control->asignacionTurno);
        $this->assertSame($this->asignacion->id, $control->asignacionTurno->id);
        $this->assertSame($this->asignacion->id, $gps->asignacionTurno->id);
        $this->assertTrue($control->seguimientosGps->contains($gps));
        $this->assertTrue($this->asignacion->seguimientosGps->contains($gps));
        $this->assertTrue($this->asignacion->controlesRecorrido->contains($control));
    }
}
