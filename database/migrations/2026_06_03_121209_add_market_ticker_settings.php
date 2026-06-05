<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            [
                'key' => 'market_ticker_enabled',
                'value' => '1',
                'group' => 'market',
                'type' => 'select',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'market_ticker_default_tab',
                'value' => 'markets',
                'group' => 'market',
                'type' => 'select',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'market_ticker_refresh_interval',
                'value' => '5',
                'group' => 'market',
                'type' => 'text',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'market_ticker_auto_refresh',
                'value' => '1',
                'group' => 'market',
                'type' => 'select',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'market_api_provider',
                'value' => 'yahoofinance',
                'group' => 'market',
                'type' => 'select',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'market_api_key',
                'value' => '',
                'group' => 'market',
                'type' => 'text',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'market_symbols_markets',
                'value' => '^SPX,^IXIC,^DJI,^FTSE,^HSI,^N225,DFMGI.DFM,TASI.SR,MSCI',
                'group' => 'market',
                'type' => 'textarea',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'market_symbols_forex',
                'value' => 'EURUSD,GBPUSD,USDJPY,USDEUR,EURGBP,USDAED,USDSAR,USDQAR',
                'group' => 'market',
                'type' => 'textarea',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'market_symbols_commodities',
                'value' => 'GC=F,SI=F,PL=F,PA=F,CL=F,BZ=F,NG=F,HG=F',
                'group' => 'market',
                'type' => 'textarea',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'market_symbols_crypto',
                'value' => 'BTCUSD,ETHUSD,USDTUSD,BNBUSD,SOLUSD,XRPUSD,DOGEUSD,USDCUSD',
                'group' => 'market',
                'type' => 'textarea',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(['key' => $setting['key']], $setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('group', 'market')->delete();
    }
};
