<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('propietarios', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 50);
        $table->string('apellido', 50);
        $table->string('carnet_identidad', 20)->unique();
        // Se cambió a string para evitar errores con ceros a la izquierda o extensiones
        $table->string('telefono', 20)->nullable(); 
        // Se añade un valor por defecto (true = activo) para no enviarlo obligatoriamente cada vez
        $table->boolean('estado')->default(true); 
        $table->date('fecha_registro');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propietarios');
    }
};
