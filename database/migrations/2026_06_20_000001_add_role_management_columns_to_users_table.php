<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 50)->default('user')->after('password')->index();
            }

            if (!Schema::hasColumn('users', 'status')) {
                $table->boolean('status')->default(true)->after('role')->index();
            }

            if (!Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile', 30)->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'tenant_id')) {
                $table->string('tenant_id')->nullable()->after('status')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['tenant_id', 'mobile', 'status', 'role'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
