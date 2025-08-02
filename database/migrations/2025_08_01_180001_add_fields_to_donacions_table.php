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
        Schema::table('donacions', function (Blueprint $table) {
            // Agregar campos para donaciones anónimas
            $table->string('nombre_anonimo')->nullable()->after('usuario_id');
            $table->string('email_anonimo')->nullable()->after('nombre_anonimo');
            $table->string('telefono_anonimo')->nullable()->after('email_anonimo');
            
            // Agregar campos de referencia de pago
            $table->string('referencia_pago')->nullable()->after('documento_path');
            $table->string('numero_transaccion')->nullable()->after('referencia_pago');
            $table->enum('estado_pago', ['pendiente', 'confirmado', 'rechazado', 'cancelado'])->default('pendiente')->after('numero_transaccion');
            $table->timestamp('fecha_pago')->nullable()->after('estado_pago');
            
            // Agregar campos adicionales para archivos
            $table->string('tipo_archivo')->nullable()->after('documento_path');
            $table->string('nombre_archivo')->nullable()->after('tipo_archivo');
            
            // Agregar campos de estado general
            $table->enum('estado', ['activa', 'completada', 'cancelada'])->default('activa')->after('fecha_pago');
            $table->text('comentarios')->nullable()->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donacions', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_anonimo',
                'email_anonimo',
                'telefono_anonimo',
                'referencia_pago',
                'numero_transaccion',
                'estado_pago',
                'fecha_pago',
                'tipo_archivo',
                'nombre_archivo',
                'estado',
                'comentarios'
            ]);
        });
    }
}; 