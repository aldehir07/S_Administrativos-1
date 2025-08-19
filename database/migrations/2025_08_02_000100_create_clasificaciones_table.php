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
        Schema::create('clasificaciones', function (Blueprint $table) {
            $table->id();
            $table->enum('nombre', ['comestibles', 'desechables', 'utiles de oficina', 'insumos de limpieza', 'Certificado'])->unique();
            $table->timestamps();
        });

            DB::table('clasificaciones')->insert([
                ['nombre' => 'comestibles'],
                ['nombre' => 'desechables'],
                ['nombre' => 'utiles de oficina'],
                ['nombre' => 'insumos de limpieza'],
                ['nombre' => 'Certificado']
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clasificaciones');
    }
};
