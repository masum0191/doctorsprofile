<?php

namespace Tests\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait InteractsWithCentralDatabase
{
    protected function setUpCentralDatabase(): void
    {
        config([
            'database.default' => 'mysql',
            'database.connections.mysql' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => false,
            ],
        ]);

        DB::purge('mysql');
        DB::connection('mysql')->getPdo();

        Schema::connection('mysql')->disableForeignKeyConstraints();

        foreach ([
            'coupon_usages',
            'payment_sessions',
            'payments',
            'subscriptions',
            'domains',
            'coupons',
            'packages',
            'users',
            'tenants',
        ] as $table) {
            Schema::connection('mysql')->dropIfExists($table);
        }

        $this->createCentralTables();

        Schema::connection('mysql')->enableForeignKeyConstraints();
    }

    private function createCentralTables(): void
    {
        Schema::connection('mysql')->create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->json('data')->nullable();
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('package_id')->nullable();
            $table->string('billing_cycle')->nullable();
            $table->decimal('monthly_price', 10, 2)->nullable();
            $table->decimal('yearly_price', 10, 2)->nullable();
            $table->integer('storage_gb')->nullable();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('role')->nullable();
            $table->string('tenant_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        Schema::connection('mysql')->create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_yearly', 10, 2)->default(0);
            $table->integer('storage_gb')->default(1);
            $table->unsignedInteger('max_doctors')->nullable();
            $table->unsignedInteger('max_patients')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::connection('mysql')->create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('fixed');
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('min_amount', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('usage_limit_per_user')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->string('tenant_id')->nullable();
            $table->string('type')->nullable();
            $table->decimal('registration_fee', 10, 2)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('status')->default(1);
            $table->string('tree_subdomain')->nullable();
            $table->string('tree_domain')->nullable();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->string('tenant_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->string('billing_cycle')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::connection('mysql')->create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('package_amount', 10, 2)->default(0);
            $table->decimal('domain_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('card_last_four')->nullable();
            $table->string('card_type')->nullable();
            $table->string('billing_cycle')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('payment_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->string('tenant_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('order_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('tenant_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }
}
