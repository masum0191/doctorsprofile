<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('doctor_posts') && !Schema::hasColumn('doctor_posts', 'related_post_ids')) {
            Schema::table('doctor_posts', function (Blueprint $table) {
                $table->json('related_post_ids')->nullable()->after('meta_keywords');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('doctor_posts') && Schema::hasColumn('doctor_posts', 'related_post_ids')) {
            Schema::table('doctor_posts', function (Blueprint $table) {
                $table->dropColumn('related_post_ids');
            });
        }
    }
};
