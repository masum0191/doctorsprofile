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
        Schema::create('sliders', function (Blueprint $table) {
    $table->id();
    $table->string('title')->nullable();
    $table->string('sub_title')->nullable();
    $table->text('description')->nullable();

    $table->string('image')->nullable();
    $table->string('video_url')->nullable();

    $table->string('click_url')->nullable();
    $table->string('button_text')->nullable();
    $table->enum('target', ['_self','_blank'])->default('_self');

    $table->boolean('status')->default(1);
    $table->integer('order')->default(0);

    $table->timestamp('start_at')->nullable();
    $table->timestamp('end_at')->nullable();

    $table->unsignedBigInteger('created_by')->nullable();

    $table->timestamps();
}); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
