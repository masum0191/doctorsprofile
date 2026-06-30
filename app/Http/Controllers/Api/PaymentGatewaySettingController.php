<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentGatewaySettingsService;
use Illuminate\Http\Request;

class PaymentGatewaySettingController extends Controller
{
    public function show(PaymentGatewaySettingsService $paymentGateways)
    {
        return response()->json([
            'success' => true,
            'data' => $paymentGateways->publicPayload(),
        ]);
    }

    public function update(Request $request, PaymentGatewaySettingsService $paymentGateways)
    {
        $payment = $paymentGateways->updateFromRequest($request);

        return response()->json([
            'success' => true,
            'message' => 'Payment gateway settings updated successfully.',
            'data' => $paymentGateways->publicPayload($payment),
        ]);
    }
}
