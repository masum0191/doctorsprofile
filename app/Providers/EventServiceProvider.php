<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\TenantDomainCreated;
use App\Listeners\CreateCpanelDomainAlias;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \App\Events\TenantDomainCreated::class => [
        \App\Listeners\CreateCpanelDomainAlias::class,
    ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
