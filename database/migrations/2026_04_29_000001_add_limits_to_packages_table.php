<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('packages')) {
            return;
        }

        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'max_doctors')) {
                $table->unsignedInteger('max_doctors')->nullable()->after('storage_gb');
            }

            if (!Schema::hasColumn('packages', 'max_patients')) {
                $table->unsignedInteger('max_patients')->nullable()->after('max_doctors');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('packages')) {
            return;
        }

        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'max_patients')) {
                $table->dropColumn('max_patients');
            }

            if (Schema::hasColumn('packages', 'max_doctors')) {
                $table->dropColumn('max_doctors');
            }
        });
    }
};
