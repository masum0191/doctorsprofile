<?php

namespace Tests\Unit;

use App\Services\RegistrationPaymentGatewayResolver;
use Tests\TestCase;

class RegistrationPaymentGatewayResolverTest extends TestCase
{
    private RegistrationPaymentGatewayResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.mysql' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'services.stripe.key' => 'pk_test_config',
            'services.stripe.secret' => 'sk_test_config',
            'sslcommerz.apiCredentials.store_id' => 'store_config',
            'sslcommerz.apiCredentials.store_password' => 'password_config',
        ]);

        $this->resolver = new RegistrationPaymentGatewayResolver();
    }

    public function test_bangladesh_country_aliases_use_sslcommerz(): void
    {
        $this->assertTrue($this->resolver->isBangladesh(' Bangladesh '));
        $this->assertTrue($this->resolver->isBangladesh('BD'));
        $this->assertTrue($this->resolver->isBangladesh('ban'));

        $this->assertSame('sslcommerz', $this->resolver->resolve('Bangladesh'));
        $this->assertSame('sslcommerz', $this->resolver->resolve('bd'));
    }

    public function test_non_bangladesh_country_defaults_to_stripe(): void
    {
        $this->assertFalse($this->resolver->isBangladesh('United States'));

        $this->assertSame('stripe', $this->resolver->resolve('US'));
        $this->assertSame('stripe', $this->resolver->resolve(null));
    }

    public function test_credentials_fall_back_to_config_values(): void
    {
        $this->assertSame('pk_test_config', $this->resolver->stripeKey());
        $this->assertSame('sk_test_config', $this->resolver->stripeSecret());
        $this->assertSame('store_config', $this->resolver->sslcommerzStoreId());
        $this->assertSame('password_config', $this->resolver->sslcommerzStorePassword());
    }

    public function test_sslcommerz_domain_uses_test_mode_flag(): void
    {
        config(['sslcommerz.test_mode' => false]);
        $_ENV['SSLCZ_TESTMODE'] = false;

        $this->assertSame('https://securepay.sslcommerz.com', $this->resolver->sslcommerzApiDomain());
    }
}
