<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('patient_name');
            $table->string('photo')->nullable();
            $table->unsignedSmallInteger('since')->nullable(); // year
            $table->unsignedTinyInteger('rating')->default(5); // 1..5
            $table->boolean('verified')->default(true)->index();
            $table->text('content');
            $table->unsignedInteger('order_column')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id','rating']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('doctor_testimonials');
    }
};
