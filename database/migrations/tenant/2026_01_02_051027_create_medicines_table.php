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
           Schema::create('medicines', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('dosage')->nullable();       // e.g. 500mg
    $table->string('frequency')->nullable();    // e.g. 2 times/day
    $table->string('duration')->nullable();     // e.g. 7 days
    $table->text('instruction')->nullable();    // after meal, before meal
    $table->string('type')->nullable();          // tablet, syrup, injection
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
