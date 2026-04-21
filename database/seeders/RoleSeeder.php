<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $conductor = Role::firstOrCreate(['name' => 'conductor']);
        $dueño = Role::firstOrCreate(['name' => 'dueño']);

        // Crear permisos para conductores
        Permission::firstOrCreate(['name' => 'conductores.index']);
        Permission::firstOrCreate(['name' => 'conductores.create']);
        Permission::firstOrCreate(['name' => 'conductores.edit']);
        Permission::firstOrCreate(['name' => 'conductores.destroy']);
        Permission::firstOrCreate(['name' => 'conductores.show']);

        // Crear permisos para micros
        Permission::firstOrCreate(['name' => 'micros.index']);
        Permission::firstOrCreate(['name' => 'micros.create']);
        Permission::firstOrCreate(['name' => 'micros.edit']);
        Permission::firstOrCreate(['name' => 'micros.destroy']);
        Permission::firstOrCreate(['name' => 'micros.show']);

        // Crear permisos para rutas
        Permission::firstOrCreate(['name' => 'ruta.index']);
        Permission::firstOrCreate(['name' => 'ruta.create']);
        Permission::firstOrCreate(['name' => 'ruta.edit']);
        Permission::firstOrCreate(['name' => 'ruta.destroy']);
        Permission::firstOrCreate(['name' => 'ruta.show']);

        // Crear permisos para turnos
        Permission::firstOrCreate(['name' => 'turnos.index']);
        Permission::firstOrCreate(['name' => 'turnos.create']);
        Permission::firstOrCreate(['name' => 'turnos.edit']);
        Permission::firstOrCreate(['name' => 'turnos.destroy']);
        Permission::firstOrCreate(['name' => 'turnos.show']);

        // Asignar permisos a roles
        // Admin tiene todos los permisos
        $admin->syncPermissions(Permission::all());

        // Conductor: solo ver conductores y turnos (index, show), no puede eliminar turnos
        $conductor->syncPermissions([
            'conductores.index', 'conductores.show',
            'turnos.index', 'turnos.show'
        ]);

        // Dueño: permisos para conductores y micros (index, create, edit, show), rutas (index, create, edit, show)
        $dueño->syncPermissions([
            'conductores.index', 'conductores.create', 'conductores.edit', 'conductores.show',
            'micros.index', 'micros.create', 'micros.edit', 'micros.show',
            'ruta.index', 'ruta.create', 'ruta.edit', 'ruta.show'
        ]);
    }
}
