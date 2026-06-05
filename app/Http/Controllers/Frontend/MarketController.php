<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\MarketCacheService;
use Illuminate\Http\Request;
use App\Models\Setting;

class MarketController extends Controller
{
    protected $cacheService;

    public function __construct(MarketCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Fetch market quotes data for a given tab.
     */
    public function getTickerData(Request $request)
    {
        $enabled = Setting::get('market_ticker_enabled', '1');
        
        if ($enabled !== '1') {
            return response()->json([
                'success' => false,
                'message' => 'Market ticker is disabled.',
                'data' => []
            ], 403);
        }

        $defaultTab = Setting::get('market_ticker_default_tab', 'markets');
        $tab = $request->query('tab', $defaultTab);

        if (!in_array($tab, ['markets', 'forex', 'commodities', 'crypto'])) {
            $tab = 'markets';
        }

        try {
            $quotes = $this->cacheService->getTabQuotes($tab);

            return response()->json([
                'success' => true,
                'tab' => $tab,
                'data' => $quotes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not fetch ticker data: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
