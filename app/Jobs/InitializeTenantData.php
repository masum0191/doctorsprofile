<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant;
use App\Services\TenantDataInitializer;

class InitializeTenantData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenantId;
    public $timeout = 300;

    public function __construct(string $tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function handle(): void
    {
        $tenant = Tenant::find($this->tenantId);
        if (!$tenant) return;

        tenancy()->initialize($tenant);

        app(TenantDataInitializer::class)->run();

        tenancy()->end();
    }
}
