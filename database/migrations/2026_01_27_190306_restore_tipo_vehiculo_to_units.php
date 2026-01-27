<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            // 1. Si la columna NO existe, la creamos
            if (!Schema::hasColumn('units', 'tipo_vehiculo')) {
                // La creamos nullable al principio o con default para evitar errores con datos existentes
                $table->string('tipo_vehiculo')->default('Volteo')->after('placa');
            }
        });

        // 2. Aseguramos que no haya nulos (Limpieza)
        DB::table('units')->whereNull('tipo_vehiculo')->update(['tipo_vehiculo' => 'Volteo']);

        // 3. Ahora sí, hacemos todo OBLIGATORIO (NOT NULL)
        Schema::table('units', function (Blueprint $table) {
            $table->string('tipo_vehiculo')->nullable(false)->change(); // El que recuperamos
            
            // Confirmamos los demás por si acaso
            $table->string('placa')->nullable(false)->change();
            $table->decimal('capacidad_maxima', 10, 2)->nullable(false)->change();
            $table->string('unidad_medida')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        // Si revertimos esto, volvemos a eliminar el tipo_vehiculo
        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units', 'tipo_vehiculo')) {
                $table->dropColumn('tipo_vehiculo');
            }
        });
    }
};