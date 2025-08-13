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
        Schema::create('responsables', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 10);
            $table->string('piso', 10);
            $table->timestamps();

        });

        //Insertar los valores iniciales
        DB::table('responsables')->insert([
            ['nombre' => 'Arline Tuñon'],
            ['nombre' => 'Luis Urriola'],
            ['nombre' => 'Otro']
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
