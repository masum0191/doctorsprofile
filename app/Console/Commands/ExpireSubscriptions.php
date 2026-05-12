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

    if (!$freePackage) {
        $this->error('Free package was not found. Expired subscriptions were not processed.');

        return self::FAILURE;
    }

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
        $tenant = Tenant::find($sub->tenant_id);

        if ($tenant) {
            $tenant->data = array_merge($tenant->data ?? [], [
                'package_id' => $freePackage->id,
                'package_name' => $freePackage->name,
                'package_features' => $freePackage->featureMap(),
                'billing_cycle' => 'monthly',
                'monthly_price' => $freePackage->price_monthly,
                'yearly_price' => $freePackage->price_yearly,
                'storage_gb' => $freePackage->storage_gb,
            ]);
            $tenant->package_id = $freePackage->id;
            $tenant->status = 1;
            $tenant->save();
        }
    }

    $this->info('Expired subscriptions processed successfully.');

    return self::SUCCESS;
}

}
