<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homestay', function (Blueprint $table) {
            $table->char('homestay_id', 36)->primary();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->char('host_id', 36)->nullable();
            $table->string('location_country', 100)->nullable();
            $table->string('location_state', 100)->nullable();
            $table->string('location_city', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->decimal('price_per_night', 10, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->decimal('cleaning_fee', 10, 2)->nullable();
            $table->string('room_type', 50)->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('beds')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('max_guests')->nullable();
            $table->decimal('rating_avg', 3, 2)->nullable();
            $table->integer('reviews_count')->nullable();
            $table->string('cancellation_policy', 50)->nullable();
            $table->string('check_in_time', 20)->nullable();
            $table->string('check_out_time', 20)->nullable();
            $table->boolean('is_guest_favorite')->default(false);
            $table->string('tags', 255)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('amenity_ids')->nullable();
        });

        Schema::create('homestay_reviews', function (Blueprint $table) {
            $table->increments('review_id');
            $table->char('homestay_id', 36);
            $table->string('user_name', 255);
            $table->text('review_text')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->timestamp('review_date')->useCurrent();

            $table->foreign('homestay_id')->references('homestay_id')->on('homestay')->onDelete('cascade');
        });

        Schema::create('homestay_images', function (Blueprint $table) {
            $table->increments('id');
            $table->char('homestay_id', 36)->nullable();
            $table->string('image_url', 255)->nullable();

            $table->foreign('homestay_id')->references('homestay_id')->on('homestay')->onDelete('cascade');
        });

        Schema::create('homestay_amenities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category', 100);
            $table->string('amenity', 100);
            $table->string('icon', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homestay_amenities');
        Schema::dropIfExists('homestay_images');
        Schema::dropIfExists('homestay_reviews');
        Schema::dropIfExists('homestay');
    }
};