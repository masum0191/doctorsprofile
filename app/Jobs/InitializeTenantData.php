<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class InitializeTenantData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenantId;

    public function __construct(string $tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function handle(): void
    {
        $tenant = Tenant::find($this->tenantId);
        if (!$tenant) return;

        tenancy()->initialize($tenant);

        Artisan::call('db:seed', ['--class' => 'MedicineTemplateSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'MedicineCompanySeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'TestSeeder', '--force' => true]);

        tenancy()->end();
    }
}
