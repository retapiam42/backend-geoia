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
        Schema::table('denuncias', function (Blueprint $table) {
            // Agregar campos para denuncias anónimas
            $table->string('nombre_anonimo')->nullable()->after('usuario_id');
            $table->string('email_anonimo')->nullable()->after('nombre_anonimo');
            $table->string('telefono_anonimo')->nullable()->after('email_anonimo');
            
            // Agregar campos para archivos
            $table->string('archivo_path')->nullable()->after('anonima');
            $table->string('tipo_archivo')->nullable()->after('archivo_path');
            $table->string('nombre_archivo')->nullable()->after('tipo_archivo');
            
            // Agregar campos de estado y ubicación
            $table->enum('estado', ['pendiente', 'en_revision', 'resuelta', 'rechazada'])->default('pendiente')->after('nombre_archivo');
            $table->string('ubicacion')->nullable()->after('estado');
            $table->text('observaciones')->nullable()->after('ubicacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('denuncias', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_anonimo',
                'email_anonimo', 
                'telefono_anonimo',
                'archivo_path',
                'tipo_archivo',
                'nombre_archivo',
                'estado',
                'ubicacion',
                'observaciones'
            ]);
        });
    }
}; 