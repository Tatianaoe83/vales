<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_create_sales_table.php

public function up(): void
{
    Schema::create('sales', function (Blueprint $table) {
        $table->id();
        
        $table->string('folio', 20)->unique();
        $table->foreignId('client_id')->constrained()->onDelete('restrict');
        $table->foreignId('user_id')->constrained();
        
        $table->enum('tipo_venta', ['Contado', 'Credito']);
        $table->date('fecha_vencimiento'); 
        
        $table->decimal('subtotal', 12, 2);
        $table->decimal('descuento', 12, 2)->default(0);
        $table->decimal('iva', 12, 2);
        $table->decimal('total', 12, 2);
        
        $table->text('notas')->nullable();
        
        $table->timestamps();
    });
}
};
