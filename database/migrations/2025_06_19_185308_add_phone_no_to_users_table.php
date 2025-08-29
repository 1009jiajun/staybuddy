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
        Schema::table('users', function (Blueprint $table) {
            // Add the 'phoneNo' column after the 'password' column (optional placement)
            $table->string('phoneNo')->nullable()->after('password');
            // If you want it to be required: $table->string('phoneNo')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the 'phoneNo' column if rolling back
            $table->dropColumn('phoneNo');
        });
    }
};