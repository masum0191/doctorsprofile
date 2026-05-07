<?php

namespace App\Http\Controllers\Setting;
use App\Http\Controllers\Controller;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['doctor','patient'])
            ->latest('date')
            ->get();

        $doctors = User::where('role', 'admin')->get();
        $patients = User::where('role', 'patient')->get();

        return view('invoices.index', compact(
            'invoices','doctors','patients'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'purpose' => 'nullable|string|max:255',
        ]);

        Invoice::create($request->only([
            'doctor_id','patient_id','amount','date','purpose'
        ]));

        return back()->with('success', 'Invoice created successfully');
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'purpose' => 'nullable|string|max:255',
        ]);

        $invoice->update($request->only([
            'doctor_id','patient_id','amount','date','purpose'
        ]));

        return back()->with('success', 'Invoice updated successfully');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return back()->with('success', 'Invoice deleted successfully');
    }
    public function show(Invoice $invoice)
{
    $invoice->load(['doctor','patient']);
    return view('invoices.show', compact('invoice'));
}

}
