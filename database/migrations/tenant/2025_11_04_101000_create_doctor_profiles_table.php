<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('doctor_profiles', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

      // Hero / headings
      $table->string('headline')->nullable();            // "Your Health, Our Priority"
      $table->string('subheadline')->nullable();         // strapline
      $table->string('hero_image')->nullable();          // primary photo url

      // About / summaries
      $table->string('tagline')->nullable();             // short one-liner
      $table->text('about_short')->nullable();           // 1–2 paras
      $table->longText('about_long')->nullable();        // detailed bio

      // KPIs shown on the page
      $table->unsignedSmallInteger('years_experience')->default(0);
      $table->unsignedInteger('patients_count')->default(0);
      $table->unsignedTinyInteger('satisfaction_rate')->default(0); // 0–100

      // Feature toggles
      $table->boolean('accepts_virtual_visits')->default(false)->index();
      $table->boolean('accepts_insurance')->default(false)->index();

      // Optional denormalized text blocks (AI can draft these)
      $table->json('sections')->nullable(); // e.g. {"online_care_intro": "...", "why_virtual":"..."}
      $table->json('meta')->nullable();     // any extra serialized data

      // Publish workflow
      $table->timestamp('published_at')->nullable();
      $table->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('doctor_profiles');
  }
};
