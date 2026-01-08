<?php
// database/migrations/2026_01_08_000000_create_units_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); 
            
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');

            $table->string('placa', 10)->unique();
            
            $table->enum('tipo_vehiculo', ['Olla', 'Volteo', 'Plataforma', 'Gondola']);
            $table->decimal('capacidad_maxima', 8, 2); 
            $table->enum('unidad_medida', ['m3', 'toneladas']); 
            
            // Control de acceso
            $table->boolean('is_active')->default(true); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};