<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mysql')->table('settings', function (Blueprint $table) {
            if (!Schema::connection('mysql')->hasColumn('settings', 'extra_data')) {
                $table->json('extra_data')->nullable()->after('store_pass');
            }

            if (!Schema::connection('mysql')->hasColumn('settings', 'payment_gateway')) {
                $table->json('payment_gateway')->nullable()->after('extra_data');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->table('settings', function (Blueprint $table) {
            if (Schema::connection('mysql')->hasColumn('settings', 'payment_gateway')) {
                $table->dropColumn('payment_gateway');
            }

            if (Schema::connection('mysql')->hasColumn('settings', 'extra_data')) {
                $table->dropColumn('extra_data');
            }
        });
    }
};
