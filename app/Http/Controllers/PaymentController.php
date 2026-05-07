<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function paymentSuccess(Request $request)
    {
        try {
            $session = session('payment_session');

            if (!$session) {
                return redirect()->route('home')->with('error', 'Invalid payment session');
            }

            $provider = new \Srmklive\PayPal\Services\PayPal(config('paypal'));
            $provider->getAccessToken();

            $order = $provider->capturePaymentOrder($request->token);

            if ($order['status'] === 'COMPLETED') {
                DB::beginTransaction();

                // Update payment status
                $payment = Payment::where('tenant_id', $session['tenant_id'])
                    ->where('status', 'pending')
                    ->firstOrFail();

                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $order['id'],
                    'payment_date' => now()
                ]);

                // Activate tenant and user
                $tenant = Tenant::findOrFail($session['tenant_id']);
                $tenant->update(['status' => 1]);

                $user = User::findOrFail($session['user_id']);
                $user->update(['status' => 1]);

                // Activate domain
                $tenant->domains()->update(['status' => 1]);

                // Update coupon usage
                if ($session['coupon_id']) {
                    $coupon = \App\Models\Coupon::find($session['coupon_id']);
                    if ($coupon) {
                        $coupon->increment('used_count');

                        \App\Models\CouponUsage::create([
                            'coupon_id' => $coupon->id,
                            'user_id' => $user->id,
                            'tenant_id' => $tenant->id,
                            'amount' => $payment->discount_amount
                        ]);
                    }
                }

                DB::commit();

                // Clear session
                session()->forget('payment_session');

                // Login user
                auth()->login($user);

                return redirect()->route('doctor.dashboard')
                    ->with('success', 'Payment successful! Your account is now active.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment success error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Payment processing failed.');
        }
    }

    public function paymentCancel(Request $request)
    {
        session()->forget('payment_session');
        return redirect()->route('registration.form')
            ->with('warning', 'Payment was cancelled. You can try again.');
    }

    public function sslcommerzSuccess(Request $request)
    {
        // Handle SSLCommerz success response
        return $this->handleSSLCommerzResponse($request, 'success');
    }

    public function sslcommerzFail(Request $request)
    {
        return $this->handleSSLCommerzResponse($request, 'failed');
    }

    public function sslcommerzCancel(Request $request)
    {
        return $this->handleSSLCommerzResponse($request, 'cancelled');
    }

    private function handleSSLCommerzResponse(Request $request, $status)
    {
        $tran_id = $request->tran_id;
        $session = session('sslcommerz_payment');

        if ($session && $session['tran_id'] === $tran_id) {
            if ($status === 'success') {
                // Verify payment with SSLCommerz
                $sslc = new \App\Services\SSLCommerzService();
                $validation = $sslc->validatePayment($request->val_id ?? $tran_id);

                if ($validation && isset($validation['valid']) && $validation['valid']) {
                    // Update payment and activate account
                    $this->processSuccessfulPayment($session, $validation['data']);
                } else {
                    Log::warning('SSLCommerz payment validation failed', [
                        'tran_id' => $tran_id,
                        'validation_result' => $validation
                    ]);
                    $status = 'failed';
                }
            }

            session()->forget('sslcommerz_payment');
        }

        if ($status === 'success') {
            return redirect()->route('admin.login')
                ->with('success', 'Payment successful! Your account has been activated.');
        } else {
            return redirect()->route('doctor.create')
                ->with('error', 'Payment failed. Please try again.');
        }
    }

    private function processSuccessfulPayment($session, $paymentData)
    {
        try {
            // Find the payment record
            $payment = \App\Models\Payment::where('user_id', $session['user_id'])
                ->where('status', 'pending')
                ->first();

            if ($payment) {
                // Update payment status
                $payment->update([
                    'status' => 'completed',
                    'payment_date' => now(),
                    'transaction_id' => $paymentData['tran_id'] ?? $session['tran_id']
                ]);

                // Find and activate tenant
                $tenant = \App\Models\Tenant::find($session['tenant_id']);
                if ($tenant) {
                    $tenant->update(['status' => 1]);

                    // Find and activate domain
                    $domain = $tenant->domains()->first();
                    if ($domain) {
                        $domain->update(['status' => 1]);
                    }

                    // Find and activate user
                    $user = \App\Models\User::find($session['user_id']);
                    if ($user) {
                        $user->update(['status' => 1]);
                    }

                    // Initialize tenancy and activate tenant user
                    tenancy()->initialize($tenant);
                    $tenantUser = \App\Models\User::where('email', $user->email)->first();
                    if ($tenantUser) {
                        $tenantUser->update(['status' => 1]);
                    }
                    tenancy()->end();

                    Log::info('Payment processed successfully', [
                        'tenant_id' => $tenant->id,
                        'user_id' => $user->id,
                        'amount' => $session['amount']
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to process successful payment', [
                'error' => $e->getMessage(),
                'session' => $session
            ]);
        }
    }

    // payment/ssl/success
       public function sslSuccess(Request $request)
{
    $referenceNo = $request->tran_id; // assuming you used appointment reference as tran_id

    // 1️⃣ Find Company Income in landlord DB
    $companyIncome = CompanyIncome::on('mysql')
        ->where('reference_no', $referenceNo)
        ->first();

    if (!$companyIncome) {
        return redirect()->route('ppayment.ssl.fail');
    }

    // 2️⃣ Find landlord doctor
    $doctor = User::on('mysql')->find($companyIncome->doctor_id);

    if (!$doctor) {
        return redirect()->route('payment.ssl.fail');
    }

    // 3️⃣ Initialize tenant
    $tenant = Tenant::find($doctor->tenant_id);
    tenancy()->initialize($tenant);

    // 4️⃣ Update Appointment (tenant DB)
    $appointment = Appointment::find($companyIncome->appointment_id);

    if ($appointment) {
        $appointment->update([
            'payment_status' => 'paid',
            'status'         => 'confirmed'
        ]);
    }

    tenancy()->end();

    // 5️⃣ Update Company Income (landlord DB)
    $companyIncome->update([
        'payment_status' => 'paid'
    ]);

     return redirect()->route('payment.ssl.success');
   // return
}

    // /payment/ssl/cancel
        public function sslCancel(Request $request)
        {

           $referenceNo = $request->tran_id;

    CompanyIncome::on('mysql')
        ->where('reference_no', $referenceNo)
        ->update([
            'payment_status' => 'failed'
        ]);

    return redirect()->route('payment.ssl.cancel');

        }
    // /payment/ssl/fail
        public function sslFail(Request $request)
        {
              $referenceNo = $request->tran_id;

    CompanyIncome::on('mysql')
        ->where('reference_no', $referenceNo)
        ->update([
            'payment_status' => 'failed'
        ]);

    return redirect()->route('payment.failed');
        }

}
