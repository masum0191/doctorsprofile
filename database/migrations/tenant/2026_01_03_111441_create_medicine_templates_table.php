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
        Schema::create('medicine_templates', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_name');
            $table->string('company_name')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('medicine_type')->nullable();   // Tablet, Syrup, Injection
            $table->string('medicine_dose')->nullable();   // 1+0+1
            $table->string('medicine_day')->nullable();    // 5 Days
            $table->string('medicine_mg')->nullable();     // 500 mg
            $table->text('medicine_comments')->nullable();
            $table->text('medicine_description')->nullable();
            $table->string('medicine_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_templates');
    }
};
