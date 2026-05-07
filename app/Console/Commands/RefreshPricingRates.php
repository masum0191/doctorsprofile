<?php

namespace App\Console\Commands;

use App\Services\PricingService;
use Illuminate\Console\Command;

class RefreshPricingRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pricing:refresh-live-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch live exchange rates from the pricing API and cache them.';

    /**
     * Execute the console command.
     */
    public function handle(PricingService $pricingService): int
    {
        $rates = $pricingService->refreshLiveRates();

        if (empty($rates)) {
            $this->error('No live rates were fetched from the API.');
            return self::FAILURE;
        }

        $this->info('Live exchange rates refreshed successfully:');

        foreach ($rates as $currency => $rate) {
            $this->line(sprintf('%s => %s', $currency, number_format($rate, 6)));
        }

        return self::SUCCESS;
    }
}
