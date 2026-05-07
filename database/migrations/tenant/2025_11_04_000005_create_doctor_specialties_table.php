<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_specialties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('patients_treated')->default(0);
            $table->string('icon')->nullable(); // e.g., ri-heart-pulse-fill
            $table->unsignedInteger('order_column')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id','name']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('doctor_specialties');
    }
};
