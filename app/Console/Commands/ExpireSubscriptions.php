<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\Package;
use App\Models\Tenant;
class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */


public function handle()
{
    $expiredSubs = Subscription::where('status','active')
        ->where('ends_at','<',now())
        ->get();

    $freePackage = Package::where('slug','free')->first();

    foreach ($expiredSubs as $sub) {

        // 1️⃣ Expire current subscription
        $sub->update(['status' => 'expired']);

        // 2️⃣ Check if already has active free
        $existingFree = Subscription::where('doctor_id',$sub->doctor_id)
            ->where('status','active')
            ->where('package_id',$freePackage->id)
            ->first();

        if (!$existingFree) {

            Subscription::create([
                'doctor_id'   => $sub->doctor_id,
                'tenant_id'   => $sub->tenant_id,
                'package_id'  => $freePackage->id,
                'billing_cycle'=> 'monthly',
                'starts_at'   => now(),
                'ends_at'     => now()->addYears(10),
                'status'      => 'active',
            ]);
        }

        // 3️⃣ Update tenant status (keep active but downgraded)
        Tenant::where('id',$sub->tenant_id)
            ->update(['status'=>1]);
    }

    $this->info('Expired subscriptions processed successfully.');
}

}
