<?php

namespace Tests\Feature;

use App\Services\SSLCommerzService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SSLCommerzServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.mysql' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => false,
            ],
            'sslcommerz.apiCredentials.store_id' => 'store_test',
            'sslcommerz.apiCredentials.store_password' => 'password_test',
            'sslcommerz.apiUrl.make_payment' => '/gwprocess/v4/api.php',
            'sslcommerz.apiUrl.order_validate' => '/validator/api/validationserverAPI.php',
        ]);
        DB::purge('mysql');
    }

    public function test_it_initiates_payment_and_returns_gateway_url(): void
    {
        Http::fake([
            'https://securepay.sslcommerz.com/gwprocess/v4/api.php' => Http::response([
                'status' => 'SUCCESS',
                'GatewayPageURL' => 'https://sslcommerz.test/pay',
            ]),
        ]);

        $url = (new SSLCommerzService())->initiatePayment([
            'total_amount' => '1200.00',
            'currency' => 'BDT',
            'tran_id' => 'TRAN123',
        ]);

        $this->assertSame('https://sslcommerz.test/pay', $url);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://securepay.sslcommerz.com/gwprocess/v4/api.php'
                && $request['store_id'] === 'store_test'
                && $request['store_passwd'] === 'password_test'
                && $request['tran_id'] === 'TRAN123';
        });
    }

    public function test_it_validates_successful_payment_response(): void
    {
        Http::fake([
            'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php*' => Http::response([
                'status' => 'VALID',
                'tran_id' => 'TRAN123',
            ]),
        ]);

        $result = (new SSLCommerzService())->validatePayment('VAL123');

        $this->assertTrue($result['valid']);
        $this->assertSame('VALID', $result['data']['status']);
    }

    public function test_it_returns_invalid_result_when_validation_fails(): void
    {
        Http::fake([
            'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php*' => Http::response([
                'status' => 'INVALID',
            ]),
        ]);

        $result = (new SSLCommerzService())->validatePayment('VAL123');

        $this->assertFalse($result['valid']);
        $this->assertSame('Payment validation failed', $result['message']);
    }

    public function test_it_formats_doctor_registration_payload(): void
    {
        $payload = (new SSLCommerzService())->createDoctorRegistrationData(
            (object) [
                'name' => 'Dr Example',
                'email' => 'doctor@example.com',
                'mobile' => '01700000000',
                'country' => 'Bangladesh',
            ],
            (object) ['name' => 'Standard'],
            1250,
            'TRAN999'
        );

        $this->assertSame('1250.00', $payload['total_amount']);
        $this->assertSame('TRAN999', $payload['tran_id']);
        $this->assertSame('Dr Example', $payload['cus_name']);
        $this->assertSame('Doctor Registration - Standard', $payload['product_name']);
    }
}
