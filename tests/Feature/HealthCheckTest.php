<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_check_route_returns_successful_response(): void
    {
        $this->get('/up')->assertOk();
    }
}
