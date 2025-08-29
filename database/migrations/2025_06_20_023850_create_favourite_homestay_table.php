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
        Schema::create('favourite_homestays', function (Blueprint $table) {
            $table->uuid('user_id'); // Assuming user_id is a UUID
            $table->uuid('homestay_id'); // Assuming homestay_id is a UUID
            // Automatically sets current timestamp on creation + 8 more hours
            $table->timestamp('added_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            // Define composite primary key to prevent duplicate favorites
            $table->primary(['user_id', 'homestay_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourite_homestays');
    }
};