<?php

namespace Tests\Unit;

use App\Models\PaymentSession;
use Tests\TestCase;

class PaymentSessionTest extends TestCase
{
    public function test_payment_session_reports_expired_when_expiration_is_in_the_past(): void
    {
        $session = new PaymentSession(['expires_at' => now()->subMinute()]);

        $this->assertTrue($session->isExpired());
    }

    public function test_payment_session_is_not_expired_when_expiration_is_future_or_missing(): void
    {
        $this->assertFalse((new PaymentSession(['expires_at' => now()->addMinute()]))->isExpired());
        $this->assertFalse((new PaymentSession())->isExpired());
    }
}
