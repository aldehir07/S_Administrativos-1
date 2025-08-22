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
        Schema::create('solicitantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 30)->unique();
            $table->timestamps();
        });

        // Inserta los valores iniciales
        DB::table('solicitantes')->insert([
            ['nombre' => 'Anabel Santana'],
            ['nombre' => 'Helvetia Bernal'],
            ['nombre' => 'Melanie Taylor'],
            ['nombre' => 'Veronica de Ureña'],
            ['nombre' => 'Yesenia Delgado'],
            ['nombre' => 'Otro'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitantes');
    }
};
