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
        Schema::create('donaciones', function (Blueprint $table) {
            $table->id('donacion_id');
            $table->unsignedBigInteger('usuario_id');
            $table->decimal('monto', 10, 2);
            $table->boolean('anonima');
            $table->string('metodo_pago');
            $table->timestamps();
            //$table->foreign('usuario_id')->references('usuarios_id')->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donaciones');
    }
};
