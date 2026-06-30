<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payment_sessions')) {
            return;
        }

        Schema::table('payment_sessions', function (Blueprint $table): void {
            if (! Schema::hasColumn('payment_sessions', 'metadata')) {
                $table->json('metadata')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('payment_sessions')) {
            return;
        }

        Schema::table('payment_sessions', function (Blueprint $table): void {
            if (Schema::hasColumn('payment_sessions', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }
};
