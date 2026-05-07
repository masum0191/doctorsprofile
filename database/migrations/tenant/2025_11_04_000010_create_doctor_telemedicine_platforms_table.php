<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_telemedicine_platforms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');   // Zoom, Google Meet, MS Teams, Secure Portal
            $table->string('icon')->nullable(); // ri-vidicon-fill, etc.
            $table->string('color')->nullable(); // optional UI color tag
            $table->boolean('active')->default(true)->index();
            $table->unsignedInteger('order_column')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('doctor_telemedicine_platforms');
    }
};
