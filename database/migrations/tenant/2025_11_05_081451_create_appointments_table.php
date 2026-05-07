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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->foreignId('chamber_id')->nullable()->constrained('chambers')->onDelete('set null');

            // Appointment details
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('appointment_duration')->default('30 minutes');
            $table->string('appointment_number')->unique()->nullable();
            // Consultation type
            $table->enum('consultation_type', ['online', 'offline'])->default('offline');
            $table->string('service_type')->nullable();

            // Status management
            $table->enum('status', [
                'pending',
                'confirmed',
                'completed',
                'cancelled',
                'no_show',
                'rescheduled'
            ])->default('pending');

            // Patient information (denormalized for performance)
            $table->string('patient_first_name');
            $table->string('patient_last_name');
            $table->string('patient_email');
            $table->string('patient_phone');
            $table->date('patient_dob')->nullable();

            // Additional information
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->tinyInteger('rating')->nullable()->comment('Rating from 1 to 5');
            $table->text('review_comment')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency', 3)->default('BDT');

            // Payment information
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();

            // Timestamps
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['doctor_id', 'appointment_date']);
            $table->index(['patient_id', 'appointment_date']);
            $table->index(['status', 'appointment_date']);
            $table->index(['appointment_date', 'appointment_time']);
            $table->index(['consultation_type', 'status']);
            $table->index(['payment_status', 'created_at']);

            // Unique constraint to prevent double booking
            $table->unique(['doctor_id', 'appointment_date', 'appointment_time'], 'unique_doctor_time_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
