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
            $table->id('movimientos_id');
            $table->unsignedBigInteger('donacion_id');
            $table->string('tipo'); // ingreso, egreso, etc.
            $table->decimal('monto', 10, 2);
            $table->text('descripcion');
            $table->timestamps();
            //$table->foreign('donacion_id')->references('id')->on('donaciones')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
