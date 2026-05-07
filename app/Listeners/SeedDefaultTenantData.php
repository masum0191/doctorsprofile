<?php

namespace App\Listeners;

use App\Events\Stancl\Tenancy\Events\TenantCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Setting;
use App\Models\Fee;
use App\Models\User;


class SeedDefaultTenantData
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenantCreated $event): void
    {
       // Default settings
        Setting::create([
            'site_name' => 'Default Company',
            'tagline' => 'Your tagline here',
            'email' => 'default@example.com',
            'site_logo' => 'default_logo.png',
            'address' => '123 Default St, City, Country',
            'phone' => '1234567890',
            'about' => 'This is the default company description.',
            'footer_text' => 'Default footer text',
            // … add the rest of your defaults here …
        ]);

        // Default fees
        Fee::create([
            'vdf_fee' => 0.00,
            'service_charge' => 0.00,
            'detention_fee' => 0.00,
        ]);

        // Default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'nid' => '0000000000',
            'phone' => '0000000000',
        ]);
    }
}
