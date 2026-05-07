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
            $table->text('about')->nullable();
            $table->string('tagline')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('govt_logo')->nullable();
            $table->string('signature')->nullable();
            $table->double('trade_license_fee')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->text('footer_text')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->string('templete')->nullable();


            $table->string('meta_description', 219);
            $table->string('keywords', 219);
            $table->string('robots', 119);
            $table->string('ogtitle', 119);
            $table->string('ogdescription', 219);
            $table->string('ogtype', 119);
            $table->string('ogurl', 119);
            $table->string('ogimage', 119);

           

            $table->string('watermark')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('twitter_url', 219)->nullable();
            $table->string('instagram_url', 219)->nullable();
            $table->string('linkedin_url', 219)->nullable();
            $table->string('whatsapp_number', 219)->nullable();
            $table->string('telegram_url', 219)->nullable();
            $table->string('tiktok_url', 219)->nullable();

            $table->string('address_line1', 219)->nullable();
            $table->string('address_line2', 219)->nullable();
            $table->string('district', 219)->nullable();
            $table->string('post_code', 219)->nullable();
            $table->string('helpline_number', 219)->nullable();
            $table->string('support_email', 219)->nullable();
            $table->string('general_email', 219)->nullable();
            $table->string('fax_number', 219)->nullable();



            $table->string('store_id')->nullable();
            $table->string('store_pass')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
