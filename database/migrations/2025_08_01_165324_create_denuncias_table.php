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
        Schema::create('denuncias', function (Blueprint $table) {
            $table->id('denuncias_id');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('titulo');
            $table->text('descripcion');
            $table->boolean('anonima')->default(false);
            $table->timestamps();
            
            $table->foreign('usuario_id')->references('usuarios_id')->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denuncias');
    }
};
