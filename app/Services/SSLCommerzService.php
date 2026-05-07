<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SSLCommerzService
{
    protected $storeId;
    protected $storePassword;
    protected $apiDomain;
    protected $isLocalhost;

    public function __construct()
    {
        $this->storeId = config('sslcommerz.apiCredentials.store_id');
        $this->storePassword = config('sslcommerz.apiCredentials.store_password');
        $this->apiDomain = config('sslcommerz.apiDomain');
        $this->isLocalhost = config('sslcommerz.connect_from_localhost', false);

        Log::info('SSLCommerz Service Initialized', [
            'store_id' => substr($this->storeId, 0, 4) . '...',
            'api_domain' => $this->apiDomain,
            'is_localhost' => $this->isLocalhost
        ]);
    }

    /**
     * Initiate SSLCommerz payment
     */
    public function initiatePayment(array $postData)
    {
        try {
            Log::info('Initiating SSLCommerz Payment', ['tran_id' => $postData['tran_id'] ?? 'unknown']);

            // Add required credentials
            $postData['store_id'] = $this->storeId;
            $postData['store_passwd'] = $this->storePassword;

            // Set default values
            $defaults = [
                'currency' => 'BDT',
                'success_url' => url(config('sslcommerz.success_url', '/success')),
                'fail_url' => url(config('sslcommerz.failed_url', '/fail')),
                'cancel_url' => url(config('sslcommerz.cancel_url', '/cancel')),
                'ipn_url' => url(config('sslcommerz.ipn_url', '/ipn')),
                'cus_name' => 'Customer',
                'cus_email' => 'customer@example.com',
                'cus_phone' => '01700000000',
                'cus_add1' => 'N/A',
                'cus_city' => 'N/A',
                'cus_country' => 'Bangladesh',
                'shipping_method' => 'NO',
                'product_name' => 'Product',
                'product_category' => 'General',
                'product_profile' => 'general',
                'emi_option' => '0',
                'multi_card_name' => '',
                'allowed_bin' => ''
            ];

            $postData = array_merge($defaults, $postData);

            // Validate required fields
            $requiredFields = ['store_id', 'store_passwd', 'total_amount', 'currency', 'tran_id'];
            foreach ($requiredFields as $field) {
                if (empty($postData[$field])) {
                    throw new \Exception("Required field missing: {$field}");
                }
            }

            // Log request data (without password)
            $logData = $postData;
            $logData['store_passwd'] = '[HIDDEN]';
            Log::info('SSLCommerz Request Data', $logData);

            // Make API call to SSLCommerz
            $apiUrl = $this->apiDomain . config('sslcommerz.apiUrl.make_payment');

            Log::info('Making SSLCommerz API call', ['url' => $apiUrl]);

            $response = Http::asForm()->timeout(30)->post($apiUrl, $postData);

            Log::info('SSLCommerz Response Status', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('SSLCommerz Response Data', $data);

                if (isset($data['status']) && $data['status'] == 'SUCCESS' && isset($data['GatewayPageURL'])) {
                    Log::info('Payment initiated successfully', [
                        'tran_id' => $postData['tran_id'],
                        'gateway_url' => $data['GatewayPageURL']
                    ]);

                    return $data['GatewayPageURL'];
                } else {
                    $errorMsg = $data['failedreason'] ?? $data['status'] ?? 'Unknown error';
                    Log::error('SSLCommerz initiation failed', [
                        'error' => $errorMsg,
                        'tran_id' => $postData['tran_id'],
                        'response' => $data
                    ]);
                    throw new \Exception("SSLCommerz payment initiation failed: {$errorMsg}");
                }
            } else {
                Log::error('SSLCommerz API call failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers()
                ]);
                throw new \Exception('SSLCommerz API call failed with status: ' . $response->status());
            }

        } catch (\Exception $e) {
            Log::error('SSLCommerzService::initiatePayment Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('SSLCommerz payment initiation failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate payment using validation API
     */
    public function validatePayment($valId)
    {
        try {
            Log::info('Validating SSLCommerz Payment', ['val_id' => $valId]);

            $apiUrl = $this->apiDomain . config('sslcommerz.apiUrl.order_validate');

            $params = [
                'val_id' => $valId,
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'format' => 'json',
                'v' => '1'
            ];

            Log::info('SSLCommerz Validation Request', [
                'url' => $apiUrl,
                'params' => ['val_id' => $valId, 'store_id' => substr($this->storeId, 0, 4) . '...']
            ]);

            $response = Http::get($apiUrl, $params);

            Log::info('SSLCommerz Validation Response Status', ['status' => $response->status()]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('SSLCommerz Validation Response Data', $data);

                if (isset($data['status']) && ($data['status'] == 'VALID' || $data['status'] == 'VALIDATED')) {
                    Log::info('Payment validated successfully', ['val_id' => $valId]);

                    return [
                        'valid' => true,
                        'data' => $data
                    ];
                } else {
                    Log::warning('Payment validation failed', [
                        'val_id' => $valId,
                        'status' => $data['status'] ?? 'unknown'
                    ]);
                }
            } else {
                Log::error('Validation API call failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            return [
                'valid' => false,
                'message' => 'Payment validation failed'
            ];

        } catch (\Exception $e) {
            Log::error('SSLCommerzService::validatePayment Exception', [
                'message' => $e->getMessage(),
                'val_id' => $valId
            ]);
            return [
                'valid' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check transaction status
     */
    public function checkTransactionStatus($tranId)
    {
        try {
            Log::info('Checking SSLCommerz Transaction Status', ['tran_id' => $tranId]);

            $apiUrl = $this->apiDomain . config('sslcommerz.apiUrl.transaction_status');

            $params = [
                'tran_id' => $tranId,
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'format' => 'json'
            ];

            $response = Http::get($apiUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Transaction Status Response', ['tran_id' => $tranId, 'data' => $data]);
                return $data;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('SSLCommerzService::checkTransactionStatus Exception', [
                'message' => $e->getMessage(),
                'tran_id' => $tranId
            ]);
            return null;
        }
    }

    /**
     * Initiate refund
     */
    public function initiateRefund($tranId, $refundAmount, $refundRemarks = '', $bankTranId = '')
    {
        try {
            Log::info('Initiating SSLCommerz Refund', [
                'tran_id' => $tranId,
                'refund_amount' => $refundAmount
            ]);

            $apiUrl = $this->apiDomain . config('sslcommerz.apiUrl.refund_payment');

            $params = [
                'tran_id' => $tranId,
                'refund_amount' => $refundAmount,
                'refund_remarks' => $refundRemarks,
                'bank_tran_id' => $bankTranId,
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'format' => 'json',
                'refe_id' => uniqid()
            ];

            $response = Http::get($apiUrl, $params);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['APIConnect']) && $data['APIConnect'] == 'DONE') {
                    Log::info('Refund initiated successfully', [
                        'tran_id' => $tranId,
                        'refund_amount' => $refundAmount
                    ]);

                    return [
                        'success' => true,
                        'data' => $data
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Refund initiation failed'
            ];

        } catch (\Exception $e) {
            Log::error('SSLCommerzService::initiateRefund Exception', [
                'message' => $e->getMessage(),
                'tran_id' => $tranId
            ]);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check refund status
     */
    public function checkRefundStatus($refundRefId)
    {
        try {
            Log::info('Checking SSLCommerz Refund Status', ['refund_ref_id' => $refundRefId]);

            $apiUrl = $this->apiDomain . config('sslcommerz.apiUrl.refund_status');

            $params = [
                'refund_ref_id' => $refundRefId,
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'format' => 'json'
            ];

            $response = Http::get($apiUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('SSLCommerzService::checkRefundStatus Exception', [
                'message' => $e->getMessage(),
                'refund_ref_id' => $refundRefId
            ]);
            return null;
        }
    }

    /**
     * Get payment session details
     */
    public function getSessionDetails($sessionKey)
    {
        try {
            Log::info('Getting SSLCommerz Session Details', ['session_key' => $sessionKey]);

            $apiUrl = $this->apiDomain . config('sslcommerz.apiUrl.transaction_status');

            $params = [
                'sessionkey' => $sessionKey,
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'format' => 'json'
            ];

            $response = Http::get($apiUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('SSLCommerzService::getSessionDetails Exception', [
                'message' => $e->getMessage(),
                'session_key' => $sessionKey
            ]);
            return null;
        }
    }

    /**
     * Helper: Generate unique transaction ID
     */
    public function generateTransactionId($prefix = 'DOCTOR')
    {
        return $prefix . '_' . date('YmdHis') . '_' . uniqid();
    }

    /**
     * Helper: Format amount for SSLCommerz
     */
    public function formatAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }

    /**
     * Helper: Create post data for doctor registration
     */
    public function createDoctorRegistrationData($user, $package, $amount, $tranId = null)
    {
        $tranId = $tranId ?? $this->generateTransactionId();

        return [
            'total_amount' => $this->formatAmount($amount),
            'tran_id' => $tranId,
            'cus_name' => $user->name ?? 'Doctor',
            'cus_email' => $user->email,
            'cus_phone' => $user->mobile ?? '01700000000',
            'cus_add1' => $user->address ?? 'N/A',
            'cus_city' => $user->city ?? 'N/A',
            'cus_country' => $user->country ?? 'Bangladesh',
            'product_name' => 'Doctor Registration - ' . ($package->name ?? 'Package'),
            'product_category' => 'Healthcare',
            'product_profile' => 'non-physical-goods',
            'shipping_method' => 'NO',
            'num_of_item' => '1',
            'product_amount' => $this->formatAmount($amount),
            'vat' => '0',
            'discount_amount' => '0',
            'convenience_fee' => '0'
        ];
    }
}
