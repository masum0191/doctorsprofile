<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 119)->nullable();
            $table->string('tagline')->nullable();
            $table->string('email', 119)->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('about')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('currency')->nullable();
            // SEO fields
            $table->string('meta_description', 219)->nullable();
            $table->string('keywords', 219)->nullable();
            $table->string('robots', 119)->nullable();
            $table->string('ogtitle', 119)->nullable();
            $table->string('ogdescription', 219)->nullable();
            $table->string('ogtype', 119)->nullable();
            $table->string('ogurl', 119)->nullable();
            $table->string('ogimage', 119)->nullable();
            $table->string('meta_title', 219)->nullable();

            $table->timestamps(); // replaces manual created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
