<?php

namespace Tests\Unit;

use App\Support\TenancyHelpers;
use Tests\TestCase;

class TenancyHelpersTest extends TestCase
{
    public function test_ensure_tenant_connection_seeds_missing_tenant_connection_from_mysql(): void
    {
        config([
            'database.connections.mysql' => [
                'driver' => 'mysql',
                'host' => '127.0.0.1',
                'database' => 'central',
                'username' => 'root',
            ],
        ]);
        $connections = config('database.connections');
        unset($connections['tenant']);
        config(['database.connections' => $connections]);

        TenancyHelpers::ensureTenantConnection();

        $this->assertSame('mysql', config('database.connections.tenant.driver'));
        $this->assertSame('127.0.0.1', config('database.connections.tenant.host'));
        $this->assertNull(config('database.connections.tenant.database'));
    }

    public function test_ensure_tenant_connection_keeps_existing_connection(): void
    {
        config([
            'database.connections.tenant' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
            ],
        ]);

        TenancyHelpers::ensureTenantConnection();

        $this->assertSame('sqlite', config('database.connections.tenant.driver'));
        $this->assertSame(':memory:', config('database.connections.tenant.database'));
    }
}
