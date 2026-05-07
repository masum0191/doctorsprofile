<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;




class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $existing = Payment::where('form_id', $request->form_id)
                  ->where('type', $request->type)
                  ->where('payment_status', 'paid')
                  ->first();

        if ($existing) {
            return redirect()->back()->with('warning', 'এই ফর্মের জন্য পেমেন্ট ইতোমধ্যে সম্পন্ন হয়েছে।');
        }
        $request->validate([
        'form_id' => 'required|numeric',
        'type' => 'required|string',
        'amount' => 'required|numeric|min:0',
        ]);
        $payment =new Payment;
        $payment->user_id=\Auth::user()->id;
        $payment->tran_id="TRI".time();

        $payment->form_id=$request->input('form_id');
        $payment->bill=$request->input('amount');
        $payment->type=$request->input('type');
        $payment->note=$request->input('type') .'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে';
        $payment->save();







        return redirect()->back()->with('success', 'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে।');
    }
}
