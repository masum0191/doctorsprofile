<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChamberCustomDatesTable extends Migration
{
    public function up()
    {
        Schema::create('chamber_custom_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chamber_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration')->default(30); // in minutes
            $table->integer('max_patients')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['chamber_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chamber_custom_dates');
    }
}
