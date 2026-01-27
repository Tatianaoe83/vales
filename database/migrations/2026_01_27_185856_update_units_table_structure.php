<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. LIMPIEZA PREVIA: Llenar datos vacíos para evitar errores al hacerlos NOT NULL
        DB::table('units')->whereNull('placa')->update(['placa' => 'S/P']);
        DB::table('units')->whereNull('unidad_medida')->update(['unidad_medida' => 'm3']);
        DB::table('units')->whereNull('capacidad_maxima')->update(['capacidad_maxima' => 0]);

        Schema::table('units', function (Blueprint $table) {
            // 2. ELIMINAR lo que no sirve
            if (Schema::hasColumn('units', 'tipo_vehiculo')) {
                $table->dropColumn('tipo_vehiculo');
            }

            // 3. BLINDAR lo que sí sirve (Hacerlos obligatorios)
            $table->string('placa')->nullable(false)->change();
            $table->string('unidad_medida')->nullable(false)->change();
            $table->decimal('capacidad_maxima', 10, 2)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('tipo_vehiculo')->nullable(); // Restaurar campo
            $table->string('placa')->nullable()->change();
            $table->string('unidad_medida')->nullable()->change();
            $table->decimal('capacidad_maxima', 10, 2)->nullable()->change();
        });
    }
};