<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('organization');
            $table->unsignedSmallInteger('start_year')->nullable()->index();
            $table->unsignedSmallInteger('end_year')->nullable()->index();
            $table->text('description')->nullable();
            $table->unsignedInteger('order_column')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('doctor_experiences');
    }
};
