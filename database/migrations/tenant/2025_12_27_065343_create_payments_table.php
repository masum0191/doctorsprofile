<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relationships


            $table->foreignId('user_id')
                ->nullable();


            $table->foreignId('package_id')
                ->nullable();
               // ->constrained();
              //  ->nullOnDelete();

            $table->foreignId('coupon_id')
                ->nullable();
               // ->constrained()
               // ->nullOnDelete();

            // Amounts
            $table->decimal('amount', 10, 2);
            $table->decimal('package_amount', 10, 2)->nullable();
            $table->decimal('domain_amount', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();

            // Payment details
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending');
            $table->string('transaction_id')->nullable()->index();

            // Card details (optional)
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_type')->nullable();

            // Billing
            $table->string('billing_cycle')->nullable();
            $table->timestamp('payment_date')->nullable();

            // Extra data
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
