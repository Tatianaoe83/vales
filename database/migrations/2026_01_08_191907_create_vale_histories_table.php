<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_vale_histories_table.php

public function up(): void
{
    Schema::create('vale_histories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vale_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->nullable()->constrained();
        
        $table->string('estatus_anterior')->nullable();
        $table->string('estatus_nuevo');
        $table->text('comentarios')->nullable();
        
        $table->timestamp('created_at')->useCurrent(); 
    });
}
};
