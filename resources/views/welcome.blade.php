<x-frontend-layout>
<div class="wrap" style="padding-top:14px;padding-bottom:28px;">
    <div class="flex gap-5">

        {{-- ═══════════════════════════════════════════
             MAIN CONTENT — 8 of 12 cols (~792px)
        ═══════════════════════════════════════════ --}}
        <div style="flex:1;min-width:0;">

            {{-- ─── HERO GRID ─────────────────────────── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px;margin-bottom:14px;">

                {{-- Left: Large featured --}}
                @if($hero = $latestPosts->shift())
                <div style="grid-column:1;grid-row:1/3;position:relative;">
                    <a href="{{ route('frontend.article.show', $hero->slug) }}" class="img-card" style="display:block;height:340px;">
                        @if($hero->hasMedia('featured_image'))
                            <img src="{{ $hero->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s;" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                        @else <div style="width:100%;height:100%;background:#ddd;"></div> @endif
                        <span class="img-cat">{{ $hero->category->getTranslation('name','en') }}</span>
                        <span class="img-flash">
                            <svg width="10" height="10" fill="#fff" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </span>
                        <div class="img-overlay"></div>
                        <div class="img-overlay-text">
                            <p class="post-meta" style="color:rgba(255,255,255,0.7);margin-bottom:5px;">{{ $hero->author->name }} · {{ $hero->published_at->format('d M Y') }}</p>
                            <h2 style="font-size:17px;font-weight:800;color:#fff;line-height:1.3;text-shadow:0 1px 3px rgba(0,0,0,0.5);" onmouseover="this.style.color='#ffcccc'" onmouseout="this.style.color='#fff'">
                                {{ $hero->translate()->title }}
                            </h2>
                        </div>
                    </a>
                </div>
                @endif

                {{-- Right top --}}
                @if($p1 = $latestPosts->shift())
                <div style="position:relative;">
                    <a href="{{ route('frontend.article.show', $p1->slug) }}" class="img-card" style="display:block;height:166px;">
                        @if($p1->hasMedia('featured_image'))
                            <img src="{{ $p1->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s;" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                        @else <div style="width:100%;height:100%;background:#bbb;"></div> @endif
                        <span class="img-cat">{{ $p1->category->getTranslation('name','en') }}</span>
                        <span class="img-flash"><svg width="8" height="8" fill="#fff" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
                        <div class="img-overlay"></div>
                        <div class="img-overlay-text" style="padding:8px;">
                            <p class="post-meta" style="color:rgba(255,255,255,0.7);margin-bottom:3px;">{{ $p1->author->name }} · {{ $p1->published_at->format('d M Y') }}</p>
                            <h3 style="font-size:13px;font-weight:700;color:#fff;line-height:1.3;">{{ $p1->translate()->title }}</h3>
                        </div>
                    </a>
                </div>
                @endif

                {{-- Right bottom --}}
                @if($p2 = $latestPosts->shift())
                <div style="position:relative;">
                    <a href="{{ route('frontend.article.show', $p2->slug) }}" class="img-card" style="display:block;height:166px;">
                        @if($p2->hasMedia('featured_image'))
                            <img src="{{ $p2->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s;" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                        @else <div style="width:100%;height:100%;background:#bbb;"></div> @endif
                        <span class="img-cat">{{ $p2->category->getTranslation('name','en') }}</span>
                        <div class="img-overlay"></div>
                        <div class="img-overlay-text" style="padding:8px;">
                            <p class="post-meta" style="color:rgba(255,255,255,0.7);margin-bottom:3px;">{{ $p2->author->name }} · {{ $p2->published_at->format('d M Y') }}</p>
                            <h3 style="font-size:13px;font-weight:700;color:#fff;line-height:1.3;">{{ $p2->translate()->title }}</h3>
                        </div>
                    </a>
                </div>
                @endif
            </div>

            {{-- ─── CATEGORY SECTIONS ─────────────────── --}}
            @foreach($headerCategories->take(5) as $catIdx => $category)
                @php
                    $cPosts = $category->posts()
                        ->where('status','published')
                        ->with(['translations','media','author','category'])
                        ->latest('published_at')
                        ->take(6)->get();
                    if($cPosts->isEmpty()) continue;
                    $featured = $cPosts->first();
                    $listPosts = $cPosts->slice(1,3);
                    $extraPosts = $cPosts->slice(4);
                @endphp

                <div class="content-box" style="margin-bottom:14px;">
                    {{-- Section header --}}
                    <div class="sec-head">
                        <h3 class="sec-title">{{ $category->getTranslation('name', app()->getLocale()) }}</h3>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <a href="{{ route('frontend.category.show', $category->slug) }}" class="more-link">More »</a>
                            <div class="nav-arrows"><span>‹</span><span>›</span></div>
                        </div>
                    </div>

                    @if($catIdx % 2 === 0)
                        {{-- Layout A: Featured left + list right --}}
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div>
                                @if($featured)
                                    <a href="{{ route('frontend.article.show', $featured->slug) }}" class="img-card" style="display:block;height:180px;margin-bottom:8px;">
                                        @if($featured->hasMedia('featured_image'))
                                            <img src="{{ $featured->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;">
                                        @else <div style="width:100%;height:100%;background:#ddd;"></div> @endif
                                        <span class="img-cat">{{ $featured->category->getTranslation('name','en') }}</span>
                                        <span class="img-flash"><svg width="8" height="8" fill="#fff" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
                                    </a>
                                    <p class="post-meta" style="margin-bottom:4px;">{{ $featured->author->name }} · {{ $featured->published_at->format('d M Y') }}</p>
                                    <a href="{{ route('frontend.article.show', $featured->slug) }}" class="post-title" style="display:block;font-size:14px;margin-bottom:5px;">{{ $featured->translate()->title }}</a>
                                    <p class="post-excerpt" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $featured->translate()->excerpt }}</p>
                                @endif
                            </div>
                            <div>
                                @foreach($listPosts as $lp)
                                    <div class="list-post">
                                        <a href="{{ route('frontend.article.show', $lp->slug) }}" class="list-post-img">
                                            @if($lp->hasMedia('featured_image'))
                                                <img src="{{ $lp->getFirstMediaUrl('featured_image') }}">
                                            @endif
                                        </a>
                                        <div>
                                            <p class="post-meta" style="margin-bottom:3px;">{{ $lp->published_at->format('d M Y') }}</p>
                                            <a href="{{ route('frontend.article.show', $lp->slug) }}" class="post-title" style="display:block;font-size:12px;">{{ $lp->translate()->title }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @else
                        {{-- Layout B: 3-column grid --}}
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                            @foreach($cPosts->take(3) as $gp)
                                <div>
                                    <a href="{{ route('frontend.article.show', $gp->slug) }}" class="img-card" style="display:block;height:130px;margin-bottom:7px;">
                                        @if($gp->hasMedia('featured_image'))
                                            <img src="{{ $gp->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;">
                                        @else <div style="width:100%;height:100%;background:#ddd;"></div> @endif
                                        <span class="img-cat" style="font-size:7px;padding:2px 4px;">{{ $gp->category->getTranslation('name','en') }}</span>
                                        <span class="img-flash"><svg width="7" height="7" fill="#fff" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
                                    </a>
                                    <p class="post-meta" style="margin-bottom:3px;">{{ $gp->author->name }} · {{ $gp->published_at->format('d M Y') }}</p>
                                    <a href="{{ route('frontend.article.show', $gp->slug) }}" class="post-title" style="font-size:12px;display:block;">{{ $gp->translate()->title }}</a>
                                </div>
                            @endforeach
                        </div>

                        @if($cPosts->count() > 3)
                        <div style="border-top:1px solid #eee;margin-top:10px;padding-top:10px;display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            @foreach($cPosts->slice(3) as $lp)
                                <div class="list-post" style="border-bottom:none;padding-bottom:0;margin-bottom:0;">
                                    <a href="{{ route('frontend.article.show', $lp->slug) }}" class="list-post-img">
                                        @if($lp->hasMedia('featured_image'))
                                            <img src="{{ $lp->getFirstMediaUrl('featured_image') }}">
                                        @endif
                                    </a>
                                    <div>
                                        <p class="post-meta" style="margin-bottom:3px;">{{ $lp->published_at->format('d M Y') }}</p>
                                        <a href="{{ route('frontend.article.show', $lp->slug) }}" class="post-title" style="font-size:11px;display:block;">{{ $lp->translate()->title }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    @endif
                </div>

                {{-- Mid-page ad after 2nd section --}}
                @if($catIdx === 1)
                    <div class="ad-box" style="width:100%;height:100px;margin-bottom:14px;">ADVERTISEMENT — 728 × 100</div>
                @endif

            @endforeach

            {{-- ─── VIDEO SECTION ─────────────────────── --}}
            @php
                $vPosts = \App\Models\Post::where('status','published')->with(['translations','media','category'])->inRandomOrder()->take(4)->get();
                $vMain  = $vPosts->first();
                $vList  = $vPosts->slice(1);
            @endphp
            @if($vMain)
            <div class="content-box" style="margin-bottom:14px;">
                <div class="sec-head">
                    <h3 class="sec-title">Latest Videos</h3>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <a href="#" class="more-link">More »</a>
                        <div class="nav-arrows"><span>‹</span><span>›</span></div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1.5fr 1fr;gap:10px;">
                    {{-- Main video --}}
                    <div>
                        <div style="position:relative;height:200px;background:#111;overflow:hidden;margin-bottom:8px;">
                            @if($vMain->hasMedia('featured_image'))
                                <img src="{{ $vMain->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;opacity:0.7;filter:grayscale(30%);">
                            @else
                                <div style="width:100%;height:100%;background:#333;"></div>
                            @endif
                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                                <div style="width:48px;height:48px;background:rgba(230,0,0,0.9);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                    <svg width="16" height="16" fill="#fff" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                </div>
                            </div>
                            <div style="position:absolute;top:8px;left:8px;background:#e60000;color:#fff;font-size:8px;font-weight:900;text-transform:uppercase;padding:2px 6px;">Video</div>
                            <div style="position:absolute;bottom:0;left:0;right:0;padding:10px;background:linear-gradient(to top,rgba(0,0,0,0.8),transparent);">
                                <p class="post-meta" style="color:rgba(255,255,255,0.7);margin-bottom:3px;">{{ $vMain->published_at->format('d M Y') }}</p>
                                <a href="{{ route('frontend.article.show', $vMain->slug) }}" style="font-size:14px;font-weight:700;color:#fff;line-height:1.3;">{{ $vMain->translate()->title }}</a>
                            </div>
                        </div>
                    </div>
                    {{-- Video list --}}
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        @foreach($vList as $v)
                            <div class="list-post" style="align-items:center;">
                                <div style="position:relative;width:90px;height:66px;flex-shrink:0;overflow:hidden;background:#222;">
                                    @if($v->hasMedia('featured_image'))
                                        <img src="{{ $v->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;filter:grayscale(50%);">
                                    @endif
                                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.3);">
                                        <svg width="10" height="10" fill="#fff" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="post-meta" style="margin-bottom:3px;">{{ $v->published_at->format('d M Y') }}</p>
                                    <a href="{{ route('frontend.article.show', $v->slug) }}" class="post-title" style="font-size:11px;display:block;">{{ $v->translate()->title }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- ─── BOTTOM 3-COLUMN SECTIONS ──────────── --}}
            @if($headerCategories->count() > 5)
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                @foreach($headerCategories->slice(5) as $bCat)
                    @php
                        $bPosts = $bCat->posts()->where('status','published')->with(['translations','media','author'])->latest('published_at')->take(4)->get();
                        if($bPosts->isEmpty()) continue;
                        $bFeat = $bPosts->first();
                    @endphp
                    <div class="content-box">
                        <div class="sec-head">
                            <h3 class="sec-title">{{ $bCat->getTranslation('name', app()->getLocale()) }}</h3>
                            <div class="nav-arrows"><span>‹</span><span>›</span></div>
                        </div>
                        @if($bFeat)
                        <a href="{{ route('frontend.article.show', $bFeat->slug) }}" class="img-card" style="display:block;height:130px;margin-bottom:8px;">
                            @if($bFeat->hasMedia('featured_image'))
                                <img src="{{ $bFeat->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;">
                            @else <div style="width:100%;height:100%;background:#ddd;"></div> @endif
                            <span class="img-cat" style="font-size:7px;padding:2px 4px;">{{ $bCat->getTranslation('name','en') }}</span>
                            <span class="img-flash"><svg width="7" height="7" fill="#fff" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
                        </a>
                        <p class="post-meta" style="margin-bottom:3px;">{{ $bFeat->author->name }} · {{ $bFeat->published_at->format('d M Y') }}</p>
                        <a href="{{ route('frontend.article.show', $bFeat->slug) }}" class="post-title" style="display:block;font-size:12px;margin-bottom:8px;">{{ $bFeat->translate()->title }}</a>
                        @endif
                        @foreach($bPosts->slice(1) as $bp)
                            <div class="list-post">
                                <a href="{{ route('frontend.article.show', $bp->slug) }}" class="list-post-img" style="width:70px;height:52px;">
                                    @if($bp->hasMedia('featured_image'))<img src="{{ $bp->getFirstMediaUrl('featured_image') }}">@endif
                                </a>
                                <div>
                                    <p class="post-meta" style="margin-bottom:2px;">{{ $bp->published_at->format('d M Y') }}</p>
                                    <a href="{{ route('frontend.article.show', $bp->slug) }}" class="post-title" style="font-size:11px;display:block;">{{ $bp->translate()->title }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            @endif

        </div>{{-- /main col --}}

        {{-- ═══════════════════════════════════════════
             SIDEBAR — ~300px
        ═══════════════════════════════════════════ --}}
        <div style="width:296px;flex-shrink:0;">
            <div style="position:sticky;top:60px;">

                {{-- Social Counters Widget --}}
                <div class="content-box" style="margin-bottom:12px;">
                    <div class="sec-head"><h3 class="sec-title">Follow Us</h3></div>
                    <a href="#" class="social-btn" style="background:#3b5998;margin-bottom:4px;"><span>Facebook</span><span>{{ number_format(rand(10000,50000)) }} Fans</span></a>
                    <a href="#" class="social-btn" style="background:#1da1f2;margin-bottom:4px;"><span>Twitter</span><span>{{ number_format(rand(5000,30000)) }} Followers</span></a>
                    <a href="#" class="social-btn" style="background:#dd4b39;margin-bottom:4px;"><span>Google+</span><span>{{ number_format(rand(2000,15000)) }} Followers</span></a>
                    <a href="#" class="social-btn" style="background:#ff6600;margin-bottom:4px;"><span>RSS</span><span>{{ number_format(rand(500,5000)) }} Readers</span></a>
                    <a href="#" class="social-btn" style="background:#00aabb;margin-bottom:4px;"><span>Vimeo</span><span>{{ number_format(rand(1000,10000)) }} Subscribers</span></a>
                    <a href="#" class="social-btn" style="background:#ff0000;"><span>YouTube</span><span>{{ number_format(rand(3000,25000)) }} Subscribers</span></a>
                </div>

                {{-- Sidebar Ad 300×250 --}}
                <div class="ad-box" style="width:100%;height:250px;margin-bottom:12px;">300 × 250 AD</div>

                {{-- Newsletter --}}
                <div style="background:#e60000;padding:16px;margin-bottom:12px;">
                    <div style="font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;color:#fff;border-top:2px solid rgba(255,255,255,0.4);padding-top:7px;margin-bottom:10px;">Newsletter</div>
                    <p style="font-size:11px;color:rgba(255,255,255,0.85);margin-bottom:12px;line-height:1.55;">Get top business stories in your inbox every morning.</p>
                    <form style="display:flex;flex-direction:column;gap:6px;">
                        <input type="text" placeholder="Your Name"
                               style="background:rgba(255,255,255,0.15);border:none;color:#fff;font-size:11px;padding:8px 10px;outline:none;placeholder:color:#fff80;">
                        <input type="email" placeholder="Your Email"
                               style="background:rgba(255,255,255,0.15);border:none;color:#fff;font-size:11px;padding:8px 10px;outline:none;">
                        <button style="background:#111;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;padding:10px;border:none;cursor:pointer;"
                                onmouseover="this.style.background='#333'" onmouseout="this.style.background='#111'">
                            Subscribe Now →
                        </button>
                    </form>
                </div>

                {{-- Popular Posts --}}
                <div class="content-box" style="margin-bottom:12px;">
                    <div class="sec-head"><h3 class="sec-title">Most Popular</h3></div>
                    @foreach($sidebarTrendingArticles->take(5) as $i => $tp)
                        <div class="list-post">
                            <div style="font-size:22px;font-weight:900;color:#eee;line-height:1;width:28px;flex-shrink:0;text-align:center;">{{ $i+1 }}</div>
                            <div>
                                <span style="background:#e60000;color:#fff;font-size:8px;font-weight:900;text-transform:uppercase;padding:1px 5px;display:inline-block;margin-bottom:3px;">
                                    {{ $tp->category->getTranslation('name','en') }}
                                </span>
                                <a href="{{ route('frontend.article.show', $tp->slug) }}" class="post-title" style="font-size:11px;display:block;line-height:1.35;">{{ $tp->translate()->title }}</a>
                                <p class="post-meta" style="margin-top:3px;">{{ $tp->published_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Poll Widget --}}
                <div class="content-box" style="margin-bottom:12px;">
                    <div class="sec-head"><h3 class="sec-title">Reader Poll</h3></div>
                    <p style="font-size:12px;font-weight:600;color:#222;margin-bottom:12px;line-height:1.4;">Which sector will drive GCC growth most in 2025?</p>
                    @foreach([['Tech & AI', 42], ['Real Estate', 28], ['Tourism', 18], ['Finance', 12]] as [$opt, $pct])
                        <div style="margin-bottom:8px;">
                            <div style="display:flex;justify-content:space-between;font-size:10px;font-weight:600;color:#444;margin-bottom:3px;">
                                <span>{{ $opt }}</span><span style="color:#e60000;">{{ $pct }}%</span>
                            </div>
                            <div style="background:#eee;height:5px;border-radius:2px;">
                                <div style="background:#e60000;height:5px;border-radius:2px;width:{{ $pct }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                    <button style="width:100%;background:#e60000;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;padding:8px;border:none;cursor:pointer;margin-top:10px;"
                            onmouseover="this.style.background='#c00'" onmouseout="this.style.background='#e60000'">
                        Vote Now
                    </button>
                </div>

                {{-- Second sidebar ad --}}
                <div class="ad-box" style="width:100%;height:200px;margin-bottom:12px;">300 × 200 AD</div>

                {{-- Featured News Tabs --}}
                <div class="content-box">
                    <div class="sec-head"><h3 class="sec-title">Trending Now</h3></div>
                    @foreach($sidebarTrendingArticles->slice(5)->take(4) as $i => $fp)
                        <div class="list-post">
                            <a href="{{ route('frontend.article.show', $fp->slug) }}" class="list-post-img" style="width:70px;height:52px;">
                                @if($fp->hasMedia('featured_image'))
                                    <img src="{{ $fp->getFirstMediaUrl('featured_image') }}">
                                @endif
                            </a>
                            <div>
                                <p class="post-meta" style="margin-bottom:3px;">{{ $fp->published_at->format('d M Y') }}</p>
                                <a href="{{ route('frontend.article.show', $fp->slug) }}" class="post-title" style="font-size:11px;display:block;">{{ $fp->translate()->title }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>{{-- /sidebar --}}

    </div>{{-- /flex --}}
</div>{{-- /wrap --}}
</x-frontend-layout>
