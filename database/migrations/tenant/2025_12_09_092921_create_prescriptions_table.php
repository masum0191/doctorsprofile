<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');

            $table->date('prescribed_date')->default(DB::raw('CURRENT_DATE'));

            $table->string('chief_complaint')->nullable();   // main complaint
            $table->text('diagnosis')->nullable();           // diagnosis
            $table->text('instructions')->nullable();        // general advice

            // simple medicines text for now (you can later make a `prescription_items` table)
            $table->json('medicines')->nullable();
            $table->json('tests')->nullable();
              // "Tab ABC 500mg – 1+0+1 x 7 days" etc.

            $table->string('next_visit_date')->nullable();   // or use date
            $table->string('status')->default('active');     // active / archived etc.

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
