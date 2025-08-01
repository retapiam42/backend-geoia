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
        Schema::create('adjuntos', function (Blueprint $table) {
            $table->id('adjutos_id');
            $table->unsignedBigInteger('denuncia_id');
            $table->string('archivo'); // path al archivo
            $table->string('tipo'); // imagen, pdf, etc.
            $table->timestamps();
            //$table->foreign('denuncia_id')->references('id')->on('denuncias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjuntos');
    }
};
