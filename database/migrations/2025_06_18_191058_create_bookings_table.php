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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            // Foreign key to homestays table - restrict deletion of homestay if bookings exist
            $table->char('homestay_id', 36); // Define the column with the correct type and length
            // Foreign key to users table - set user_id to NULL if user is deleted
            $table->char('user_id', 36)->nullable();
            $table->date('check_in_date'); // Date column for check-in
            $table->date('check_out_date'); // Date column for check-out
            $table->integer('total_guests'); // Integer column for total guests
            $table->string('status')->default('pending'); // String column for status, with a default value
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
