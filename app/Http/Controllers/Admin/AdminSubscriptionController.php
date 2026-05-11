<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    public function index(Request $request)
{
    $query = Subscription::with(['package','doctor'])
        ->orderByDesc('id');

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by package
    if ($request->filled('package')) {
        $query->where('package_id', $request->package);
    }

    // Filter by date
    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('starts_at', [
            $request->from,
            $request->to
        ]);
    }

    $subscriptions = $query->paginate(20);

    $packages = \App\Models\Package::all();

    return view('admin.subscriptions.index', compact(
        'subscriptions',
        'packages'
    ));
}
    public function approve($id)
{
    $subscription = Subscription::with(['package', 'doctor'])->findOrFail($id);

    if ($subscription->status !== 'pending') {
        return back()->with('error','Already processed.');
    }

    $package = $subscription->package ?: Package::find($subscription->package_id);

    if (!$package) {
        return back()->with('error', 'Package not found.');
    }

    $doctor = $subscription->doctor ?: User::where('id', $subscription->doctor_id)->first();
    $tenantId = $subscription->tenant_id ?: ($doctor->tenant_id ?? null);

    if (!$tenantId) {
        return back()->with('error', 'Tenant not resolved.');
    }

    $tenant = Tenant::find($tenantId);

    if (!$tenant) {
        return back()->with('error', 'Doctor tenant account was not found.');
    }

    $subscription->update([
        'status' => 'active',
        'starts_at' => now(),
        'ends_at' => $subscription->billing_cycle === 'yearly'
            ? now()->addYear()
            : now()->addMonth()
    ]);

    Subscription::where('tenant_id', $tenantId)
        ->where('id', '!=', $subscription->id)
        ->where('status', 'active')
        ->update(['status' => 'expired']);

    $this->syncTenantPackage($tenant, $package, $subscription->billing_cycle);
    $this->syncTenantPaymentAndDoctor($tenant, $subscription, $doctor, $package);

    return back()->with('success','Subscription activated.');
}
public function cancel($id)
{
    $subscription = Subscription::findOrFail($id);

    $subscription->update(['status' => 'cancelled']);

    return back()->with('success','Subscription cancelled.');
}


    /* ===============================
       EXTEND SUBSCRIPTION
    =============================== */
   public function extend(Request $request, $id)
{
    $request->validate([
        'days' => 'required|integer|min:1|max:365'
    ]);

    $subscription = Subscription::findOrFail($id);

    if ($subscription->status !== 'active') {
        return back()->with('error','Only active subscription can be extended.');
    }

    $days = (int) $request->days; // 🔥 Cast to int

    $newEndDate = Carbon::parse($subscription->ends_at)
                    ->addDays($days);

    $subscription->update([
        'ends_at' => $newEndDate
    ]);

    return back()->with('success','Subscription extended successfully.');
}
public function sendMail(Request $request, $id)
{
    $request->validate([
        'message' => 'required|string'
    ]);

    $subscription = Subscription::with('doctor')->findOrFail($id);

    if (!$subscription->doctor) {
        return back()->with('error', 'Doctor not found.');
    }

    Mail::raw($request->message, function ($mail) use ($subscription) {
        $mail->to($subscription->doctor->email)
             ->subject('Message from Super Admin');
    });

    return back()->with('success', 'Message sent successfully.');
}

private function syncTenantPackage(Tenant $tenant, Package $package, ?string $billingCycle): void
{
    $tenant->data = array_merge($tenant->data ?? [], [
        'package_id' => $package->id,
        'package_name' => $package->name,
        'package_features' => $package->featureMap(),
        'billing_cycle' => $billingCycle,
        'monthly_price' => $package->price_monthly,
        'yearly_price' => $package->price_yearly,
        'storage_gb' => $package->storage_gb,
    ]);

    $tenant->status = 1;
    $tenant->save();
}

private function syncTenantPaymentAndDoctor(Tenant $tenant, Subscription $subscription, ?User $doctor, Package $package): void
{
    tenancy()->initialize($tenant);

    try {
        Payment::where('package_id', $subscription->package_id)
            ->when($doctor, fn ($query) => $query->where('user_id', $doctor->id))
            ->latest()
            ->first()
            ?->update([
                'status' => 'completed',
                'payment_date' => now(),
            ]);

        if ($doctor && Schema::hasTable('users') && Schema::hasColumn('users', 'package')) {
            User::where(function ($query) use ($doctor) {
                $query->where('id', $doctor->id);

                if ($doctor->email) {
                    $query->orWhere('email', $doctor->email);
                }
            })->update(['package' => (string) $package->id]);
        }
    } finally {
        tenancy()->end();
    }
}
}
