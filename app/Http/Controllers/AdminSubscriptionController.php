<?php

namespace App\Http\Controllers\A;

use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    public function index(Request $request)
    {
    $query = Subscription::with(['package','doctor'])
        ->orderByDesc('id');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $subscriptions = $query->paginate(20);

    return view('admin.subscriptions.index', compact('subscriptions'));
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

    return back()->with('success','Subscription activated.');
}
public function cancel($id)
{
    $subscription = Subscription::findOrFail($id);

    $subscription->update(['status' => 'cancelled']);

    return back()->with('success','Subscription cancelled.');
}
}
