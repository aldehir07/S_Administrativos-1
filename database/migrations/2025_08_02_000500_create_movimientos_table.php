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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_movimiento');
            $table->unsignedBigInteger('producto_id'); //Relacion con productos/insumos
            $table->unsignedBigInteger('clasificacion_id');
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->integer('cantidad');
            $table->date('fecha');
            $table->string('lote')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('evento')->nullable();
            $table->unsignedBigInteger('solicitante_id')->nullable();
            $table->string('motivo')->nullable();
            $table->text('observaciones')->nullable();

            //LLaves foraneas
            $table->foreign('clasificacion_id')->references('id')->on('clasificaciones');
            $table->foreign('solicitante_id')->references('id')->on('solicitantes');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('responsable_id')->references('id')->on('responsables');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
