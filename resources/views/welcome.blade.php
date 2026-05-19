<x-frontend-layout>
    @push('styles')
        {{-- Custom homepage styles can go here --}}
    @endpush

    <div class="wrap" style="padding-top:14px;padding-bottom:28px;">

        {{-- ═══════════════════════════════════════════
        FULL-WIDTH HERO SLIDER (above main flex)
        ═══════════════════════════════════════════ --}}

        {{-- ─── TABBED HERO SLIDER ─────────────────────── --}}
        @php
            $hero1 = $heroFeatured->toJson();
            $hero2 = $heroBusiness->toJson();
            $hero3 = $heroTechnology->toJson();
            $hero4 = $heroMarkets->toJson();
        @endphp

        <div x-data="heroSlider({
                    box1Posts:   {{ $hero1 }},
                    box2Posts:   {{ $hero2 }},
                    box3Posts:   {{ $hero3 }},
                    box4Posts:   {{ $hero4 }},
                    box1Auto:    {{ $heroSettings['box1_autoplay'] }},
                    box1Speed:   {{ max(3000, (int) $heroSettings['box1_speed']) }},
                    box2Auto:    {{ $heroSettings['box2_autoplay'] }},
                    box2Speed:   {{ max(4000, (int) $heroSettings['box2_speed']) }},
                    box3Auto:    {{ $heroSettings['box3_autoplay'] }},
                    box3Speed:   {{ max(5000, (int) $heroSettings['box3_speed']) }},
                    box4Auto:    {{ $heroSettings['box4_autoplay'] }},
                    box4Speed:   {{ max(6000, (int) $heroSettings['box4_speed']) }},
                })" style="margin-bottom:14px;">

            {{-- ── Hero Grid: Box1 (large) + Box2 + Box3 + Box4 ──── --}}
            <div class="home-hero-grid" style="display:grid;grid-template-columns:1.5fr 1fr;grid-template-rows:auto auto;gap:3px;">

                {{-- ── BOX 1: Large Main Slider ─────────────────── --}}
                <div class="home-hero-main" style="grid-row:1/3;position:relative;height:500px;overflow:hidden;background:#111;">
                    <template x-for="(post, idx) in box1Posts" :key="post.id">
                        <div :style="idx===box1Idx ? 'opacity:1;z-index:2;' : 'opacity:0;z-index:1;'"
                            style="position:absolute;inset:0;transition:opacity 0.6s ease;">
                            <a :href="'/article/'+post.slug" style="display:block;height:100%;position:relative;">
                                <img :src="post.image" :alt="post.title"
                                    style="width:100%;height:100%;object-fit:cover;">
                                <div
                                    style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.88) 0%,rgba(0,0,0,0.15) 55%,transparent 100%);">
                                </div>
                                <div style="position:absolute;top:8px;left:8px;">
                                    <span
                                        style="background:#e60000;color:#fff;font-size:8px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;padding:2px 7px;"
                                        x-text="post.category"></span>
                                </div>
                                <div style="position:absolute;top:8px;right:8px;background:#e60000;padding:4px;">
                                    <svg width="9" height="9" fill="#fff" viewBox="0 0 24 24">
                                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div style="position:absolute;bottom:0;left:0;right:0;padding:14px;">
                                    <p style="font-size:9px;font-weight:600;color:rgba(255,255,255,0.65);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:5px;"
                                        x-text="post.author+' · '+post.date"></p>
                                    <h2 style="font-size:16px;font-weight:800;color:#fff;line-height:1.3;text-shadow:0 1px 4px rgba(0,0,0,0.6);"
                                        x-text="post.title"></h2>
                                    <p style="font-size:11px;color:rgba(255,255,255,0.7);margin-top:5px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"
                                        x-text="post.excerpt"></p>
                                </div>
                            </a>
                        </div>
                    </template>

                    {{-- Box1 Controls --}}
                    <div
                        style="position:absolute;bottom:10px;right:10px;z-index:10;display:flex;align-items:center;gap:5px;">
                        {{-- Dots --}}
                        <div style="display:flex;gap:3px;">
                            <template x-for="(p, i) in box1Posts" :key="i">
                                <button @click="box1Idx=i;box1ResetTimer()"
                                    :style="i===box1Idx ? 'background:#e60000;width:18px;' : 'background:rgba(255,255,255,0.5);width:8px;'"
                                    style="height:4px;border:none;cursor:pointer;transition:all 0.3s;border-radius:2px;padding:0;"></button>
                            </template>
                        </div>
                        {{-- Prev/Next --}}
                        <button @click="box1Prev()"
                            style="background:rgba(0,0,0,0.5);color:#fff;border:none;width:22px;height:22px;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;">‹</button>
                        <button @click="box1Next()"
                            style="background:rgba(0,0,0,0.5);color:#fff;border:none;width:22px;height:22px;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;">›</button>
                        {{-- Play/Pause --}}
                        <button @click="box1Playing=!box1Playing;box1Playing?box1StartTimer():box1ClearTimer()"
                            style="background:rgba(230,0,0,0.8);color:#fff;border:none;width:22px;height:22px;font-size:9px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                            <span x-text="box1Playing ? '⏸' : '▶'"></span>
                        </button>
                    </div>
                    {{-- Prev/Next side arrows --}}
                    <button @click="box1Prev()"
                        style="position:absolute;left:0;top:50%;transform:translateY(-50%);z-index:10;background:rgba(0,0,0,0.4);color:#fff;border:none;width:28px;height:48px;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;">‹</button>
                    <button @click="box1Next()"
                        style="position:absolute;right:0;top:50%;transform:translateY(-50%);z-index:10;background:rgba(0,0,0,0.4);color:#fff;border:none;width:28px;height:48px;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;">›</button>
                </div>

                {{-- ── BOX 2: Top-right slider ───────────────────── --}}
                <div class="home-hero-box-2" style="position:relative;height:248px;overflow:hidden;background:#111;">
                    <template x-for="(post, idx) in box2Posts" :key="post.id">
                        <div :style="idx===box2Idx ? 'opacity:1;z-index:2;' : 'opacity:0;z-index:1;'"
                            style="position:absolute;inset:0;transition:opacity 0.6s ease;">
                            <a :href="'/article/'+post.slug" style="display:block;height:100%;position:relative;">
                                <img :src="post.image" style="width:100%;height:100%;object-fit:cover;">
                                <div
                                    style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.82) 0%,transparent 60%);">
                                </div>
                                <div style="position:absolute;top:5px;left:5px;"><span
                                        style="background:#e60000;color:#fff;font-size:7px;font-weight:900;text-transform:uppercase;padding:1px 5px;"
                                        x-text="post.category"></span></div>
                                <div style="position:absolute;bottom:0;left:0;right:0;padding:8px;">
                                    <p style="font-size:8px;color:rgba(255,255,255,0.6);margin-bottom:3px;"
                                        x-text="post.date"></p>
                                    <h3 style="font-size:12px;font-weight:700;color:#fff;line-height:1.25;"
                                        x-text="post.title"></h3>
                                </div>
                            </a>
                        </div>
                    </template>
                    <div
                        style="position:absolute;bottom:5px;right:5px;z-index:10;display:flex;gap:3px;align-items:center;">
                        <button @click="box2Prev()"
                            style="background:rgba(0,0,0,0.5);color:#fff;border:none;width:18px;height:18px;font-size:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;">‹</button>
                        <button @click="box2Next()"
                            style="background:rgba(0,0,0,0.5);color:#fff;border:none;width:18px;height:18px;font-size:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;">›</button>
                        <button @click="box2Playing=!box2Playing;box2Playing?box2StartTimer():box2ClearTimer()"
                            style="background:rgba(230,0,0,0.8);color:#fff;border:none;width:18px;height:18px;font-size:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                            <span x-text="box2Playing ? '⏸' : '▶'"></span>
                        </button>
                    </div>
                </div>

                {{-- ── BOX 3 + BOX 4 (bottom right row) ─────────── --}}
                <div class="home-hero-sub-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:3px;">

                    {{-- BOX 3 --}}
                    <div class="home-hero-box-small" style="position:relative;height:248px;overflow:hidden;background:#111;">
                        <template x-for="(post, idx) in box3Posts" :key="post.id">
                            <div :style="idx===box3Idx ? 'opacity:1;z-index:2;' : 'opacity:0;z-index:1;'"
                                style="position:absolute;inset:0;transition:opacity 0.6s ease;">
                                <a :href="'/article/'+post.slug" style="display:block;height:100%;position:relative;">
                                    <img :src="post.image" style="width:100%;height:100%;object-fit:cover;">
                                    <div
                                        style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.82) 0%,transparent 60%);">
                                    </div>
                                    <div style="position:absolute;top:5px;left:5px;"><span
                                            style="background:#e60000;color:#fff;font-size:7px;font-weight:900;text-transform:uppercase;padding:1px 5px;"
                                            x-text="post.category"></span></div>
                                    <div style="position:absolute;bottom:0;left:0;right:0;padding:8px;">
                                        <p style="font-size:8px;color:rgba(255,255,255,0.6);margin-bottom:3px;"
                                            x-text="post.date"></p>
                                        <h3 style="font-size:11px;font-weight:700;color:#fff;line-height:1.25;"
                                            x-text="post.title"></h3>
                                    </div>
                                </a>
                            </div>
                        </template>
                        <div
                            style="position:absolute;bottom:5px;right:5px;z-index:10;display:flex;gap:2px;align-items:center;">
                            <button @click="box3Prev()"
                                style="background:rgba(0,0,0,0.5);color:#fff;border:none;width:16px;height:16px;font-size:9px;cursor:pointer;">‹</button>
                            <button @click="box3Next()"
                                style="background:rgba(0,0,0,0.5);color:#fff;border:none;width:16px;height:16px;font-size:9px;cursor:pointer;">›</button>
                            <button @click="box3Playing=!box3Playing;box3Playing?box3StartTimer():box3ClearTimer()"
                                style="background:rgba(230,0,0,0.8);color:#fff;border:none;width:16px;height:16px;font-size:7px;cursor:pointer;">
                                <span x-text="box3Playing ? '⏸' : '▶'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- BOX 4 --}}
                    <div class="home-hero-box-small" style="position:relative;height:248px;overflow:hidden;background:#111;">
                        <template x-for="(post, idx) in box4Posts" :key="post.id">
                            <div :style="idx===box4Idx ? 'opacity:1;z-index:2;' : 'opacity:0;z-index:1;'"
                                style="position:absolute;inset:0;transition:opacity 0.6s ease;">
                                <a :href="'/article/'+post.slug" style="display:block;height:100%;position:relative;">
                                    <img :src="post.image" style="width:100%;height:100%;object-fit:cover;">
                                    <div
                                        style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.82) 0%,transparent 60%);">
                                    </div>
                                    <div style="position:absolute;top:5px;left:5px;"><span
                                            style="background:#e60000;color:#fff;font-size:7px;font-weight:900;text-transform:uppercase;padding:1px 5px;"
                                            x-text="post.category"></span></div>
                                    <div style="position:absolute;bottom:0;left:0;right:0;padding:8px;">
                                        <p style="font-size:8px;color:rgba(255,255,255,0.6);margin-bottom:3px;"
                                            x-text="post.date"></p>
                                        <h3 style="font-size:11px;font-weight:700;color:#fff;line-height:1.25;"
                                            x-text="post.title"></h3>
                                    </div>
                                </a>
                            </div>
                        </template>
                        <div
                            style="position:absolute;bottom:5px;right:5px;z-index:10;display:flex;gap:2px;align-items:center;">
                            <button @click="box4Prev()"
                                style="background:rgba(0,0,0,0.5);color:#fff;border:none;width:16px;height:16px;font-size:9px;cursor:pointer;">‹</button>
                            <button @click="box4Next()"
                                style="background:rgba(0,0,0,0.5);color:#fff;border:none;width:16px;height:16px;font-size:9px;cursor:pointer;">›</button>
                            <button @click="box4Playing=!box4Playing;box4Playing?box4StartTimer():box4ClearTimer()"
                                style="background:rgba(230,0,0,0.8);color:#fff;border:none;width:16px;height:16px;font-size:7px;cursor:pointer;">
                                <span x-text="box4Playing ? '⏸' : '▶'"></span>
                            </button>
                        </div>
                    </div>
                </div>{{-- /box3+4 --}}

            </div>{{-- /hero grid --}}
        </div>{{-- /x-data --}}

        {{-- ── Hero Slider Alpine.js Component ─────────────────── --}}
        @push('scripts')
            <script>
        function heroSlider(cfg) {
            return {
                box1Posts: cfg.box1Posts || [],
                box2Posts: cfg.box2Posts || [],
                box3Posts: cfg.box3Posts || [],
                box4Posts: cfg.box4Posts || [],

                // Box indexes
                box1Idx: 0, box2Idx: 0, box3Idx: 0, box4Idx: 0,
                // Play state
                box1Playing: !!cfg.box1Auto,
                box2Playing: !!cfg.box2Auto,
                box3Playing: !!cfg.box3Auto,
                box4Playing: !!cfg.box4Auto,
                // Timers
                _t1: null, _t2: null, _t3: null, _t4: null,

                // Box 1
                box1Prev() { this.box1Idx = (this.box1Idx - 1 + this.box1Posts.length) % this.box1Posts.length; this.box1ResetTimer(); },
                box1Next() { this.box1Idx = (this.box1Idx + 1) % this.box1Posts.length; this.box1ResetTimer(); },
                box1StartTimer() { if (!cfg.box1Auto || !this.box1Posts.length) return; this._t1 = setInterval(() => { this.box1Idx = (this.box1Idx + 1) % this.box1Posts.length; }, cfg.box1Speed); },
                box1ClearTimer() { clearInterval(this._t1); },
                box1ResetTimer() { this.box1ClearTimer(); if (this.box1Playing) this.box1StartTimer(); },

                // Box 2
                box2Prev() { this.box2Idx = (this.box2Idx - 1 + this.box2Posts.length) % this.box2Posts.length; this.box2ResetTimer(); },
                box2Next() { this.box2Idx = (this.box2Idx + 1) % this.box2Posts.length; this.box2ResetTimer(); },
                box2StartTimer() { if (!cfg.box2Auto || !this.box2Posts.length) return; this._t2 = setInterval(() => { this.box2Idx = (this.box2Idx + 1) % this.box2Posts.length; }, cfg.box2Speed); },
                box2ClearTimer() { clearInterval(this._t2); },
                box2ResetTimer() { this.box2ClearTimer(); if (this.box2Playing) this.box2StartTimer(); },

                // Box 3
                box3Prev() { this.box3Idx = (this.box3Idx - 1 + this.box3Posts.length) % this.box3Posts.length; this.box3ResetTimer(); },
                box3Next() { this.box3Idx = (this.box3Idx + 1) % this.box3Posts.length; this.box3ResetTimer(); },
                box3StartTimer() { if (!cfg.box3Auto || !this.box3Posts.length) return; this._t3 = setInterval(() => { this.box3Idx = (this.box3Idx + 1) % this.box3Posts.length; }, cfg.box3Speed); },
                box3ClearTimer() { clearInterval(this._t3); },
                box3ResetTimer() { this.box3ClearTimer(); if (this.box3Playing) this.box3StartTimer(); },

                // Box 4
                box4Prev() { this.box4Idx = (this.box4Idx - 1 + this.box4Posts.length) % this.box4Posts.length; this.box4ResetTimer(); },
                box4Next() { this.box4Idx = (this.box4Idx + 1) % this.box4Posts.length; this.box4ResetTimer(); },
                box4StartTimer() { if (!cfg.box4Auto || !this.box4Posts.length) return; this._t4 = setInterval(() => { this.box4Idx = (this.box4Idx + 1) % this.box4Posts.length; }, cfg.box4Speed); },
                box4ClearTimer() { clearInterval(this._t4); },
                box4ResetTimer() { this.box4ClearTimer(); if (this.box4Playing) this.box4StartTimer(); },

                init() {
                    this.box1StartTimer();
                    this.box2StartTimer();
                    this.box3StartTimer();
                    this.box4StartTimer();
                }
            };
        }
            </script>
        @endpush
        {{-- ── MAIN FLEX LAYOUT (below hero) ── --}}
        <div class="flex gap-5 home-main-layout" style="margin-top:14px;">

            {{-- ════ MAIN COLUMN ════ --}}
            <div class="home-main-column" style="flex:1;min-width:0;">
                @php $eIds = $usedHeroPostIds ?? [];
                $hc = $headerCategories->values(); @endphp

                {{-- ── MACRO: fetch posts helper ── --}}
                @php
                    $cposts = function ($cat, $n = 10) use ($eIds) {
                        return $cat->posts()->where('status', 'published')
                            ->whereNotIn('id', $eIds)
                            ->with(['translations', 'media', 'author', 'category'])
                            ->latest('published_at')->take($n)->get();
                    };
                @endphp

                {{-- ── ROW 1: 2-up grid (cats 0,1) ── --}}
                <div class="home-two-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                    @for ($i = 0; $i <= 1; $i++)
                        @php
                            $cat = $hc->get($i);
                            if (!$cat) { continue; }
                            $isBusiness = $cat->getTranslation('name', 'en') === 'Business';
                            $ps = $cposts($cat, $isBusiness ? 15 : 10);
                            $groups = $ps->chunk(5);
                        @endphp
                        @if ($ps->isNotEmpty())
                            <div class="content-box home-content-box" style="position:relative;"
                                x-data="{ 
                                    index: 0, 
                                    loading: false, 
                                    total: {{ $groups->count() }},
                                    next() {
                                        if(this.loading) return;
                                        this.loading = true;
                                        setTimeout(() => {
                                            this.index = (this.index + 1) % this.total;
                                            this.loading = false;
                                        }, 700);
                                    },
                                    prev() {
                                        if(this.loading) return;
                                        this.loading = true;
                                        setTimeout(() => {
                                            this.index = (this.index - 1 + this.total) % this.total;
                                            this.loading = false;
                                        }, 700);
                                    }
                                }">
                                
                                {{-- Loading Overlay --}}
                                 <div x-show="loading"
                                     style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.85);z-index:10000;">
                                     <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
                                         <div class="loading-spinner"></div>
                                     </div>
                                 </div>

                                <div class="sec-head">
                                    <h3 class="sec-title">{{ $cat->getTranslation('name', app()->getLocale()) }}</h3>
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <a href="{{ route('frontend.category.show', $cat->slug) }}" class="more-link">More »</a>
                                        <div class="nav-arrows">
                                            <span @click="prev()">‹</span>
                                            <span @click="next()">›</span>
                                        </div>
                                    </div>
                                </div>

                                @foreach ($groups as $gIndex => $group)
                                    <div x-show="index === {{ $gIndex }}">
                                        @php
                                            $pf = $group->first();
                                            $others = $group->slice(1);
                                        @endphp
                                        @if ($pf)
                                            <a href="{{ route('frontend.article.show', $pf->slug) }}" class="img-card"
                                                style="display:block;height:170px;margin-bottom:8px;">
                                                @if ($pf->hasMedia('featured_image'))
                                                    <img src="{{ $pf->getFirstMediaUrl('featured_image') }}"
                                                        style="width:100%;height:100%;object-fit:cover;">
                                                @else
                                                    <div style="width:100%;height:100%;background:#ccc;"></div>
                                                @endif
                                                <span class="img-cat">{{ $pf->category?->getTranslation('name', 'en') }}</span>
                                                <span class="img-flash"><svg width="8" height="8" fill="#fff"
                                                        viewBox="0 0 24 24">
                                                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg></span>
                                            </a>
                                            <p class="post-meta" style="margin-bottom:4px;">{{ $pf->author?->name }} ·
                                                {{ $pf->published_at?->format('d M Y') }}</p>
                                            <a href="{{ route('frontend.article.show', $pf->slug) }}" class="post-title"
                                                style="display:block;font-size:13px;font-weight:700;margin-bottom:12px;line-height:1.35;height:35px;overflow:hidden;">{{ $pf->translate()?->title }}</a>

                                            @if ($isBusiness)
                                                {{-- 2x2 Grid for Business --}}
                                                <div class="home-two-grid keep-2-col-mobile" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                                    @foreach ($others as $lp)
                                                        <div class="group">
                                                            <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                class="img-card"
                                                                style="display:block;height:80px;margin-bottom:5px;">
                                                                @if ($lp->hasMedia('featured_image'))
                                                                    <img src="{{ $lp->getFirstMediaUrl('featured_image') }}"
                                                                        style="width:100%;height:100%;object-fit:cover;">
                                                                @endif
                                                            </a>
                                                            <p class="post-meta" style="margin-bottom:2px;font-size:8px;">{{ $lp->published_at?->format('d M Y') }}</p>
                                                            <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                class="post-title"
                                                                style="display:block;font-size:12px;font-weight:700;line-height:1.2;height:40px;overflow:hidden;">{{ Str::limit($lp->translate()?->title, 60) }}</a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                {{-- Standard List for Others --}}
                                                @foreach ($others as $lp)
                                                    <div class="list-post">
                                                        <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                            class="list-post-img">
                                                            @if ($lp->hasMedia('featured_image'))
                                                                <img src="{{ $lp->getFirstMediaUrl('featured_image') }}">
                                                            @endif
                                                        </a>
                                                        <div>
                                                            <p class="post-meta" style="margin-bottom:2px;">
                                                                {{ $lp->published_at?->format('d M Y') }}</p>
                                                            <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                class="post-title"
                                                                style="display:block;font-size:12px;line-height:1.3;">{{ Str::limit($lp->translate()?->title, 60) }}</a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endfor
                </div>

                {{-- Dynamic Ad 1 --}}
                <div style="margin-bottom:14px; overflow:hidden; border-radius:4px;">
                    <x-ad-banner position="home_between_1" />
                </div>

                {{-- Dynamic Ad 2 (Removed duplication and shifted down) --}}

                {{-- ── ROW 2: Full-width featured (Markets only) ── --}}
                @for($i = 2; $i <= 2; $i++)
                    @php
                        $cat = $hc->get($i);
                        if (!$cat) { continue; }
                        $ps = $cposts($cat, 10);
                        $groups = $ps->chunk(5);
                    @endphp
                    @if ($ps->isNotEmpty())
                        <div class="content-box home-content-box" style="margin-bottom:14px;position:relative;"
                            x-data="{ 
                                index: 0, 
                                loading: false, 
                                total: {{ $groups->count() }},
                                next() {
                                    if(this.loading) return;
                                    this.loading = true;
                                    setTimeout(() => {
                                        this.index = (this.index + 1) % this.total;
                                        this.loading = false;
                                    }, 700);
                                },
                                prev() {
                                    if(this.loading) return;
                                    this.loading = true;
                                    setTimeout(() => {
                                        this.index = (this.index - 1 + this.total) % this.total;
                                        this.loading = false;
                                    }, 700);
                                }
                            }">

                            {{-- Loading Overlay --}}
                            <div x-show="loading"
                                style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.85);z-index:10000;">
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
                                    <div class="loading-spinner"></div>
                                </div>
                            </div>
                            <div class="sec-head">
                                <h3 class="sec-title">{{ $cat->getTranslation('name', app()->getLocale()) }}</h3>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <a href="{{ route('frontend.category.show', $cat->slug) }}" class="more-link">More »</a>
                                    <div class="nav-arrows">
                                        <span @click="prev()">‹</span>
                                        <span @click="next()">›</span>
                                    </div>
                                </div>
                            </div>

                            @foreach ($groups as $gIndex => $group)
                                <div x-show="index === {{ $gIndex }}">
                                    @php
                                        $pf = $group->first();
                                        $others = $group->slice(1);
                                    @endphp
                                    @if ($pf)
                                            <div class="home-two-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                            <div>
                                                <a href="{{ route('frontend.article.show', $pf->slug) }}" class="img-card"
                                                    style="display:block;height:185px;margin-bottom:8px;">
                                                    @if ($pf->hasMedia('featured_image'))
                                                        <img src="{{ $pf->getFirstMediaUrl('featured_image') }}"
                                                            style="width:100%;height:100%;object-fit:cover;">
                                                    @else
                                                        <div style="width:100%;height:100%;background:#ccc;"></div>
                                                    @endif
                                                    <span class="img-cat">{{ $pf->category?->getTranslation('name', 'en') }}</span>
                                                    <span class="img-flash"><svg width="8" height="8" fill="#fff"
                                                            viewBox="0 0 24 24">
                                                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                        </svg></span>
                                                </a>
                                                <p class="post-meta" style="margin-bottom:4px;">{{ $pf->author?->name }} ·
                                                    {{ $pf->published_at?->format('d M Y') }}</p>
                                                <a href="{{ route('frontend.article.show', $pf->slug) }}" class="post-title"
                                                    style="display:block;font-size:14px;font-weight:700;margin-bottom:5px;line-height:1.35;">{{ $pf->translate()?->title }}</a>
                                                <p class="post-excerpt"
                                                    style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                                    {{ $pf->translate()?->excerpt }}</p>
                                            </div>
                                            <div class="home-two-grid keep-2-col-mobile" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                                @foreach ($others as $lp)
                                                    <div class="group">
                                                        <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                            class="img-card"
                                                            style="display:block;height:80px;margin-bottom:5px;">
                                                            @if ($lp->hasMedia('featured_image'))
                                                                <img src="{{ $lp->getFirstMediaUrl('featured_image') }}"
                                                                    style="width:100%;height:100%;object-fit:cover;">
                                                            @endif
                                                        </a>
                                                        <p class="post-meta" style="margin-bottom:2px;font-size:9px;">
                                                            {{ $lp->published_at?->format('d M Y') }}</p>
                                                        <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                            class="post-title"
                                                            style="display:block;font-size:12px;font-weight:700;line-height:1.3;height:34px;overflow:hidden;">{{ Str::limit($lp->translate()?->title, 50) }}</a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endfor

                {{-- ── ROW 3: 2-up grid (Technology & GCC News) ── --}}
                <div class="home-two-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                    @for($i = 3; $i <= 4; $i++)
                        @php
                            $cat = $hc->get($i);
                            if (!$cat) { continue; }
                            $ps = $cposts($cat, 10);
                            $groups = $ps->chunk(5);
                        @endphp
                        @if ($ps->isNotEmpty())
                            <div class="content-box home-content-box" style="position:relative;"
                                x-data="{ 
                                    index: 0, 
                                    loading: false, 
                                    total: {{ $groups->count() }},
                                    next() {
                                        if(this.loading) return;
                                        this.loading = true;
                                        setTimeout(() => {
                                            this.index = (this.index + 1) % this.total;
                                            this.loading = false;
                                        }, 700);
                                    },
                                    prev() {
                                        if(this.loading) return;
                                        this.loading = true;
                                        setTimeout(() => {
                                            this.index = (this.index - 1 + this.total) % this.total;
                                            this.loading = false;
                                        }, 700);
                                    }
                                }">
                                
                                {{-- Loading Overlay --}}
                                 <div x-show="loading"
                                     style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.85);z-index:10000;">
                                     <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
                                         <div class="loading-spinner"></div>
                                     </div>
                                 </div>
                                <div class="sec-head">
                                    <h3 class="sec-title">{{ $cat->getTranslation('name', app()->getLocale()) }}</h3>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <a href="{{ route('frontend.category.show', $cat->slug) }}" class="more-link">More »</a>
                                    <div class="nav-arrows">
                                        <span @click="prev()">‹</span>
                                        <span @click="next()">›</span>
                                    </div>
                                </div>
                            </div>

                            @foreach ($groups as $gIndex => $group)
                                <div x-show="index === {{ $gIndex }}">
                                    @php
                                        $pf = $group->first();
                                        $others = $group->slice(1);
                                    @endphp
                                    @if ($pf)
                                <a href="{{ route('frontend.article.show', $pf->slug) }}" class="img-card"
                                    style="display:block;height:155px;margin-bottom:8px;">
                                    @if($pf->hasMedia('featured_image'))<img src="{{ $pf->getFirstMediaUrl('featured_image') }}"
                                    style="width:100%;height:100%;object-fit:cover;">@else<div
                                    style="width:100%;height:100%;background:#ccc;"></div>@endif
                                    <span class="img-cat">{{ $pf->category?->getTranslation('name', 'en') }}</span>
                                    <span class="img-flash"><svg width="8" height="8" fill="#fff" viewBox="0 0 24 24">
                                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg></span>
                                </a>
                                <p class="post-meta" style="margin-bottom:4px;">{{ $pf->author?->name }} ·
                                    {{ $pf->published_at?->format('d M Y') }}</p>
                                <a href="{{ route('frontend.article.show', $pf->slug) }}" class="post-title"
                                    style="display:block;font-size:13px;font-weight:700;margin-bottom:8px;line-height:1.35;">{{ $pf->translate()?->title }}</a>
                                                <div class="home-list-grid keep-2-col-mobile" style="display:grid;grid-template-columns:repeat(2, 1fr);gap:10px;margin-top:12px;border-top:1px solid #f0f0f0;padding-top:12px;">
                                                    @foreach ($others as $lp)
                                                        <div>
                                                            <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                class="img-card"
                                                                style="display:block;height:65px;margin-bottom:5px;">
                                                                @if ($lp->hasMedia('featured_image'))
                                                                    <img src="{{ $lp->getFirstMediaUrl('featured_image') }}"
                                                                        style="width:100%;height:100%;object-fit:cover;">
                                                                @endif
                                                            </a>
                                                            <p class="post-meta" style="margin-bottom:2px;font-size:9px;">
                                                                {{ $lp->published_at?->format('d M Y') }}</p>
                                                            <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                class="post-title"
                                                                style="display:block;font-size:12px;font-weight:700;line-height:1.25;height:34px;overflow:hidden;">{{ Str::limit($lp->translate()?->title, 40) }}</a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endfor
                    </div>

                <div style="margin-bottom: 20px; overflow:hidden; border-radius:4px;">
                    <x-ad-banner position="home_between_2" />
                </div>

                {{-- ── ROW 4: Full-width featured (cats 5,6) ── --}}
                @for($i = 5; $i <= 6; $i++)
                    @php
                        $cat = $hc->get($i);
                        if (!$cat) { continue; }
                        $ps = $cposts($cat, 10);
                        $groups = $ps->chunk(5);
                    @endphp
                    @if ($ps->isNotEmpty())
                        <div class="content-box home-content-box" style="margin-bottom:14px;position:relative;"
                            x-data="{ 
                                index: 0, 
                                loading: false, 
                                total: {{ $groups->count() }},
                                next() {
                                    if(this.loading) return;
                                    this.loading = true;
                                    setTimeout(() => {
                                        this.index = (this.index + 1) % this.total;
                                        this.loading = false;
                                    }, 700);
                                },
                                prev() {
                                    if(this.loading) return;
                                    this.loading = true;
                                    setTimeout(() => {
                                        this.index = (this.index - 1 + this.total) % this.total;
                                        this.loading = false;
                                    }, 700);
                                }
                            }">

                            {{-- Loading Overlay --}}
                            <div x-show="loading"
                                style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.85);z-index:10000;">
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
                                    <div class="loading-spinner"></div>
                                </div>
                            </div>
                            <div class="sec-head">
                                <h3 class="sec-title">{{ $cat->getTranslation('name', app()->getLocale()) }}</h3>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <a href="{{ route('frontend.category.show', $cat->slug) }}" class="more-link">More »</a>
                                    <div class="nav-arrows">
                                        <span @click="prev()">‹</span>
                                        <span @click="next()">›</span>
                                    </div>
                                </div>
                            </div>

                            @foreach ($groups as $gIndex => $group)
                                <div x-show="index === {{ $gIndex }}">
                                    @php
                                        $pf = $group->first();
                                        $others = $group->slice(1);
                                    @endphp
                                    @if ($pf)
                                            <div class="home-feature-grid" style="display:grid;grid-template-columns:1.4fr 1fr;gap:12px;">
                                                <div>
                                                    <a href="{{ route('frontend.article.show', $pf->slug) }}"
                                                        class="img-card" style="display:block;height:185px;margin-bottom:8px;">
                                                        @if ($pf->hasMedia('featured_image'))
                                                            <img src="{{ $pf->getFirstMediaUrl('featured_image') }}"
                                                                style="width:100%;height:100%;object-fit:cover;">
                                                        @else
                                                            <div style="width:100%;height:100%;background:#ccc;"></div>
                                                        @endif
                                                        <span class="img-cat">{{ $pf->category?->getTranslation('name', 'en') }}</span>
                                                        <span class="img-flash"><svg width="8" height="8" fill="#fff"
                                                                viewBox="0 0 24 24">
                                                                <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                            </svg></span>
                                                    </a>
                                                    <p class="post-meta" style="margin-bottom:4px;">{{ $pf->author?->name }} ·
                                                        {{ $pf->published_at?->format('d M Y') }}</p>
                                                    <a href="{{ route('frontend.article.show', $pf->slug) }}"
                                                        class="post-title"
                                                        style="display:block;font-size:14px;font-weight:700;margin-bottom:5px;line-height:1.35;">{{ $pf->translate()?->title }}</a>
                                                    <p class="post-excerpt"
                                                        style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                                        {{ $pf->translate()?->excerpt }}</p>
                                                </div>
                                                <div>
                                                    @foreach ($others as $lp)
                                                        <div class="list-post">
                                                            <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                class="list-post-img">
                                                                @if ($lp->hasMedia('featured_image'))
                                                                    <img src="{{ $lp->getFirstMediaUrl('featured_image') }}">
                                                                @endif
                                                            </a>
                                                            <div>
                                                                <p class="post-meta" style="margin-bottom:2px;">
                                                                    {{ $lp->published_at?->format('d M Y') }}</p>
                                                                <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                    class="post-title"
                                                                    style="display:block;font-size:11px;line-height:1.3;">{{ $lp->translate()?->title }}</a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                            @endforeach
                        </div>
                    @endif
                @endfor


            </div>{{-- /main col --}}

            {{-- ════ SIDEBAR ════ --}}
            <div class="home-sidebar" style="width:300px;flex-shrink:0;">
                <div class="sidebar-sticky" style="position:sticky;top:60px;">

                    {{-- Dynamic Ad --}}
                    <div style="margin-bottom:12px;">
                        <x-ad-banner position="home_sidebar" />
                    </div>

                    {{-- Newsletter --}}
                    <div style="background:#e60000;padding:16px;margin-bottom:12px;">
                        <div
                            style="font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;color:#fff;border-top:2px solid rgba(255,255,255,0.4);padding-top:7px;margin-bottom:10px;">
                            Newsletter</div>
                        <p style="font-size:11px;color:rgba(255,255,255,0.85);margin-bottom:12px;line-height:1.55;">Get
                            top business stories in your inbox every morning.</p>
                            <form 
                                x-data="{
                                    name: '',
                                    email: '',
                                    loading: false,
                                    sent: false,
                                    message: '',
                                    async submit() {
                                        this.loading = true;
                                        this.message = '';
                                        try {
                                            const response = await fetch('{{ route('frontend.newsletter.subscribe') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Accept': 'application/json'
                                                },
                                                body: JSON.stringify({ email: this.email, name: this.name })
                                            });
                                            const result = await response.json();
                                            this.message = result.message;
                                            if (response.ok) {
                                                this.sent = true;
                                                this.email = '';
                                                this.name = '';
                                            }
                                        } catch (e) {
                                            this.message = 'Something went wrong.';
                                        } finally {
                                            this.loading = false;
                                        }
                                    }
                                }"
                                @submit.prevent="submit"
                                style="display:flex;flex-direction:column;gap:6px;"
                            >
                                <template x-if="!sent">
                                    <div style="display:flex;flex-direction:column;gap:6px;">
                                        <input type="text" placeholder="Your Name" x-model="name"
                                            style="background:rgba(255,255,255,0.15);border:none;color:#fff;font-size:11px;padding:8px 10px;outline:none;">
                                        <input type="email" placeholder="Your Email" x-model="email" required
                                            style="background:rgba(255,255,255,0.15);border:none;color:#fff;font-size:11px;padding:8px 10px;outline:none;">
                                        <button type="submit" :disabled="loading"
                                            style="background:#111;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;padding:10px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;"
                                            onmouseover="this.style.background='#333'"
                                            onmouseout="this.style.background='#111'">
                                            <svg x-show="loading" width="10" height="10" viewBox="0 0 24 24" style="animation: spin 1s linear infinite;"><path fill="currentColor" d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8Z"/></svg>
                                            <span x-text="loading ? '...' : 'Subscribe Now →'"></span>
                                        </button>
                                        <template x-if="message">
                                            <span x-text="message" style="font-size:10px;font-weight:bold;color:#fff;background:rgba(0,0,0,0.5);padding:4px 8px;border-radius:3px;"></span>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="sent">
                                    <div style="text-align:center;padding:10px;background:rgba(0,0,0,0.2);border-radius:4px;">
                                        <div style="font-size:24px;color:#fff;margin-bottom:5px;">✓</div>
                                        <p style="font-size:11px;font-weight:bold;color:#fff;margin:0;" x-text="message"></p>
                                    </div>
                                </template>
                            </form>
                    </div>

                    {{-- Most Popular --}}
                    <div class="content-box" style="margin-bottom:12px;">
                        <div class="sec-head">
                            <h3 class="sec-title">Most Popular</h3>
                        </div>
                        @foreach($sidebarTrendingArticles->take(5) as $i => $tp)
                            <div class="list-post">
                                <div
                                    style="font-size:22px;font-weight:900;color:#eee;line-height:1;width:28px;flex-shrink:0;text-align:center;">
                                    {{ $i + 1 }}</div>
                                <div>
                                    <span
                                        style="background:#e60000;color:#fff;font-size:8px;font-weight:900;text-transform:uppercase;padding:1px 5px;display:inline-block;margin-bottom:3px;">{{ $tp->category?->getTranslation('name', 'en') }}</span>
                                    <a href="{{ route('frontend.article.show', $tp->slug) }}" class="post-title"
                                        style="font-size:11px;display:block;line-height:1.35;">{{ $tp->translate()?->title }}</a>
                                    <p class="post-meta" style="margin-top:3px;">{{ $tp->published_at?->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Poll --}}
                    <x-reader-poll />

                    {{-- Dynamic Ad Slot --}}
                    <div style="margin-bottom:12px; overflow:hidden; border-radius:4px;">
                        <x-ad-banner position="home_sidebar_2" />
                    </div>

                    {{-- Trending Now --}}
                    <div class="content-box">
                        <div class="sec-head">
                            <h3 class="sec-title">Trending Now</h3>
                        </div>
                        @foreach($sidebarTrendingArticles->slice(5)->take(4) as $fp)
                            <div class="list-post">
                                <a href="{{ route('frontend.article.show', $fp->slug) }}" class="list-post-img"
                                    style="width:70px;height:52px;">@if($fp->hasMedia('featured_image'))<img
                                    src="{{ $fp->getFirstMediaUrl('featured_image') }}">@endif</a>
                                <div>
                                    <p class="post-meta" style="margin-bottom:3px;">
                                        {{ $fp->published_at?->format('d M Y') }}</p><a
                                        href="{{ route('frontend.article.show', $fp->slug) }}" class="post-title"
                                        style="font-size:11px;display:block;">{{ $fp->translate()?->title }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>{{-- /sidebar --}}

        </div>{{-- /flex --}}

        {{-- ── PROFESSIONAL VIDEO GALLERY ── --}}
        @if($videos->isNotEmpty())
            @php $vGroups = $videos->chunk(5); @endphp
            <div x-data="{ 
                videoOpen: false, 
                videoUrl: '', 
                videoTitle: '',
                index: 0,
                loading: false,
                total: {{ $vGroups->count() }},
                openVideo(url, title) {
                    this.videoUrl = url;
                    this.videoTitle = title;
                    this.videoOpen = true;
                },
                next() {
                    if(this.loading) return;
                    this.loading = true;
                    setTimeout(() => { this.index = (this.index + 1) % this.total; this.loading = false; }, 600);
                },
                prev() {
                    if(this.loading) return;
                    this.loading = true;
                    setTimeout(() => { this.index = (this.index - 1 + this.total) % this.total; this.loading = false; }, 600);
                }
            }" @keydown.escape.window="videoOpen = false" class="content-box" style="margin-top:20px;margin-bottom:30px;position:relative;">
                
                {{-- Loading Overlay --}}
                <div x-show="loading" style="position:absolute;inset:0;background:rgba(255,255,255,0.8);z-index:100;">
                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
                        <div class="loading-spinner"></div>
                    </div>
                </div>

                <div class="sec-head">
                    <h3 class="sec-title">Originals & Interviews</h3>
                    <div class="nav-arrows">
                        <span @click="prev()">‹</span>
                        <span @click="next()">›</span>
                    </div>
                </div>

                @foreach($vGroups as $gIndex => $group)
                    <div x-show="index === {{ $gIndex }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        @php 
                            $vMain = $group->first();
                            $vList = $group->slice(1);
                        @endphp
                        <div class="home-video-grid" style="display:grid;grid-template-columns:1.8fr 1fr;gap:20px;">
                            {{-- Large Featured Video --}}
                            <div class="group cursor-pointer" @click="openVideo('{{ $vMain->embed_url }}', '{{ $vMain->title }}')">
                                <div style="position:relative;aspect-ratio:18/8.5;overflow:hidden;border-radius:4px;background:#000;">
                                    @if($vMain->hasMedia('thumbnail'))
                                        <img src="{{ $vMain->getFirstMediaUrl('thumbnail') }}" 
                                             style="width:100%;height:100%;object-fit:cover;transition:transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);opacity:0.9;"
                                             class="group-hover:scale-105 group-hover:opacity-100">
                                    @endif
                                    
                                    {{-- Play Button Overlay --}}
                                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                                        <div style="width:64px;height:64px;background:rgb(221 0 0 / 80%);border:2px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:all 0.3s; cursor: pointer;" class="group-hover:scale-110 group-hover:bg-[#e60000] group-hover:border-[#e60000]">
                                            <svg width="24" height="24" fill="#fff" viewBox="0 0 24 24">
                                                <polygon points="5 3 19 12 5 21 5 3" />
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Info Bar --}}
                                    <div style="position:absolute;bottom:0;left:0;right:0;padding:30px;background:linear-gradient(to top, rgba(0,0,0,0.9) 0%, transparent 100%);">
                                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                                            <span style="background:#e60000;color:#fff;font-size:9px;font-weight:900;text-transform:uppercase;padding:2px 8px;border-radius:2px;letter-spacing:0.1em;">Featured</span>
                                            <span style="color:rgba(255,255,255,0.7);font-size:11px;font-weight:600;">{{ $vMain->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <h4 style="font-size:24px;font-weight:800;color:#fff;line-height:1.2;transition:color 0.3s;" class="group-hover:text-[#e60000]">{{ $vMain->title }}</h4>
                                    </div>
                                </div>
                            </div>

                            {{-- List of 4 Videos --}}
                            <div class="home-video-list" style="display:flex;flex-direction:column;gap:15px;">
                                @foreach($vList as $v)
                                    <div class="group cursor-pointer flex gap-4 border-b border-neutral-100 pb-3 last:border-0 last:pb-0" @click="openVideo('{{ $v->embed_url }}', '{{ $v->title }}')">
                                        <div style="position:relative;width:120px;height:75px;flex-shrink:0;overflow:hidden;border-radius:4px;background:#000;">
                                            @if($v->hasMedia('thumbnail'))
                                                <img src="{{ $v->getFirstMediaUrl('thumbnail') }}" 
                                                     style="width:100%;height:100%;object-fit:cover;opacity:0.8;transition:all 0.3s;"
                                                     class="group-hover:opacity-100 group-hover:scale-110">
                                            @endif
                                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                                                <div style="width:28px;height:28px;background:rgb(221 0 0 / 80%);border-radius:50%;display:flex;align-items:center;justify-content:center;transition:all 0.3s; cursor: pointer;" class="group-hover:bg-[#e60000]">
                                                    <svg width="10" height="10" fill="#fff" viewBox="0 0 24 24">
                                                        <polygon points="5 3 19 12 5 21 5 3" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <p style="font-size:10px;font-weight:700;color:#999;text-transform:uppercase;margin-bottom:4px;">{{ $v->created_at->format('d M Y') }}</p>
                                            <h5 style="font-size:13px;font-weight:800;line-height:1.3;transition:color 0.3s;" class="group-hover:text-[#e60000]">{{ $v->title }}</h5>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Immersive Video Modal --}}
                <template x-teleport="body">
                    <div x-show="videoOpen" 
                         x-effect="if(videoOpen) document.body.style.overflow='hidden'; else document.body.style.overflow='auto'"
                         style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:1000000;background:rgba(0,0,0,0.7);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        
                        {{-- Close Button (Top Right) --}}
                        <button @click="videoOpen = false" style="position:absolute;top:40px;right:40px;background:white;border:none;color:black;cursor:pointer;width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 20px 40px rgba(0,0,0,0.4);transition:all 0.3s;z-index:1000001;" onmouseover="this.style.transform='rotate(90deg)'" onmouseout="this.style.transform='rotate(0deg)'">
                            <svg style="width:24px;height:24px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>

                        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);width:95%;max-width:1100px;z-index:1000002;"
                             x-show="videoOpen"
                             x-transition:enter="transition ease-out duration-500 delay-100"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100">
                            
                            {{-- Player Container --}}
                            <div style="width:100%;aspect-ratio:16/9;background:#000;box-shadow:0 60px 120px -20px rgba(0,0,0,0.9);border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.15);">
                                <template x-if="videoOpen">
                                    <iframe :src="videoUrl" style="width:100%;height:100%;" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                </template>
                            </div>

                            {{-- Info --}}
                            <div style="margin-top:30px;text-align:center;">
                                <span style="display:inline-block;background:#e60000;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;padding:4px 12px;letter-spacing:0.2em;border-radius:2px;margin-bottom:15px;box-shadow:0 5px 15px rgba(230,0,0,0.3);">Now Playing</span>
                                <h2 x-text="videoTitle" style="color:#fff;font-size:32px;font-weight:800;font-family:serif;letter-spacing:-0.02em;text-shadow:0 2px 10px rgba(0,0,0,0.5);"></h2>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        @endif

        {{-- ════ BOTTOM 3-COL GRID (cats 8+) ════ --}}
        @if($hc->count() > 8)
            <div style="margin-top:14px;display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                @foreach($hc->slice(8) as $bCat)
                    @php 
                        $bPs = $bCat->posts()->where('status', 'published')->whereNotIn('id', $eIds)->with(['translations', 'media', 'author'])->latest('published_at')->take(8)->get();
                        if ($bPs->isEmpty()) continue;
                        $bGroups = $bPs->chunk(4);
                    @endphp
                    <div class="content-box" style="position:relative;"
                        x-data="{ 
                            index: 0, 
                            loading: false, 
                            total: {{ $bGroups->count() }},
                            next() {
                                if(this.loading) return;
                                this.loading = true;
                                setTimeout(() => {
                                    this.index = (this.index + 1) % this.total;
                                    this.loading = false;
                                }, 700);
                            },
                            prev() {
                                if(this.loading) return;
                                this.loading = true;
                                setTimeout(() => {
                                    this.index = (this.index - 1 + this.total) % this.total;
                                    this.loading = false;
                                }, 700);
                            }
                        }">

                        {{-- Loading Overlay --}}
                        <div x-show="loading"
                            style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.85);z-index:10000;">
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
                                <div class="loading-spinner"></div>
                            </div>
                        </div>
                        <div class="sec-head">
                            <h3 class="sec-title">{{ $bCat->getTranslation('name', app()->getLocale()) }}</h3>
                            <div class="nav-arrows">
                                <span @click="prev()">‹</span>
                                <span @click="next()">›</span>
                            </div>
                        </div>

                        @foreach ($bGroups as $bgIndex => $bGroup)
                            <div x-show="index === {{ $bgIndex }}">
                                @php
                                    $bF = $bGroup->first();
                                    $bOthers = $bGroup->slice(1);
                                @endphp
                                @if ($bF)
                            <a href="{{ route('frontend.article.show', $bF->slug) }}" class="img-card"
                                style="display:block;height:130px;margin-bottom:8px;">
                                @if($bF->hasMedia('featured_image'))<img src="{{ $bF->getFirstMediaUrl('featured_image') }}"
                                style="width:100%;height:100%;object-fit:cover;">@else<div
                                style="width:100%;height:100%;background:#ddd;"></div>@endif
                                <span class="img-cat"
                                    style="font-size:7px;padding:2px 4px;">{{ $bCat->getTranslation('name', 'en') }}</span>
                                <span class="img-flash"><svg width="7" height="7" fill="#fff" viewBox="0 0 24 24">
                                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg></span>
                            </a>
                            <p class="post-meta" style="margin-bottom:3px;">{{ $bF->author?->name }} ·
                                {{ $bF->published_at?->format('d M Y') }}</p>
                            <a href="{{ route('frontend.article.show', $bF->slug) }}" class="post-title"
                                style="display:block;font-size:12px;margin-bottom:8px;">{{ $bF->translate()?->title }}</a>
                        @endif
                                @foreach ($bOthers as $bp)
                                    <div class="list-post">
                                        <a href="{{ route('frontend.article.show', $bp->slug) }}" class="list-post-img"
                                            style="width:70px;height:52px;">
                                            @if ($bp->hasMedia('featured_image'))
                                                <img src="{{ $bp->getFirstMediaUrl('featured_image') }}">
                                            @endif
                                        </a>
                                        <div>
                                            <p class="post-meta" style="margin-bottom:2px;">
                                                {{ $bp->published_at?->format('d M Y') }}</p>
                                            <a href="{{ route('frontend.article.show', $bp->slug) }}" class="post-title"
                                                style="font-size:11px;display:block;">{{ $bp->translate()?->title }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

    </div>{{-- /wrap --}}
</x-frontend-layout>