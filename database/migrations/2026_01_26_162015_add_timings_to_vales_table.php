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
        Schema::table('vales', function (Blueprint $table) {
            $table->timestamp('fecha_entrada')->nullable()->after('estatus');
            $table->timestamp('fecha_salida')->nullable()->after('fecha_entrada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vales', function (Blueprint $table) {
            $table->dropColumn(['fecha_entrada', 'fecha_salida']);
        });
    }
};