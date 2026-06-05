<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class MarketDataService
{
    /**
     * Fetch quotes for a given set of symbols.
     */
    public function fetchQuotes(array $symbols, string $tab): array
    {
        $provider = Setting::get('market_api_provider', 'yahoofinance');
        $apiKey = Setting::get('market_api_key', '');

        if ($provider === 'mock' || $tab === 'mock' || empty($symbols)) {
            return $this->getMockData($symbols, $tab);
        }

        try {
            switch ($provider) {
                case 'yahoofinance':
                    return $this->fetchYahooFinance($symbols);
                case 'twelvedata':
                    return $this->fetchTwelveData($symbols, $apiKey);
                case 'alphavantage':
                    return $this->fetchAlphaVantage($symbols, $apiKey);
                case 'fmp':
                    return $this->fetchFMP($symbols, $apiKey);
                default:
                    return $this->getMockData($symbols, $tab);
            }
        } catch (\Exception $e) {
            Log::error("MarketDataService: Failed to fetch quotes from '{$provider}'. Falling back. Error: " . $e->getMessage());
            // Re-throw so caching layer knows to use failsafe fallback
            throw $e;
        }
    }

    /**
     * Yahoo Finance API integration (Free & Public)
     */
    protected function fetchYahooFinance(array $symbols): array
    {
        // Auto-correct formatting for Forex and Crypto symbols to match Yahoo Finance API specs
        $yahooSymbolsMap = [];
        $querySymbols = [];

        foreach ($symbols as $sym) {
            $clean = trim($sym);
            if (empty($clean)) continue;

            $yahooSym = $clean;
            // Forex conversion: EURUSD -> EURUSD=X
            if (strlen($clean) === 6 && preg_match('/^[A-Z]{6}$/i', $clean)) {
                $yahooSym = $clean . '=X';
            }
            // Crypto conversion: BTCUSD -> BTC-USD
            elseif (preg_match('/^(BTC|ETH|USDT|BNB|SOL|XRP|DOGE|USDC)USD$/i', $clean, $matches)) {
                $yahooSym = $matches[1] . '-USD';
            }

            $yahooSymbolsMap[$yahooSym] = $clean;
            $querySymbols[] = $yahooSym;
        }

        if (empty($querySymbols)) {
            return [];
        }

        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'
        ])
        ->timeout(10)
        ->get("https://query1.finance.yahoo.com/v7/finance/quote", [
            'symbols' => implode(',', $querySymbols),
        ]);

        if (!$response->successful()) {
            throw new \Exception("Yahoo Finance API returned status " . $response->status());
        }

        $data = $response->json();
        $quotes = $data['quoteResponse']['result'] ?? [];

        $results = [];
        foreach ($quotes as $quote) {
            $yahooSym = $quote['symbol'] ?? '';
            $origSym = $yahooSymbolsMap[$yahooSym] ?? $yahooSym;

            $price = (float) ($quote['regularMarketPrice'] ?? 0);
            $change = (float) ($quote['regularMarketChange'] ?? 0);
            $percentChange = (float) ($quote['regularMarketChangePercent'] ?? 0);

            $results[] = [
                'symbol' => $origSym,
                'name' => $this->getCleanName($origSym, $quote['shortName'] ?? $origSym),
                'price' => $this->formatPrice($price, $origSym),
                'change' => ($change >= 0 ? '+' : '') . $this->formatPrice($change, $origSym),
                'percent_change' => ($percentChange >= 0 ? '+' : '') . number_format($percentChange, 2) . '%',
                'is_gain' => $change >= 0,
            ];
        }

        return $results;
    }

    /**
     * Twelve Data API integration
     */
    protected function fetchTwelveData(array $symbols, string $apiKey): array
    {
        if (empty($apiKey)) {
            throw new \Exception("Twelve Data API Key is not configured.");
        }

        $response = Http::timeout(10)->get("https://api.twelvedata.com/quote", [
            'symbol' => implode(',', $symbols),
            'apikey' => $apiKey,
        ]);

        if (!$response->successful()) {
            throw new \Exception("Twelve Data API returned status " . $response->status());
        }

        $data = $response->json();
        
        // Single symbol call returns a flat object, normalize it to match batch format
        $normalizedData = $data;
        if (count($symbols) === 1) {
            $normalizedData = [$symbols[0] => $data];
        }

        $results = [];
        foreach ($symbols as $sym) {
            $quote = $normalizedData[$sym] ?? null;
            if ($quote && !isset($quote['code'])) {
                $price = (float) ($quote['close'] ?? 0);
                $change = (float) ($quote['change'] ?? 0);
                $percentChange = (float) ($quote['percent_change'] ?? 0);

                $results[] = [
                    'symbol' => $sym,
                    'name' => $this->getCleanName($sym, $quote['name'] ?? $sym),
                    'price' => $this->formatPrice($price, $sym),
                    'change' => ($change >= 0 ? '+' : '') . $this->formatPrice($change, $sym),
                    'percent_change' => ($percentChange >= 0 ? '+' : '') . number_format($percentChange, 2) . '%',
                    'is_gain' => $change >= 0,
                ];
            }
        }

        return $results;
    }

    /**
     * Alpha Vantage API integration
     */
    protected function fetchAlphaVantage(array $symbols, string $apiKey): array
    {
        if (empty($apiKey)) {
            throw new \Exception("Alpha Vantage API Key is not configured.");
        }

        $results = [];
        // Note: Alpha Vantage free tier is highly rate-limited (5 requests/min),
        // and requires fetching quotes one by one.
        foreach ($symbols as $sym) {
            $response = Http::timeout(10)->get("https://www.alphavantage.co/query", [
                'function' => 'GLOBAL_QUOTE',
                'symbol' => $sym,
                'apikey' => $apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $quote = $data['Global Quote'] ?? null;
                if ($quote && isset($quote['01. symbol'])) {
                    $price = (float) ($quote['05. price'] ?? 0);
                    $change = (float) ($quote['09. change'] ?? 0);
                    $percentChange = (float) str_replace('%', '', $quote['10. change percent'] ?? '0');

                    $results[] = [
                        'symbol' => $sym,
                        'name' => $this->getCleanName($sym, $sym),
                        'price' => $this->formatPrice($price, $sym),
                        'change' => ($change >= 0 ? '+' : '') . $this->formatPrice($change, $sym),
                        'percent_change' => ($percentChange >= 0 ? '+' : '') . number_format($percentChange, 2) . '%',
                        'is_gain' => $change >= 0,
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * Financial Modeling Prep (FMP) API integration
     */
    protected function fetchFMP(array $symbols, string $apiKey): array
    {
        if (empty($apiKey)) {
            throw new \Exception("Financial Modeling Prep API Key is not configured.");
        }

        $response = Http::timeout(10)->get("https://financialmodelingprep.com/api/v3/quote/" . implode(',', $symbols), [
            'apikey' => $apiKey,
        ]);

        if (!$response->successful()) {
            throw new \Exception("FMP API returned status " . $response->status());
        }

        $quotes = $response->json() ?? [];
        $quotesBySymbol = [];
        foreach ($quotes as $q) {
            if (isset($q['symbol'])) {
                $quotesBySymbol[$q['symbol']] = $q;
            }
        }

        $results = [];
        foreach ($symbols as $sym) {
            $quote = $quotesBySymbol[$sym] ?? null;
            if ($quote) {
                $price = (float) ($quote['price'] ?? 0);
                $change = (float) ($quote['change'] ?? 0);
                $percentChange = (float) ($quote['changesPercentage'] ?? 0);

                $results[] = [
                    'symbol' => $sym,
                    'name' => $this->getCleanName($sym, $quote['name'] ?? $sym),
                    'price' => $this->formatPrice($price, $sym),
                    'change' => ($change >= 0 ? '+' : '') . $this->formatPrice($change, $sym),
                    'percent_change' => ($percentChange >= 0 ? '+' : '') . number_format($percentChange, 2) . '%',
                    'is_gain' => $change >= 0,
                ];
            }
        }

        return $results;
    }

    /**
     * Simulated Real-time Mock Data Fallback
     */
    protected function getMockData(array $symbols, string $tab): array
    {
        $baseQuotes = [
            // Markets
            '^SPX' => ['name' => 'S&P 500', 'price' => 5250.40],
            '^IXIC' => ['name' => 'NASDAQ', 'price' => 16400.80],
            '^DJI' => ['name' => 'Dow Jones', 'price' => 39120.30],
            '^FTSE' => ['name' => 'FTSE 100', 'price' => 7930.50],
            '^HSI' => ['name' => 'Hang Seng', 'price' => 16540.20],
            '^N225' => ['name' => 'Nikkei 225', 'price' => 38920.10],
            'DFMGI.DFM' => ['name' => 'DFM General Index', 'price' => 4210.40],
            'TASI.SR' => ['name' => 'Tadawul All Share Index', 'price' => 12530.80],
            'MSCI' => ['name' => 'MSCI World', 'price' => 3420.50],

            // Forex
            'EURUSD' => ['name' => 'EUR/USD', 'price' => 1.0850],
            'GBPUSD' => ['name' => 'GBP/USD', 'price' => 1.2640],
            'USDJPY' => ['name' => 'USD/JPY', 'price' => 151.40],
            'USDEUR' => ['name' => 'USD/EUR', 'price' => 0.9210],
            'EURGBP' => ['name' => 'EUR/GBP', 'price' => 0.8580],
            'USDAED' => ['name' => 'USD/AED', 'price' => 3.6725],
            'USDSAR' => ['name' => 'USD/SAR', 'price' => 3.7500],
            'USDQAR' => ['name' => 'USD/QAR', 'price' => 3.6400],

            // Commodities
            'GC=F' => ['name' => 'Gold', 'price' => 2280.50],
            'SI=F' => ['name' => 'Silver', 'price' => 26.40],
            'PL=F' => ['name' => 'Platinum', 'price' => 930.20],
            'PA=F' => ['name' => 'Palladium', 'price' => 1010.50],
            'CL=F' => ['name' => 'Crude Oil', 'price' => 85.40],
            'BZ=F' => ['name' => 'Brent Oil', 'price' => 89.20],
            'NG=F' => ['name' => 'Natural Gas', 'price' => 1.85],
            'HG=F' => ['name' => 'Copper', 'price' => 4.25],

            // Crypto
            'BTCUSD' => ['name' => 'Bitcoin', 'price' => 67420.00],
            'ETHUSD' => ['name' => 'Ethereum', 'price' => 3480.50],
            'USDTUSD' => ['name' => 'Tether', 'price' => 1.00],
            'BNBUSD' => ['name' => 'BNB', 'price' => 580.40],
            'SOLUSD' => ['name' => 'Solana', 'price' => 184.20],
            'XRPUSD' => ['name' => 'XRP', 'price' => 0.5850],
            'DOGEUSD' => ['name' => 'Dogecoin', 'price' => 0.1850],
            'USDCUSD' => ['name' => 'USDC', 'price' => 1.00],
        ];

        $results = [];
        foreach ($symbols as $sym) {
            $symClean = trim($sym);
            if (empty($symClean)) continue;

            $base = $baseQuotes[$symClean] ?? ['name' => $symClean, 'price' => 100.00];
            
            // Seed based on minute of current hour so the numbers are relatively stable but update every minute
            $seed = crc32($symClean) + (int) date('i') + (int) date('H');
            mt_srand($seed);
            
            $percent = (mt_rand(-150, 200) / 100); // ranges from -1.50% to +2.00%
            $price = $base['price'] * (1 + ($percent / 100));
            $change = $price - $base['price'];

            $results[] = [
                'symbol' => $symClean,
                'name' => $base['name'],
                'price' => $this->formatPrice($price, $symClean),
                'change' => ($change >= 0 ? '+' : '') . $this->formatPrice($change, $symClean),
                'percent_change' => ($percent >= 0 ? '+' : '') . number_format($percent, 2) . '%',
                'is_gain' => $percent >= 0,
            ];
        }

        mt_srand(); // Reset rand

        return $results;
    }

    /**
     * Map complex stock symbols to beautiful names (CNBC/Bloomberg style)
     */
    protected function getCleanName(string $symbol, string $defaultName): string
    {
        $symbolNames = [
            '^SPX' => 'S&P 500',
            '^IXIC' => 'NASDAQ',
            '^DJI' => 'Dow Jones',
            '^FTSE' => 'FTSE 100',
            '^HSI' => 'Hang Seng Index',
            '^N225' => 'Nikkei 225',
            'DFMGI.DFM' => 'DFM General Index',
            'TASI.SR' => 'Tadawul Index',
            'MSCI' => 'MSCI World',
            'EURUSD' => 'EUR/USD',
            'GBPUSD' => 'GBP/USD',
            'USDJPY' => 'USD/JPY',
            'USDEUR' => 'USD/EUR',
            'EURGBP' => 'EUR/GBP',
            'USDAED' => 'USD/AED',
            'USDSAR' => 'USD/SAR',
            'USDQAR' => 'USD/QAR',
            'GC=F' => 'Gold',
            'SI=F' => 'Silver',
            'PL=F' => 'Platinum',
            'PA=F' => 'Palladium',
            'CL=F' => 'Crude Oil',
            'BZ=F' => 'Brent Oil',
            'NG=F' => 'Natural Gas',
            'HG=F' => 'Copper',
            'BTCUSD' => 'Bitcoin',
            'ETHUSD' => 'Ethereum',
            'USDTUSD' => 'Tether',
            'BNBUSD' => 'BNB',
            'SOLUSD' => 'Solana',
            'XRPUSD' => 'XRP',
            'DOGEUSD' => 'Dogecoin',
            'USDCUSD' => 'USD Coin'
        ];

        return $symbolNames[$symbol] ?? $defaultName;
    }

    /**
     * Format rates appropriately by asset classification
     */
    protected function formatPrice(float $value, string $symbol): string
    {
        // Forex pairs are usually detailed to 4 decimals
        if (preg_match('/^[A-Z]{6}$/i', $symbol) || str_ends_with($symbol, '=X')) {
            return number_format($value, 4);
        }
        
        // Commodities like Natural Gas or penny cryptos also have higher decimal precision
        if ($value < 10 && $value > 0) {
            return number_format($value, 3);
        }

        return number_format($value, 2);
    }
}
