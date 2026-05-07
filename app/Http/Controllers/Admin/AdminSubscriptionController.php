<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
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
    $subscription = Subscription::findOrFail($id);

    if ($subscription->status !== 'pending') {
        return back()->with('error','Already processed.');
    }

    $subscription->update([
        'status' => 'active',
        'starts_at' => now(),
        'ends_at' => $subscription->billing_cycle === 'yearly'
            ? now()->addYear()
            : now()->addMonth()
    ]);
    $doctor =User::where('id',$subscription->doctor_id)->first();
    $tenantId = $doctor->tenant_id ?? null;

    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);

    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
    $payment =Payment::where('package_id',$subscription->package_id)->first();
    $payment->status="completed";
    $payment->save();
    tenancy()->end();

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
}
