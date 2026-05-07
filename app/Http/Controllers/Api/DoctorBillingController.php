<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorBillingController extends Controller
{
    // 📌 Billing list + stats
    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['message' => 'Tenant not resolved'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        tenancy()->initialize($tenant);

        // Base query: only this doctor’s appointments
        $query = Appointment::query();

        // ---- Filters ----
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('from')) {
            $query->whereDate('appointment_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('appointment_date', '<=', $request->to);
        }

        if ($request->filled('consultation_type')) {
            $query->where('consultation_type', $request->consultation_type);
        }

        // Clone for stats
        $statsQuery = clone $query;

        // ---- Stats ----
        $stats = $statsQuery
            ->selectRaw('
                COUNT(*) as total_appointments,
                COALESCE(SUM(amount),0) as total_amount,
                COALESCE(SUM(CASE WHEN payment_status = "paid" THEN amount ELSE 0 END),0) as paid_amount,
                COALESCE(SUM(CASE WHEN payment_status != "paid" OR payment_status IS NULL THEN amount ELSE 0 END),0) as unpaid_amount
            ')
            ->first();

        // ---- Paginated list ----
        $appointments = $query
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(25);


        return response()->json([
            'success' => true,
            'data' => $appointments,
            'stats' => $stats,
            'filters' => $request->only([
                'status', 'payment_status', 'from', 'to', 'consultation_type'
            ])
        ]);
        tenancy()->end();

    }

    // 📌 Billing report (summary)
    public function report(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['message' => 'Tenant not resolved'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        tenancy()->initialize($tenant);

        $from = $request->input('from');
        $to   = $request->input('to');

        // Base query
        $base = Appointment::forDoctor($authUser->id);

        if ($from) {
            $base->whereDate('appointment_date', '>=', $from);
        }

        if ($to) {
            $base->whereDate('appointment_date', '<=', $to);
        }

        // 1️⃣ Overall summary
        $overall = (clone $base)
            ->selectRaw('
                COUNT(*) as total_appointments,
                COALESCE(SUM(amount),0) as total_amount,
                COALESCE(SUM(CASE WHEN payment_status = "paid" THEN amount ELSE 0 END),0) as paid_amount,
                COALESCE(SUM(CASE WHEN payment_status != "paid" OR payment_status IS NULL THEN amount ELSE 0 END),0) as unpaid_amount
            ')
            ->first();

        // 2️⃣ Daily summary
        $daily = (clone $base)
            ->selectRaw('
                appointment_date,
                COUNT(*) as total_appointments,
                COALESCE(SUM(amount),0) as total_amount,
                COALESCE(SUM(CASE WHEN payment_status = "paid" THEN amount ELSE 0 END),0) as paid_amount,
                COALESCE(SUM(CASE WHEN payment_status != "paid" OR payment_status IS NULL THEN amount ELSE 0 END),0) as unpaid_amount
            ')
            ->groupBy('appointment_date')
            ->orderBy('appointment_date', 'asc')
            ->get();

        // 3️⃣ Status-wise summary
        $byStatus = (clone $base)
            ->selectRaw('
                status,
                COUNT(*) as total_appointments,
                COALESCE(SUM(amount),0) as total_amount
            ')
            ->groupBy('status')
            ->get();

        // 4️⃣ Payment-status-wise summary
        $byPaymentStatus = (clone $base)
            ->selectRaw('
                payment_status,
                COUNT(*) as total_appointments,
                COALESCE(SUM(amount),0) as total_amount
            ')
            ->groupBy('payment_status')
            ->get();


        return response()->json([
            'success' => true,
            'overall' => $overall,
            'daily' => $daily,
            'by_status' => $byStatus,
            'by_payment_status' => $byPaymentStatus,
            'from' => $from,
            'to' => $to,
        ]);
        tenancy()->end();
    }
}
