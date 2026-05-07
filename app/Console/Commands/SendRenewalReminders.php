<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;

class SendRenewalReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-renewal-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $reminderDates = [
        now()->addDays(7)->toDateString(),
        now()->addDay()->toDateString(),
    ];

    $subscriptions = Subscription::where('status','active')
        ->whereDate('ends_at', $reminderDates)
        ->with('doctor')
        ->get();

    foreach ($subscriptions as $sub) {

        Mail::to($sub->doctor->email)
            ->send(new \App\Mail\SubscriptionRenewalReminder($sub));

    }

    $this->info('Renewal reminders sent.');
}
}
