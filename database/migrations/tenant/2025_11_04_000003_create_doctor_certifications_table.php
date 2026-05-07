<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('organization')->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->string('status')->nullable(); // Active / Current / Expired
            $table->text('description')->nullable();
            $table->unsignedInteger('order_column')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('doctor_certifications');
    }
};
