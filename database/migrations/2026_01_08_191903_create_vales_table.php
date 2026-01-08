<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_vales_table.php

public function up(): void
{
    Schema::create('vales', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('sale_id')->constrained()->onDelete('cascade');
        
        $table->string('folio_vale', 20)->unique(); 
        $table->uuid('uuid')->unique(); 
        
        $table->foreignId('material_id')->constrained();
        $table->decimal('cantidad', 8, 2); 
        
        $table->foreignId('unit_id')->nullable()->constrained('units');
        
        $table->enum('estatus', [
            'Vigente',     
            'En Planta',    
            'Surtido',      
            'Vencido',      
            'Cancelado'     
        ])->default('Vigente');

        $table->timestamps();
    });
}
};
