<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Tenant;
use Carbon\Carbon;
class UpgradeController extends Controller
{


public function index()
{
    $packages = Package::where('is_visible',1)
        ->orderBy('sort_order')
        ->get();

    // Get latest subscription
    $subscription = Subscription::where('tenant_id', tenant('id'))
        ->latest()
        ->with('package')
        ->first();

    $isActive = false;
    $currentPackageId = null;

    if ($subscription) {
        $isActive = $subscription->status === 'active'
            && \Carbon\Carbon::parse($subscription->ends_at)->isFuture();

        $currentPackageId = $subscription->package_id;
    }

    // Free package check (only one time)
    $freePackage = Package::where('slug','free')->first();

    $hasUsedFree = false;

    if ($freePackage) {
        $hasUsedFree = Subscription::where('tenant_id', tenant('id'))
            ->where('package_id', $freePackage->id)
            ->exists();
    }

    return view('doctor.packages.index', compact(
        'packages',
        'subscription',
        'isActive',
        'currentPackageId',
        'hasUsedFree'
    ));
}


public function process(Request $request)
{
    $request->validate([
        'package_id' => 'required',
        'billing_cycle' => 'required|in:monthly,yearly'
    ]);

    $tenantId = tenant('id');

    $current = Subscription::where('tenant_id',$tenantId)
        ->where('status','active')
        ->first();

    $newPackage = Package::on('mysql')->findOrFail($request->package_id);

    $price = $request->billing_cycle == 'yearly'
        ? $newPackage->price_yearly
        : $newPackage->price_monthly;

    /* ==========================
       PRORATED BILLING CALC
    ========================== */

    $credit = 0;

    if ($current) {
        $daysRemaining = now()->diffInDays($current->ends_at, false);
        $totalDays = $current->starts_at->diffInDays($current->ends_at);

        if ($daysRemaining > 0 && $totalDays > 0) {
            $dailyRate = $current->package->price_monthly / $totalDays;
            $credit = $dailyRate * $daysRemaining;
        }
    }

    $finalAmount = max(0, $price - $credit);

    /* ==========================
       PAYMENT RECORD
    ========================== */

    $payment = Payment::create([
        //'tenant_id' => $tenantId,
        'user_id' => auth()->id(),
        'package_id' => $newPackage->id,
        'amount' => $finalAmount,
        'status' => 'panding',
        'billing_cycle' => $request->billing_cycle,


    ]);

    /* ==========================
       EXPIRE OLD SUBSCRIPTION
    ========================== */

    if ($current) {
        $current->update(['status'=>'expired']);
    }

    /* ==========================
       CREATE NEW SUBSCRIPTION
    ========================== */

    Subscription::on('mysql')->create([
        'doctor_id' => $current->doctor_id,
        'tenant_id' => $tenantId,
        'package_id'=> $newPackage->id,
        'billing_cycle'=> $request->billing_cycle,
        'starts_at' => now(),
        'ends_at' => $request->billing_cycle == 'yearly'
                        ? now()->addYear()
                        : now()->addMonth(),
        //'status' => 'active',
    ]);

    /* ==========================
       UPDATE TENANT FEATURE
    ========================== */

    $tenant = Tenant::find($tenantId);

$tenant->data = array_merge($tenant->data ?? [], [
    'package_id' => $newPackage->id,
    'billing_cycle' => $request->billing_cycle,
    'monthly_price' => $newPackage->price_monthly,
    'yearly_price'  => $newPackage->price_yearly,
    'storage_gb'    => $newPackage->storage_gb,
]);

$tenant->status = 1;
$tenant->save();

    return redirect()->route('admin.dashboard')
        ->with('success','Subscription upgraded successfully.');
}
}
