<?php

namespace App\Events;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

// ... other use statements
use App\Models\Domain; // Make sure this is your Domain model

class TenantDomainCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $domain;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Domain $domain
     * @return void
     */
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }
}