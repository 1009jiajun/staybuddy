<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('homestay', function (Blueprint $table) {
            $table->text('rules')->nullable()->after('tags'); // or after any relevant column
        });
    }

    public function down(): void
    {
        Schema::table('homestay', function (Blueprint $table) {
            $table->dropColumn('rules');
        });
    }
};