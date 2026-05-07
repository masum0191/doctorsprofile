<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorBillingController extends Controller
{
    public function index(Request $request)
    {
        $doctor = $request->user(); // logged-in doctor user

        // Base query: only this doctor’s appointments
        $query = Appointment::forDoctor($doctor->id);

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

        // Clone for stats before pagination
        $statsQuery = clone $query;

        // ---- Stats / Report summary ----
        $stats = $statsQuery
            ->selectRaw('
                COUNT(*) as total_appointments,
                COALESCE(SUM(amount),0) as total_amount,
                COALESCE(SUM(CASE WHEN payment_status = "paid" THEN amount ELSE 0 END),0) as paid_amount,
                COALESCE(SUM(CASE WHEN payment_status != "paid" THEN amount ELSE 0 END),0) as unpaid_amount
            ')
            ->first();

        // ---- Paginated list ----
        $appointments = $query
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('doctor.billing.index', [
            'appointments' => $appointments,
            'stats'        => $stats,
            'filters'      => $request->only(['status', 'payment_status', 'from', 'to', 'consultation_type']),
        ]);
    }

    public function report(Request $request)
    {
        $doctor = $request->user();

        $from = $request->input('from');
        $to   = $request->input('to');

        // Base query
        $base = Appointment::forDoctor($doctor->id);

        if ($from) {
            $base->whereDate('appointment_date', '>=', $from);
        }

        if ($to) {
            $base->whereDate('appointment_date', '<=', $to);
        }

        // 1) Overall summary
        $overall = (clone $base)
            ->selectRaw('
                COUNT(*) as total_appointments,
                COALESCE(SUM(amount),0) as total_amount,
                COALESCE(SUM(CASE WHEN payment_status = "paid" THEN amount ELSE 0 END),0) as paid_amount,
                COALESCE(SUM(CASE WHEN payment_status != "paid" OR payment_status IS NULL THEN amount ELSE 0 END),0) as unpaid_amount
            ')
            ->first();

        // 2) Daily summary
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

        // 3) Status-wise summary
        $byStatus = (clone $base)
            ->selectRaw('
                status,
                COUNT(*) as total_appointments,
                COALESCE(SUM(amount),0) as total_amount
            ')
            ->groupBy('status')
            ->get();

        // 4) Payment-status-wise summary
        $byPaymentStatus = (clone $base)
            ->selectRaw('
                payment_status,
                COUNT(*) as total_appointments,
                COALESCE(SUM(amount),0) as total_amount
            ')
            ->groupBy('payment_status')
            ->get();

        return view('doctor.billing.report', [
            'overall'         => $overall,
            'daily'           => $daily,
            'byStatus'        => $byStatus,
            'byPaymentStatus' => $byPaymentStatus,
            'from'            => $from,
            'to'              => $to,
        ]);
    }
}
