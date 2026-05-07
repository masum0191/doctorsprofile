<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');

            $table->decimal('amount', 10, 2);
            $table->date('date');

            $table->string('purpose')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('doctor_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('patient_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
