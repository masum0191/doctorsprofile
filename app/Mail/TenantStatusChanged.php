<?php
namespace App\Mail;
use Illuminate\Support\Facades\Mail;
class TenantStatusChanged extends Mail
{
    public $tenant;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct($tenant, $status)
    {
        $this->tenant = $tenant;
        $this->status = $status;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Tenant Status Changed')
                    ->view('emails.tenant_status_changed')
                    ->with([
                        'tenant' => $this->tenant,
                        'status' => $this->status,
                        'domain' => $this->tenant->domains->isNotEmpty() ? $this->tenant->domains->first()->domain : 'No domain',
                        'created_at' => $this->tenant->created_at,
                    ]);
    }
}
