<?php

namespace Tests\Feature;

use Tests\TestCase;

class ConsoleScheduleTest extends TestCase
{
    public function test_application_schedules_expected_console_commands(): void
    {
        $this->artisan('schedule:list')
            ->expectsOutputToContain('app:expire-subscriptions')
            ->expectsOutputToContain('app:send-renewal-reminders')
            ->expectsOutputToContain('pricing:refresh-live-rates')
            ->assertSuccessful();
    }
}
