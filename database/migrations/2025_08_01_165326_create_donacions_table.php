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
        Schema::create('donacions', function (Blueprint $table) {
            $table->id('donacions_id');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->decimal('monto', 10, 2);
            $table->boolean('anonima')->default(false);
            $table->string('metodo_pago');
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('documento_path')->nullable();
            $table->timestamps();
            
            $table->foreign('usuario_id')->references('usuarios_id')->on('usuarios')->onDelete('set null');
            $table->foreign('proyecto_id')->references('proyectos_id')->on('proyectos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donacions');
    }
};
