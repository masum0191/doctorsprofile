<?php
namespace App\Services;

use App\Models\Holding;
use App\Models\HoldingTransaction;
use Carbon\Carbon;

class HoldingPaymentReport
{
    public function generateReport($filters = [])
    {
        //dd($filters);

        $query = HoldingTransaction::orderBy('date', 'desc');

        // Apply filters
        if (!empty($filters['fiscal_year'])) {
            $query->where('fiscal_year', $filters['fiscal_year']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('date', '>=', Carbon::parse($filters['date_from']));
        }

        if (!empty($filters['date_to'])) {
            $query->where('date', '<=', Carbon::parse($filters['date_to']));
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        $transactions = $query->get();
        $holding = Holding::get();
        //dd($holding);
        // Calculate totals
        $totalPaid = $transactions->sum('quantity');

        // Group by fiscal year for chart
        $chartData = $transactions->groupBy('fiscal_year')
            ->map(function($items) {
                return $items->sum('quantity');
            });
$monthlyData = $transactions->groupBy(function($item) {
    return Carbon::parse($item->date)->format('Y-m');
})->map(function($items) {
    return $items->sum('quantity');
});

        return [
            'holding' => $holding,
            'total_paid' => $totalPaid,
            'transactions' => $transactions,
            'chart_data' => $chartData,
            'filters' => $filters,
            'monthly_data' => $monthlyData
];
    }
}
