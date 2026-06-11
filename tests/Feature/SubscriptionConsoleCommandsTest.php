<?php

namespace Tests\Feature;

use App\Mail\SubscriptionRenewalReminder;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\InteractsWithCentralDatabase;
use Tests\TestCase;

class SubscriptionConsoleCommandsTest extends TestCase
{
    use InteractsWithCentralDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpCentralDatabase();
    }

    public function test_expire_subscriptions_downgrades_expired_active_subscription_to_free_package(): void
    {
        $freePackage = Package::create([
            'name' => 'Free',
            'slug' => 'free',
            'price_monthly' => 0,
            'price_yearly' => 0,
            'storage_gb' => 1,
        ]);
        $premiumPackage = Package::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'price_monthly' => 25,
            'price_yearly' => 250,
            'storage_gb' => 25,
        ]);
        DB::connection('mysql')->table('tenants')->insert([
            'id' => 'tenant-expired',
            'package_id' => $premiumPackage->id,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $doctor = User::create([
            'name' => 'Dr Expired',
            'email' => 'expired@example.com',
            'password' => 'secret',
            'tenant_id' => 'tenant-expired',
        ]);
        $subscription = Subscription::create([
            'doctor_id' => $doctor->id,
            'tenant_id' => 'tenant-expired',
            'package_id' => $premiumPackage->id,
            'billing_cycle' => 'monthly',
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->subDay(),
            'status' => 'active',
        ]);

        $this->artisan('app:expire-subscriptions')
            ->expectsOutput('Expired subscriptions processed successfully.')
            ->assertSuccessful();

        $this->assertSame('expired', $subscription->fresh()->status);
        $this->assertDatabaseHas('subscriptions', [
            'doctor_id' => $doctor->id,
            'tenant_id' => 'tenant-expired',
            'package_id' => $freePackage->id,
            'status' => 'active',
        ], 'mysql');
        $this->assertDatabaseHas('tenants', [
            'id' => 'tenant-expired',
            'package_id' => $freePackage->id,
            'status' => 1,
        ], 'mysql');
    }

    public function test_expire_subscriptions_fails_when_free_package_is_missing(): void
    {
        $this->artisan('app:expire-subscriptions')
            ->expectsOutput('Free package was not found. Expired subscriptions were not processed.')
            ->assertFailed();
    }

    public function test_send_renewal_reminders_sends_mail_for_one_and_seven_day_expirations(): void
    {
        Mail::fake();

        $package = Package::create([
            'name' => 'Standard',
            'slug' => 'standard-reminder',
            'price_monthly' => 10,
            'price_yearly' => 100,
            'storage_gb' => 5,
        ]);
        $oneDayDoctor = User::create([
            'name' => 'Dr One',
            'email' => 'one@example.com',
            'password' => 'secret',
        ]);
        $sevenDayDoctor = User::create([
            'name' => 'Dr Seven',
            'email' => 'seven@example.com',
            'password' => 'secret',
        ]);
        $laterDoctor = User::create([
            'name' => 'Dr Later',
            'email' => 'later@example.com',
            'password' => 'secret',
        ]);

        Subscription::create([
            'doctor_id' => $oneDayDoctor->id,
            'tenant_id' => 'tenant-one-day',
            'package_id' => $package->id,
            'billing_cycle' => 'monthly',
            'ends_at' => now()->addDay(),
            'status' => 'active',
        ]);
        Subscription::create([
            'doctor_id' => $sevenDayDoctor->id,
            'tenant_id' => 'tenant-seven-day',
            'package_id' => $package->id,
            'billing_cycle' => 'monthly',
            'ends_at' => now()->addDays(7),
            'status' => 'active',
        ]);
        Subscription::create([
            'doctor_id' => $laterDoctor->id,
            'tenant_id' => 'tenant-later',
            'package_id' => $package->id,
            'billing_cycle' => 'monthly',
            'ends_at' => now()->addDays(15),
            'status' => 'active',
        ]);

        $this->artisan('app:send-renewal-reminders')
            ->expectsOutput('Renewal reminders sent.')
            ->assertSuccessful();

        Mail::assertSent(SubscriptionRenewalReminder::class, 2);
        Mail::assertSent(SubscriptionRenewalReminder::class, function ($mail) use ($oneDayDoctor) {
            return $mail->hasTo($oneDayDoctor->email) && $mail->daysLeft === 1;
        });
        Mail::assertSent(SubscriptionRenewalReminder::class, function ($mail) use ($sevenDayDoctor) {
            return $mail->hasTo($sevenDayDoctor->email) && $mail->daysLeft === 7;
        });
        Mail::assertNotSent(SubscriptionRenewalReminder::class, function ($mail) use ($laterDoctor) {
            return $mail->hasTo($laterDoctor->email);
        });
    }
}
