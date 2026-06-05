<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MarketCacheService;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class RefreshMarketTicker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:refresh-ticker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the cached financial market ticker data from external APIs';

    /**
     * Execute the console command.
     */
    public function handle(MarketCacheService $cacheService)
    {
        $enabled = Setting::get('market_ticker_enabled', '1');
        $autoRefresh = Setting::get('market_ticker_auto_refresh', '1');

        if ($enabled !== '1' || $autoRefresh !== '1') {
            $this->info('Market ticker or auto refresh is disabled in settings. Skipping updates.');
            return Command::SUCCESS;
        }

        $this->info('Starting financial market ticker refresh...');

        try {
            $cacheService->refreshAllTabs();
            $this->info('Market ticker refresh completed successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to refresh market ticker rates: ' . $e->getMessage());
            Log::error('RefreshMarketTicker Command Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
