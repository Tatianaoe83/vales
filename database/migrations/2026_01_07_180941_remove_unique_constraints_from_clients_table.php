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
        Schema::table('clients', function (Blueprint $table) {
            try {
                $table->dropUnique(['rfc']);
            } catch (\Exception $e) {
            }

            try {
                $table->dropUnique(['email']);
            } catch (\Exception $e) {
            }
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unique('rfc');
            $table->unique('email');
        });
    }
};