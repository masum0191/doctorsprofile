<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('category_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->longText('cover_image')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->unsignedSmallInteger('read_minutes')->nullable(); // optional override
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);

            // optional SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();

            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('order_column')->default(0); // if you want manual pinning/order
            $table->timestamps();

            $table->index(['user_id','is_published','published_at']);
          //  $table->index('category');
        });
    }

    public function down(): void {
        Schema::dropIfExists('doctor_posts');
    }
};
