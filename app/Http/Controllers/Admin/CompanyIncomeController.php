<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyIncome;
use Illuminate\Http\Request;

class CompanyIncomeController extends Controller
{
    public function index(Request $request)
{
    $query = CompanyIncome::on('mysql')->with('doctor');

    // Filter by doctor
    if ($request->doctor_id) {
        $query->where('doctor_id', $request->doctor_id);
    }

    // Filter by status
    if ($request->payment_status) {
        $query->where('payment_status', $request->payment_status);
    }

    // Date filter
    if ($request->from && $request->to) {
        $query->whereBetween('created_at', [
            $request->from,
            $request->to
        ]);
    }

    $incomes = $query->latest()->paginate(20);

    // Summary
    $totalAmount = (clone $query)->sum('amount');
    $totalCompanyProfit = (clone $query)->sum('company_profit');

    return view('admin.company_incomes.index', compact(
        'incomes',
        'totalAmount',
        'totalCompanyProfit'
    ));
}
public function updateStatus(Request $request, $id)
{
    $income = CompanyIncome::on('mysql')->findOrFail($id);

    $request->validate([
        'payment_status' => 'required|in:pending,paid,failed'
    ]);

    $income->update([
        'payment_status' => $request->payment_status
    ]);

    return back()->with('success', 'Payment status updated successfully.');
}

}

