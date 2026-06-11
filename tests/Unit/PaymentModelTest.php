<?php

namespace Tests\Unit;

use App\Models\Payment;
use Tests\TestCase;

class PaymentModelTest extends TestCase
{
    public function test_payment_status_and_method_helpers_return_readable_values(): void
    {
        $payment = new Payment([
            'status' => 'completed',
            'payment_method' => 'credit_card',
        ]);

        $this->assertTrue($payment->isSuccessful());
        $this->assertSame('Completed', $payment->status_text);
        $this->assertSame('Credit/Debit Card', $payment->payment_method_text);

        $payment->status = 'pending_approval';
        $payment->payment_method = 'bank_transfer';

        $this->assertFalse($payment->isSuccessful());
        $this->assertSame('Pending_approval', $payment->status_text);
        $this->assertSame('Bank Transfer', $payment->payment_method_text);
    }
}
