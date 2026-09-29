<x-admin-layout>
    <x-slot:section-title>System</x-slot>
    <x-slot:page-title>Global Settings</x-slot>
    
    <x-slot:page-actions>
        <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
            @csrf
            <x-ui.button type="submit" variant="outline" size="sm">
                Clear Cache
            </x-ui.button>
        </form>
    </x-slot>

    <div x-data="{ activeTab: 'general' }" class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        {{-- Sidebar Tabs --}}
        <div class="lg:col-span-3">
            <nav class="space-y-1 bg-white border border-[#E5E5E5] p-2">
                @foreach($settings as $group => $groupSettings)
                    <button @click="activeTab = '{{ $group }}'" 
                            :class="activeTab === '{{ $group }}' ? 'bg-black text-white' : 'text-neutral-400 hover:text-black hover:bg-[#F8F8F8]'"
                            class="w-full text-left px-6 py-5 text-[10px] font-bold uppercase tracking-[0.2em] transition-all flex items-center justify-between group">
                        {{ strtoupper($group) === 'SEO' ? 'SEO & Crawl' : ucfirst($group) }}
                        <svg x-show="activeTab === '{{ $group }}'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- Settings Form --}}
        <div class="lg:col-span-9">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#E5E5E5] p-10">
                @csrf
                @method('PUT')

                @foreach($settings as $group => $groupSettings)
                    <div x-show="activeTab === '{{ $group }}'" x-cloak class="space-y-12">
                        <div class="border-b border-neutral-100 pb-8 mb-12">
                            <h3 class="text-2xl font-serif font-bold tracking-tight">{{ strtoupper($group) === 'SEO' ? 'SEO & Crawl Engine' : ucfirst($group) . ' Configuration' }}</h3>
                            <p class="text-xs text-neutral-400 uppercase tracking-widest mt-2">Manage your platform's {{ $group }} parameters and system defaults.</p>
                        </div>

                        @if($group === 'seo' && isset($seoWarnings))
                            <div class="mb-10 p-6 bg-[#FAFAFA] border border-[#E5E5E5]">
                                <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 mb-6">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-widest text-black flex items-center gap-2">
                                            <span class="inline-block w-2 h-2 rounded-full {{ count($seoWarnings) > 0 ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                                            SEO Health & Diagnostics
                                        </h4>
                                        <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-1">Automatic real-time scan of canonicals, sitemaps, robots, and crawl signals.</p>
                                    </div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 {{ count($seoWarnings) > 0 ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-emerald-100 text-emerald-900 border border-emerald-300' }}">
                                        {{ count($seoWarnings) > 0 ? count($seoWarnings) . ' Issue(s) Detected' : 'All Systems Optimal' }}
                                    </span>
                                </div>

                                @if(count($seoWarnings) > 0)
                                    <div class="space-y-3">
                                        @foreach($seoWarnings as $warn)
                                            <div class="flex items-start gap-4 p-4 bg-white border border-[#E5E5E5] text-xs">
                                                <span class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ ($warn['level'] ?? '') === 'critical' ? 'bg-red-100 text-red-800 border border-red-300' : (($warn['level'] ?? '') === 'warning' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-blue-100 text-blue-800 border border-blue-300') }}">
                                                    {{ $warn['type'] ?? ucfirst($warn['level'] ?? 'notice') }}
                                                </span>
                                                <p class="text-neutral-700 leading-relaxed font-sans">{{ $warn['message'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4 bg-white border border-emerald-200 text-xs text-emerald-800 flex items-center gap-3">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Canonical tags, sitemaps, robots directives, and domain protocols are configured correctly.</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="space-y-10">
                            @foreach($groupSettings as $setting)
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                                    <div class="md:col-span-1">
                                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-900">
                                            {{ str_replace('_', ' ', $setting->key) }}
                                        </label>
                                    </div>
                                    <div class="md:col-span-3">
                                        @if($setting->type === 'text')
                                            <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                                            @if($setting->key === 'site_logo_alt')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SEO ALT TEXT: Crucial for search engine image indexing and screen accessibility.</p>
                                            @elseif($setting->key === 'site_footer_logo_alt')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SEO ALT TEXT: Crucial for search engine image indexing and screen accessibility.</p>
                                            @elseif($setting->key === 'seo_canonical_base')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Canonical Base: Master domain for all canonical tags and sitemaps (https://bizscoopmena.com).</p>
                                            @elseif($setting->key === 'seo_robots_login')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Robots Directive for /login: Keeps portal out of Google index while allowing links to be followed.</p>
                                            @elseif($setting->key === 'seo_robots_register')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Robots Directive for /register: Prevents registration page from indexing.</p>
                                            @elseif($setting->key === 'seo_robots_dashboard')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Robots Directive for User Dashboard.</p>
                                            @elseif($setting->key === 'seo_robots_search')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Robots Directive for Search Results: Prevents search pagination crawl bloat.</p>
                                            @elseif($setting->key === 'seo_robots_default')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Default Robots: Directive for public articles and sections (usually 'index,follow').</p>
                                            @elseif($setting->key === 'seo_related_articles_count')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Number of related article links displayed per story (strengthens crawl signals).</p>
                                            @elseif($setting->key === 'seo_min_articles_for_section')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Threshold of published articles before a section is flagged as thin content.</p>
                                            @elseif($setting->key === 'mail_mailer')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Mailer Driver: The transport driver for outgoing emails (usually 'smtp').</p>
                                            @elseif($setting->key === 'mail_host')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SMTP Host: The server host address of your email provider.</p>
                                            @elseif($setting->key === 'mail_port')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SMTP Port: Connection port (e.g. 587 for TLS, 465 for SSL, or 2525).</p>
                                            @elseif($setting->key === 'mail_username')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SMTP Username: The account credential used to authenticate connection.</p>
                                            @elseif($setting->key === 'mail_encryption')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SMTP Encryption: Connection protocol (e.g. 'tls', 'ssl' or leave blank).</p>
                                            @elseif($setting->key === 'mail_from_address')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Mail From Address: Outgoing sender email address (e.g. hello@bizscoop.com).</p>
                                            @elseif($setting->key === 'mail_from_name')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Mail From Name: Display name that appears on received emails (e.g. Bizscoop).</p>
                                            @elseif($setting->key === 'market_ticker_refresh_interval')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Refresh Interval (Minutes): How often the background scheduler updates live rates from the selected API.</p>
                                            @elseif($setting->key === 'market_api_key')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">API Access Key: Required for premium providers (Twelve Data, Alpha Vantage, FMP). Leave blank for Yahoo Finance or Mock data.</p>
                                            @endif
                                        @elseif($setting->type === 'boolean')
                                            <select name="{{ $setting->key }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                                                <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Enabled (ON)</option>
                                                <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Disabled (OFF)</option>
                                            </select>
                                            @if($setting->key === 'seo_force_https')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Force HTTPS: Enforces SSL redirection on bizscoopmena.com.</p>
                                            @elseif($setting->key === 'seo_force_non_www')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Force Non-WWW: Redirects www.bizscoopmena.com to bizscoopmena.com.</p>
                                            @elseif($setting->key === 'seo_remove_trailing_slash')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Trailing Slash Removal: 301 redirects /path/ to /path (excluding root homepage).</p>
                                            @elseif($setting->key === 'seo_canonical_tags_enabled')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Enable Canonical Tags: Emits self-referencing canonical &lt;link&gt; tags on all pages.</p>
                                            @elseif($setting->key === 'seo_sitemap_enabled')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Enable XML Sitemap: Exposes /sitemap.xml for search engines.</p>
                                            @elseif($setting->key === 'seo_sitemap_articles')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Include published articles in /sitemap.xml.</p>
                                            @elseif($setting->key === 'seo_sitemap_sections')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Include active sections in /sitemap.xml.</p>
                                            @elseif($setting->key === 'seo_sitemap_static_pages')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Include static pages (about, contact, etc.) in /sitemap.xml.</p>
                                            @elseif($setting->key === 'seo_related_articles_enabled')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Enable automatic internal article linking to enhance Google crawl discovery.</p>
                                            @endif
                                        @elseif($setting->type === 'password')
                                            <input type="password" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                                            @if($setting->key === 'mail_password')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SMTP Password: The password or secure token key corresponding to the username.</p>
                                            @endif
                                        @elseif($setting->type === 'select')
                                            <select name="{{ $setting->key }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                                                @if($setting->key === 'market_ticker_enabled' || $setting->key === 'market_ticker_auto_refresh')
                                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Enabled</option>
                                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Disabled</option>
                                                @elseif($setting->key === 'market_ticker_default_tab')
                                                    <option value="markets" {{ $setting->value == 'markets' ? 'selected' : '' }}>Markets</option>
                                                    <option value="forex" {{ $setting->value == 'forex' ? 'selected' : '' }}>Forex</option>
                                                    <option value="commodities" {{ $setting->value == 'commodities' ? 'selected' : '' }}>Commodities</option>
                                                    <option value="crypto" {{ $setting->value == 'crypto' ? 'selected' : '' }}>Crypto</option>
                                                @elseif($setting->key === 'market_api_provider')
                                                    <option value="yahoofinance" {{ $setting->value == 'yahoofinance' ? 'selected' : '' }}>Yahoo Finance (Free/Public compatible)</option>
                                                    <option value="mock" {{ $setting->value == 'mock' ? 'selected' : '' }}>Mock Data (Failsafe/Local)</option>
                                                    <option value="twelvedata" {{ $setting->value == 'twelvedata' ? 'selected' : '' }}>Twelve Data API</option>
                                                    <option value="alphavantage" {{ $setting->value == 'alphavantage' ? 'selected' : '' }}>Alpha Vantage API</option>
                                                    <option value="fmp" {{ $setting->value == 'fmp' ? 'selected' : '' }}>Financial Modeling Prep API</option>
                                                @endif
                                            </select>
                                            @if($setting->key === 'market_ticker_enabled')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Enable/Disable the entire market ticker section from the website header.</p>
                                            @elseif($setting->key === 'market_ticker_auto_refresh')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Toggle if the background task should automatically update quotes via API.</p>
                                            @elseif($setting->key === 'market_api_provider')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Select where the system loads financial quotes from. Yahoo Finance is free & does not require an API key.</p>
                                            @endif
                                        @elseif($setting->type === 'textarea')
                                            <textarea name="{{ $setting->key }}" rows="5" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all font-mono text-xs">{{ $setting->value }}</textarea>
                                            @if($setting->key === 'market_symbols_markets')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Markets Symbols: Comma-separated symbols for index quotes (e.g. ^SPX,^IXIC,^DJI,DFMGI.DFM).</p>
                                            @elseif($setting->key === 'market_symbols_forex')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Forex Symbols: Comma-separated currency pairs (e.g. EURUSD,GBPUSD,USDJPY).</p>
                                            @elseif($setting->key === 'market_symbols_commodities')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Commodity Symbols: Comma-separated codes (e.g. GC=F,SI=F,CL=F for Gold, Silver, Crude Oil).</p>
                                            @elseif($setting->key === 'market_symbols_crypto')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Crypto Symbols: Comma-separated cryptocurrency tickers (e.g. BTCUSD,ETHUSD,SOLUSD).</p>
                                            @endif
                                        @elseif($setting->type === 'image')
                                            <div class="flex items-start space-x-8">
                                                @if($setting->value)
                                                    <div class="relative w-32 h-16 bg-[#111111] border border-[#E5E5E5] p-2 shadow-sm flex items-center justify-center">
                                                        <img src="{{ Storage::url($setting->value) }}" class="max-w-full max-h-full object-contain">
                                                        <button type="button" 
                                                                onclick="if(confirm('Are you sure you want to remove this logo image?')) { document.getElementById('delete-setting-{{ $setting->key }}').submit(); }"
                                                                class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold shadow-md transition-all cursor-pointer"
                                                                title="Remove image">
                                                            ✕
                                                        </button>
                                                    </div>
                                                @endif
                                                <div class="flex-grow">
                                                    <input type="file" name="{{ $setting->key }}" class="text-xs text-neutral-500 file:mr-6 file:py-3 file:px-6 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white hover:file:bg-neutral-800 transition-all cursor-pointer">
                                                    <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">
                                                        @if($setting->key === 'site_logo')
                                                            Recommended size: 250x60 px (max height 80px). Transparent PNG or WebP format.
                                                        @elseif($setting->key === 'site_footer_logo')
                                                            Recommended size: 250x60 px. Transparent PNG/WebP (Optimized for dark background).
                                                        @elseif($setting->key === 'site_favicon')
                                                            Recommended size: 32x32 px or 16x16 px. ICO or PNG format.
                                                        @else
                                                            Recommended: WebP or PNG format.
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="mt-12 pt-8 border-t">
                    <x-ui.button type="submit" variant="primary" class="w-full">
                        Save Changes
                    </x-ui.button>
                </div>
            </form>

            {{-- Delete Forms --}}
            @foreach($settings as $group => $groupSettings)
                @foreach($groupSettings as $setting)
                    @if($setting->type === 'image' && $setting->value)
                        <form id="delete-setting-{{ $setting->key }}" action="{{ route('admin.settings.remove', $setting->key) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
</x-admin-layout>
