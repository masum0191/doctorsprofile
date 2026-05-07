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
       Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug');
        $table->text('description')->nullable();

        // Pricing
        $table->decimal('price_monthly', 8, 2); // E.g., 10.00
        $table->decimal('price_yearly', 8, 2);  // E.g., 96.00

        // Settings/Features
        $table->integer('storage_gb')->default(1);
        $table->boolean('is_visible')->default(true);
        $table->integer('sort_order')->default(0);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
