<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('ai_content_drafts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();

      // Which part is this draft for?
      $table->string('target');
      // examples: profile.headline, profile.about_short, specialties, services, certifications, affiliations, experiences, educations, faqs, testimonials, gallery.captions, online_care_intro, etc.

      // Raw AI payload (text or structured JSON for bulk sections)
      $table->json('payload');

      // Workflow
      $table->enum('status', ['draft','accepted','rejected'])->default('draft')->index();
      $table->string('source')->default('ai'); // ai|manual|import
      $table->text('notes')->nullable();

      $table->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('ai_content_drafts');
  }
};
