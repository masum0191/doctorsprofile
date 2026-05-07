<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint unsigned primary key
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps(); // created_at & updated_at
            $table->string('role', 119)->default('user');
            $table->integer('type')->nullable();
            $table->string('nid', 119)->nullable();
            $table->string('mobile', 119)->nullable();
            $table->string('acount_type', 119)->nullable();
            $table->json('extra_data')->nullable();
            $table->string('qualification', 119)->nullable();
            $table->string('reg_no', 119)->nullable();
            $table->string('city', 119)->nullable();
            $table->string('country', 119)->nullable();
            $table->string('latitude', 119)->nullable();
            $table->string('longitude', 119)->nullable();
            $table->string('is_available_today')->nullable();
            $table->string('accepts_virtual_visits')->nullable();
            $table->string('accepts_insurance')->nullable();
            $table->string('rating')->nullable();
            $table->string('photo')->nullable();
            $table->json('specialization')->nullable();
            //$table->string('address')->nullable();
            $table->string('licence')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('state')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['male','female','other'])->nullable();
            $table->text('address')->nullable();
            // status active/inactive
            $table->boolean('status')->default(true);
            $table->string('package')->nullable();



    $table->string('vitality')->nullable();

    $table->json('emergency_contact')->nullable();
    $table->json('basic_details')->nullable();

    $table->text('medical_history')->nullable();
            // state


            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
