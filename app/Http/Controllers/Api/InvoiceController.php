<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // 📌 List invoices
    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);

        $invoices = Invoice::with('patient')
            ->where('doctor_id', $authUser->id)
            ->latest()
            ->paginate(20);

        return response()->json($invoices);
                    tenancy()->end();

    }

    // 📌 Create invoice
    public function store(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);

        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'purpose' => 'required|string|max:255',
        ]);

        $invoice = Invoice::create([
            'doctor_id' => $authUser->id,
            'patient_id' => $request->patient_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'purpose' => $request->purpose,
        ]);

        return response()->json([
            'message' => 'Invoice created successfully',
            'data' => $invoice
        ], 201);
                    tenancy()->end();

    }

    // 📌 Show invoice
    public function show($id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);

        $invoice = Invoice::where('doctor_id',$authUser->id)
            ->with('patient')
            ->where('id',$id)
            ->first();

        if (!$invoice) {
            tenancy()->end();
            return response()->json(['message'=>'Invoice not found'],404);
        }

        return response()->json($invoice);
                    tenancy()->end();

    }

    // 📌 Update invoice
    public function update(Request $request, $id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);

        $invoice = Invoice::where('doctor_id',$authUser->id)
            ->where('id',$id)
            ->first();

        if (!$invoice) {
            tenancy()->end();
            return response()->json(['message'=>'Invoice not found'],404);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'purpose' => 'required|string|max:255',
        ]);

        $invoice->update($request->only('amount','date','purpose'));

        return response()->json([
            'message'=>'Invoice updated successfully',
            'data'=>$invoice
        ]);
                    tenancy()->end();

    }

    // 📌 Delete invoice
    public function destroy($id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);

        $invoice = Invoice::where('doctor_id',$authUser->id)
            ->where('id',$id)
            ->first();

        if (!$invoice) {
            tenancy()->end();
            return response()->json(['message'=>'Invoice not found'],404);
        }

        $invoice->delete();

        return response()->json(['message'=>'Invoice deleted successfully']);
                    tenancy()->end();

    }
}
