<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('responsables', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 40);
            $table->string('piso', 10)->nullable();
            $table->enum('tipo', ['manual', 'completo'])->default('completo');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

         // Insertar los valores iniciales
         DB::table('responsables')->insert([
            ['nombre' => 'Arlene Tuñon', 'piso' => 'Piso 1', 'tipo' => 'manual'],
            ['nombre' => 'Luis Urriola', 'piso' => 'Piso 2', 'tipo' => 'manual'],
            ['nombre' => 'Nicol Espino', 'piso' => 'Piso 1', 'tipo' => 'completo'],
            ['nombre' => 'Ilanova Barrera', 'piso' => 'Piso 2', 'tipo' => 'completo'],
            ['nombre' => 'Luis Herrera', 'piso' => 'Piso 1', 'tipo' => 'completo'],
            ['nombre' => 'Otro', 'piso' => 'N/A', 'tipo' => 'completo']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsables');
    }
};
