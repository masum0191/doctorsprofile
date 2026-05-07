<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('category')->nullable(); // Facility, Care, Technology, etc.
            $table->string('image_url');
            $table->text('caption')->nullable();
            $table->unsignedInteger('order_column')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id','category']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('doctor_galleries');
    }
};
