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
        Schema::table('homestay', function (Blueprint $table) {
            $table->timestamps(); // This adds both 'created_at' and 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homestay', function (Blueprint $table) {
            $table->dropTimestamps(); // This removes both 'created_at' and 'updated_at'
        });
    }
};