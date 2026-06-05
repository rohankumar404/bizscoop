<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use App\Services\MarketDataService;

class MarketCacheService
{
    protected $dataService;

    public function __construct(MarketDataService $dataService)
    {
        $this->dataService = $dataService;
    }

    /**
     * Fetch market quotes for a tab, either from cache or directly (forcing refresh).
     */
    public function getTabQuotes(string $tab, bool $forceRefresh = false): array
    {
        $cacheKey = "market_ticker_data_{$tab}";

        if ($forceRefresh) {
            return $this->refreshTabCache($tab);
        }

        return Cache::remember($cacheKey, $this->getCacheTTL(), function () use ($tab) {
            return $this->refreshTabCache($tab);
        });
    }

    /**
     * Query API and refresh the cache data for a selected tab.
     */
    public function refreshTabCache(string $tab): array
    {
        $symbolsString = Setting::get("market_symbols_{$tab}", '');

        if (empty($symbolsString)) {
            $symbolsString = $this->getDefaultSymbolsForTab($tab);
        }

        $symbols = array_filter(array_map('trim', explode(',', $symbolsString)));

        try {
            $quotes = $this->dataService->fetchQuotes($symbols, $tab);

            // Infinite Cache Backup (Failsafe fallback in case of future API drops)
            Cache::forever("market_ticker_failsafe_{$tab}", $quotes);
            Cache::forever("market_ticker_last_updated_{$tab}", now()->toDateTimeString());

            return $quotes;
        } catch (\Exception $e) {
            Log::warning("MarketCacheService: Error refreshing {$tab} rates. Loading failsafe fallback. Error: " . $e->getMessage());

            // Pull the latest known cached quotes from local storage
            $fallback = Cache::get("market_ticker_failsafe_{$tab}");
            if ($fallback) {
                return $fallback;
            }

            // If no failsafe exists, generate Mock data so that the frontend layout never crashes
            try {
                return $this->dataService->fetchQuotes($symbols, 'mock');
            } catch (\Exception $mockEx) {
                return [];
            }
        }
    }

    /**
     * Refresh all tabs in a single operation.
     */
    public function refreshAllTabs(): void
    {
        foreach (['markets', 'forex', 'commodities', 'crypto'] as $tab) {
            $this->refreshTabCache($tab);
        }
    }

    /**
     * Fetch the Dynamic Cache TTL based on the settings panel interval
     */
    protected function getCacheTTL()
    {
        $interval = (int) Setting::get('market_ticker_refresh_interval', 5);
        // Minimum interval of 1 minute to prevent database lock or infinite loading loops
        return now()->addMinutes(max(1, $interval));
    }

    /**
     * Standard list of index symbols seeded if settings are empty
     */
    protected function getDefaultSymbolsForTab(string $tab): string
    {
        $defaults = [
            'markets' => '^SPX,^IXIC,^DJI,^FTSE,^HSI,^N225,DFMGI.DFM,TASI.SR,MSCI',
            'forex' => 'EURUSD,GBPUSD,USDJPY,USDEUR,EURGBP,USDAED,USDSAR,USDQAR',
            'commodities' => 'GC=F,SI=F,PL=F,PA=F,CL=F,BZ=F,NG=F,HG=F',
            'crypto' => 'BTCUSD,ETHUSD,USDTUSD,BNBUSD,SOLUSD,XRPUSD,DOGEUSD,USDCUSD',
        ];

        return $defaults[$tab] ?? '';
    }
}
