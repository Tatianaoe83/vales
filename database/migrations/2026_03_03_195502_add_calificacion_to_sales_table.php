<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // null = sin calificar, 1-5 = puntuación
            $table->unsignedTinyInteger('calificacion')->nullable()->after('notas');
            $table->timestamp('calificacion_at')->nullable()->after('calificacion');
            // Flag para que la pantalla sepa que hay una nueva venta lista para calificar
            $table->boolean('pendiente_calificacion')->default(false)->after('calificacion_at');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['calificacion', 'calificacion_at', 'pendiente_calificacion']);
        });
    }
};