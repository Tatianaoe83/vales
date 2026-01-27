<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Importante para limpiar datos

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. LIMPIEZA DE DATOS (Vital para evitar errores)
        // Antes de hacer los campos obligatorios, debemos asegurarnos de que no haya NULLs
        // Si hay un cliente sin nombre, le pondremos "Sin Nombre", etc.
        DB::table('clients')->whereNull('name')->update(['name' => 'Sin Nombre']);
        DB::table('clients')->whereNull('rfc')->update(['rfc' => 'XAXX010101000']); // RFC Genérico
        DB::table('clients')->whereNull('phone')->update(['phone' => '0000000000']);
        DB::table('clients')->whereNull('email')->update(['email' => 'sin_correo@sistema.com']);
        DB::table('clients')->whereNull('address')->update(['address' => 'Dirección Pendiente']);

        // 2. APLICAR RESTRICCIONES (Hacerlos NOT NULL)
        Schema::table('clients', function (Blueprint $table) {
            // El método ->change() modifica la columna existente
            $table->string('name')->nullable(false)->change();
            $table->string('rfc')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->text('address')->nullable(false)->change(); 
            // is_active usualmente ya tiene un default(true), así que no debería dar problemas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('rfc')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->text('address')->nullable()->change();
        });
    }
};