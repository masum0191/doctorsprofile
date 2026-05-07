<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto increment
            $table->string('site_name')->nullable();
            $table->string('site_name_en')->nullable();
            $table->text('about')->nullable();
            $table->string('tagline')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('signature')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->text('footer_text')->nullable();


            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();



            $table->string('meta_title', 219)->nullable();
            $table->string('meta_description', 219)->nullable();
            $table->string('keywords', 219)->nullable();
            $table->string('robots', 119)->nullable();
            $table->string('ogtitle', 119)->nullable();
            $table->string('ogdescription', 219)->nullable();
            $table->string('ogtype', 119)->nullable();
            $table->string('ogurl', 119)->nullable();
            $table->string('ogimage', 119)->nullable();
            // Google Analytics ID
            $table->string('google_analytics_id', 119)->nullable();
            $table->string('facebook_pixel_id', 119)->nullable();

            $table->string('facebook_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('twitter_url', 219)->nullable();
            $table->string('instagram_url', 219)->nullable();
            $table->string('linkedin_url', 219)->nullable();
            $table->string('whatsapp_number', 219)->nullable();
            $table->string('telegram_url', 219)->nullable();
            $table->string('tiktok_url', 219)->nullable();
            $table->text('worktime')->nullable();
            $table->string('latitude', 219)->nullable();
            $table->string('longitude', 219)->nullable();
            $table->string('office_name', 219)->nullable();
            $table->string('address_line1', 219)->nullable();
            $table->string('address_line2', 219)->nullable();
            $table->string('district', 219)->nullable();
            $table->string('post_code', 219)->nullable();
            $table->string('helpline_number', 219)->nullable();
            $table->string('support_email', 219)->nullable();
            $table->string('general_email', 219)->nullable();
            $table->string('fax_number', 219)->nullable();
            $table->string('emergency_number', 219)->nullable();
            $table->string('template', 219)->nullable();


            $table->json('extra_data')->nullable();
            $table->json('email_settings')->nullable();
            $table->json('sms')->nullable();
            $table->json('payment_gateway')->nullable();
             $table->json('online_schedule')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
