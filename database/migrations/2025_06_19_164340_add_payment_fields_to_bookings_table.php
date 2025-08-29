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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('external_reference')->nullable()->after('status');
            $table->string('toyyibpay_bill_code')->nullable()->after('external_reference');
            $table->string('transaction_id')->nullable()->after('toyyibpay_bill_code');
            $table->decimal('total_amount', 10, 2)->nullable()->after('total_guests');
            $table->timestamp('paid_at')->nullable()->after('transaction_id');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'external_reference',
                'toyyibpay_bill_code',
                'transaction_id',
                'total_amount',
                'paid_at',
            ]);
        });
    }
};