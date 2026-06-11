<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            if (!Schema::hasColumn('tenants', 'type')) {
                $table->string('type')->nullable()->after('data');
            }

            if (!Schema::hasColumn('tenants', 'status')) {
                $table->boolean('status')->default(true)->after('type');
            }

            if (!Schema::hasColumn('tenants', 'entity_id')) {
                $table->unsignedBigInteger('entity_id')->nullable()->after('status');
            }

            if (!Schema::hasColumn('tenants', 'division_id')) {
                $table->unsignedBigInteger('division_id')->nullable()->after('entity_id');
            }

            if (!Schema::hasColumn('tenants', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('division_id');
            }

            if (!Schema::hasColumn('tenants', 'upazilla_id')) {
                $table->unsignedBigInteger('upazilla_id')->nullable()->after('district_id');
            }

            if (!Schema::hasColumn('tenants', 'package_id')) {
                $table->unsignedBigInteger('package_id')->nullable()->after('upazilla_id');
            }

            if (!Schema::hasColumn('tenants', 'billing_cycle')) {
                $table->string('billing_cycle')->nullable()->after('package_id');
            }

            if (!Schema::hasColumn('tenants', 'monthly_price')) {
                $table->decimal('monthly_price', 10, 2)->nullable()->after('billing_cycle');
            }

            if (!Schema::hasColumn('tenants', 'yearly_price')) {
                $table->decimal('yearly_price', 10, 2)->nullable()->after('monthly_price');
            }

            if (!Schema::hasColumn('tenants', 'storage_gb')) {
                $table->unsignedInteger('storage_gb')->nullable()->after('yearly_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            foreach ([
                'storage_gb',
                'yearly_price',
                'monthly_price',
                'billing_cycle',
                'package_id',
                'upazilla_id',
                'district_id',
                'division_id',
                'entity_id',
                'status',
                'type',
            ] as $column) {
                if (Schema::hasColumn('tenants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
