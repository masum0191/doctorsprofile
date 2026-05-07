<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_incomes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('doctor_id');

            $table->enum('type', ['register', 'appointment']);

            $table->string('patient_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->decimal('amount', 10, 2);

            $table->enum('payment_status', ['pending', 'paid', 'failed'])
                  ->default('pending');

            $table->timestamps();

            // Foreign key
            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('doctors')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_incomes');
    }
};
