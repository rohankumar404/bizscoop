<x-frontend-layout>
    @push('styles')
        {{-- Custom homepage styles can go here --}}
    @endpush

    <div class="wrap" style="padding-top:14px;padding-bottom:28px;">

        {{-- ═══════════════════════════════════════════
        PROFESSIONAL HERO SLIDER — 4 BOXES
        Box1: Featured (large left)
        Box2: Latest news (top-right)
        Box3: Business (bottom-right top)
        Box4: Economy (bottom-right bottom)
        ═══════════════════════════════════════════ --}}
        @push('styles')
        <style>
            /* ── Hero Grid Layout ── */
            .hero-grid {
                display: grid;
                grid-template-columns: 3fr 2fr;
                grid-template-rows: 1fr 1fr;
                gap: 4px;
                height: 520px;
            }
            .hero-box-main { grid-row: 1 / 3; }
            .hero-box { position: relative; overflow: hidden; background: #111; }

            /* ── Slide Item ── */
            .hero-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 0.75s ease; z-index: 1; }
            .hero-slide.active { opacity: 1; z-index: 2; }

            /* ── Slide Image ── */
            .hero-slide img {
                width: 100%; height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 6s ease;
            }
            .hero-slide.active img { transform: scale(1.04); }

            /* ── Gradient Overlay ── */
            .hero-overlay-strong {
                position: absolute; inset: 0;
                background: linear-gradient(to top, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.3) 45%, transparent 100%);
            }
            .hero-overlay-med {
                position: absolute; inset: 0;
                background: linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.2) 55%, transparent 100%);
            }

            /* ── Category Badge ── */
            .hero-badge {
                position: absolute; top: 12px; left: 12px; z-index: 5;
                font-size: 9px; font-weight: 900; text-transform: uppercase;
                letter-spacing: 0.12em; color: #fff;
                background: #c00; padding: 3px 9px;
                line-height: 1.5;
            }

            /* ── Featured Star Badge ── */
            .hero-featured-badge {
                position: absolute; top: 12px; right: 12px; z-index: 5;
                background: rgba(0,0,0,0.75); border: 1px solid rgba(255,255,255,0.15);
                padding: 4px 8px; display: flex; align-items: center; gap: 4px;
                font-size: 8px; font-weight: 900; color: #f0b429; text-transform: uppercase; letter-spacing: 0.1em;
            }

            /* ── Slide Caption: Box 1 (large) ── */
            .hero-caption-main {
                position: absolute; bottom: 0; left: 0; right: 0;
                padding: 20px 18px 56px; z-index: 5;
            }
            .hero-caption-main .meta {
                font-size: 9px; font-weight: 700; color: rgba(255,255,255,0.6);
                text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 7px;
            }
            .hero-caption-main h2 {
                font-size: 18px; font-weight: 900; color: #fff; line-height: 1.28;
                text-shadow: 0 2px 8px rgba(0,0,0,0.5); margin: 0 0 7px;
                display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
            }
            .hero-caption-main p {
                font-size: 11px; color: rgba(255,255,255,0.65); line-height: 1.5;
                display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin: 0;
            }

            /* ── Slide Caption: Small boxes ── */
            .hero-caption-sm {
                position: absolute; bottom: 0; left: 0; right: 0;
                padding: 10px 11px 42px; z-index: 5;
            }
            .hero-caption-sm .meta {
                font-size: 8px; color: rgba(255,255,255,0.55); letter-spacing: 0.05em;
                font-weight: 600; margin-bottom: 4px;
            }
            .hero-caption-sm h3 {
                font-size: 12px; font-weight: 800; color: #fff; line-height: 1.3;
                display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; margin: 0;
            }

            /* ── Controls Row ── */
            .hero-controls {
                position: absolute; bottom: 0; left: 0; right: 0; z-index: 10;
                display: flex; align-items: center; justify-content: space-between;
                padding: 8px 10px;
                background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 100%);
            }

            /* ── Dot Indicators ── */
            .hero-dots { display: flex; gap: 4px; align-items: center; }
            .hero-dot {
                width: 6px; height: 6px; border-radius: 50%;
                border: none; cursor: pointer; padding: 0;
                background: rgba(255,255,255,0.35);
                transition: all 0.3s;
            }
            .hero-dot.active { background: #fff; width: 18px; border-radius: 3px; }

            /* ── Arrow Buttons ── */
            .hero-arrow {
                background: rgba(0,0,0,0.5); color: #fff; border: none;
                cursor: pointer; display: flex; align-items: center; justify-content: center;
                transition: background 0.2s;
            }
            .hero-arrow:hover { background: rgba(0,0,0,0.85); }
            .hero-arrow-side {
                position: absolute; top: 50%; transform: translateY(-50%); z-index: 10;
                width: 30px; height: 52px; font-size: 20px;
            }
            .hero-arrow-side.left { left: 0; }
            .hero-arrow-side.right { right: 0; }
            .hero-arrow-sm { width: 20px; height: 20px; font-size: 13px; }

            /* ── Progress Bar ── */
            .hero-progress {
                position: absolute; bottom: 0; left: 0; height: 3px; z-index: 15;
                background: #c00;
                transition: width 0.1s linear;
            }

            /* ── Section Label (small boxes) ── */
            .hero-section-label {
                position: absolute; top: 0; left: 0; right: 0; z-index: 6;
                padding: 7px 10px;
                background: linear-gradient(to bottom, rgba(0,0,0,0.6) 0%, transparent 100%);
                font-size: 8px; font-weight: 900; text-transform: uppercase;
                letter-spacing: 0.15em; color: rgba(255,255,255,0.8);
                display: flex; align-items: center; gap: 5px;
            }
            .hero-section-label span.dot { width: 5px; height: 5px; border-radius: 50%; background: #c00; display: inline-block; }

            /* ── No Posts Fallback ── */
            .hero-empty {
                position: absolute; inset: 0; display: flex; flex-direction: column;
                align-items: center; justify-content: center;
                background: #1a1a1a; color: rgba(255,255,255,0.25);
            }

            /* ── Image Fallback (no image posts) ── */
            .hero-img-fallback {
                width: 100%; height: 100%;
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
                display: flex; align-items: center; justify-content: center;
            }

            @media (max-width: 768px) {
                .hero-grid { grid-template-columns: 1fr; grid-template-rows: auto; height: auto; }
                .hero-box-main { grid-row: auto; height: 280px; }
                .hero-box { height: 200px; }
            }
        </style>
        @endpush

        @php
            $hero1 = $heroFeatured->toJson();
            $hero2 = $heroLatest->toJson();
            $hero3 = $heroBusiness->toJson();
            $hero4 = $heroEconomy->toJson();
        @endphp

        <div x-data="heroSlider({
                box1Posts: {{ $hero1 }},
                box2Posts: {{ $hero2 }},
                box3Posts: {{ $hero3 }},
                box4Posts: {{ $hero4 }},
                box1Speed: {{ max(3000, (int) $heroSettings['box1_speed']) }},
                box2Speed: {{ max(3000, (int) $heroSettings['box2_speed']) }},
                box3Speed: {{ max(3000, (int) $heroSettings['box3_speed']) }},
                box4Speed: {{ max(3000, (int) $heroSettings['box4_speed']) }},
            })"
             x-init="init()"
             style="margin-bottom:16px;">

            <div class="hero-grid">

                {{-- ══════ BOX 1 — FEATURED (large left) ══════ --}}
                <div class="hero-box hero-box-main"
                     @mouseenter="box1Pause()" @mouseleave="box1Resume()">

                    <template x-if="box1Posts.length > 0">
                        <div style="position:absolute;inset:0;">
                            <template x-for="(post, idx) in box1Posts" :key="'b1-'+post.id">
                                <a :href="'/article/'+post.slug"
                                   class="hero-slide"
                                   :class="{ active: idx === box1Idx }"
                                   style="display:block;height:100%;">
                                    <template x-if="post.image">
                                        <img :src="post.image" :alt="post.title" loading="lazy">
                                    </template>
                                    <template x-if="!post.image">
                                        <div class="hero-img-fallback">
                                            <svg width="48" height="48" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m3 9 4-4 4 4 4-4 4 4"/><circle cx="8.5" cy="14.5" r="1.5"/></svg>
                                        </div>
                                    </template>
                                    <div class="hero-overlay-strong"></div>

                                    {{-- Category Badge --}}
                                    <div class="hero-badge" x-text="post.category" x-show="post.category"></div>

                                    {{-- Featured star --}}
                                    <div class="hero-featured-badge">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="#f0b429"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        Featured
                                    </div>

                                    {{-- Caption --}}
                                    <div class="hero-caption-main">
                                        <p class="meta" x-text="post.author + ' · ' + post.date"></p>
                                        <h2 x-text="post.title"></h2>
                                        <p x-text="post.excerpt" x-show="post.excerpt"></p>
                                    </div>
                                </a>
                            </template>

                            {{-- Side Arrows --}}
                            <button class="hero-arrow hero-arrow-side left" @click.prevent="box1Prev()" x-show="box1Posts.length > 1">‹</button>
                            <button class="hero-arrow hero-arrow-side right" @click.prevent="box1Next()" x-show="box1Posts.length > 1">›</button>

                            {{-- Controls: Dots --}}
                            <div class="hero-controls">
                                <div class="hero-dots">
                                    <template x-for="(p, i) in box1Posts" :key="'d1-'+i">
                                        <button class="hero-dot" :class="{ active: i === box1Idx }" @click="box1GoTo(i)"></button>
                                    </template>
                                </div>
                                <span style="font-size:9px;color:rgba(255,255,255,0.45);font-weight:700;letter-spacing:0.05em;" x-text="(box1Idx+1)+' / '+box1Posts.length"></span>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="hero-progress" :style="'width:'+box1Progress+'%'"></div>
                        </div>
                    </template>

                    <template x-if="box1Posts.length === 0">
                        <div class="hero-empty">
                            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <p style="font-size:11px;margin-top:8px;">No featured articles</p>
                        </div>
                    </template>
                </div>

                {{-- ══════ BOX 2 — LATEST NEWS (top right) ══════ --}}
                <div class="hero-box"
                     @mouseenter="box2Pause()" @mouseleave="box2Resume()">

                    <template x-if="box2Posts.length > 0">
                        <div style="position:absolute;inset:0;">
                            {{-- Section label --}}
                            <div class="hero-section-label">
                                <span class="dot"></span> Latest News
                            </div>

                            <template x-for="(post, idx) in box2Posts" :key="'b2-'+post.id">
                                <a :href="'/article/'+post.slug"
                                   class="hero-slide"
                                   :class="{ active: idx === box2Idx }"
                                   style="display:block;height:100%;">
                                    <template x-if="post.image">
                                        <img :src="post.image" :alt="post.title" loading="lazy">
                                    </template>
                                    <template x-if="!post.image">
                                        <div class="hero-img-fallback"></div>
                                    </template>
                                    <div class="hero-overlay-med"></div>
                                    <div class="hero-caption-sm">
                                        <p class="meta" x-text="post.category + ' · ' + post.date"></p>
                                        <h3 x-text="post.title"></h3>
                                    </div>
                                </a>
                            </template>

                            <div class="hero-controls">
                                <div class="hero-dots">
                                    <template x-for="(p, i) in box2Posts" :key="'d2-'+i">
                                        <button class="hero-dot" :class="{ active: i === box2Idx }" @click="box2GoTo(i)"></button>
                                    </template>
                                </div>
                                <div style="display:flex;gap:3px;">
                                    <button class="hero-arrow hero-arrow-sm" @click.prevent="box2Prev()" x-show="box2Posts.length > 1">‹</button>
                                    <button class="hero-arrow hero-arrow-sm" @click.prevent="box2Next()" x-show="box2Posts.length > 1">›</button>
                                </div>
                            </div>
                            <div class="hero-progress" :style="'width:'+box2Progress+'%'"></div>
                        </div>
                    </template>

                    <template x-if="box2Posts.length === 0">
                        <div class="hero-empty"><p style="font-size:11px;">No articles</p></div>
                    </template>
                </div>

                {{-- ══════ BOX 3 — BUSINESS (bottom right, top) ══════ --}}
                <div class="hero-box"
                     @mouseenter="box3Pause()" @mouseleave="box3Resume()">

                    <template x-if="box3Posts.length > 0">
                        <div style="position:absolute;inset:0;">
                            <div class="hero-section-label">
                                <span class="dot"></span> Business
                                <a href="/section/{{ $businessCatSlug }}" style="margin-left:auto;font-size:7px;color:rgba(255,255,255,0.5);text-decoration:none;" @click.stop>More »</a>
                            </div>

                            <template x-for="(post, idx) in box3Posts" :key="'b3-'+post.id">
                                <a :href="'/article/'+post.slug"
                                   class="hero-slide"
                                   :class="{ active: idx === box3Idx }"
                                   style="display:block;height:100%;">
                                    <template x-if="post.image">
                                        <img :src="post.image" :alt="post.title" loading="lazy">
                                    </template>
                                    <template x-if="!post.image">
                                        <div class="hero-img-fallback"></div>
                                    </template>
                                    <div class="hero-overlay-med"></div>
                                    <div class="hero-caption-sm">
                                        <p class="meta" x-text="post.date"></p>
                                        <h3 x-text="post.title"></h3>
                                    </div>
                                </a>
                            </template>

                            <div class="hero-controls">
                                <div class="hero-dots">
                                    <template x-for="(p, i) in box3Posts" :key="'d3-'+i">
                                        <button class="hero-dot" :class="{ active: i === box3Idx }" @click="box3GoTo(i)"></button>
                                    </template>
                                </div>
                                <div style="display:flex;gap:3px;">
                                    <button class="hero-arrow hero-arrow-sm" @click.prevent="box3Prev()" x-show="box3Posts.length > 1">‹</button>
                                    <button class="hero-arrow hero-arrow-sm" @click.prevent="box3Next()" x-show="box3Posts.length > 1">›</button>
                                </div>
                            </div>
                            <div class="hero-progress" :style="'width:'+box3Progress+'%'"></div>
                        </div>
                    </template>

                    <template x-if="box3Posts.length === 0">
                        <div class="hero-empty"><p style="font-size:11px;">No business articles</p></div>
                    </template>
                </div>

                {{-- ══════ BOX 4 — ECONOMY (bottom right, bottom) ══════ --}}
                <div class="hero-box"
                     @mouseenter="box4Pause()" @mouseleave="box4Resume()">

                    <template x-if="box4Posts.length > 0">
                        <div style="position:absolute;inset:0;">
                            <div class="hero-section-label">
                                <span class="dot" style="background:#f0b429;"></span> Economy
                                <a href="/section/{{ $economyCatSlug }}" style="margin-left:auto;font-size:7px;color:rgba(255,255,255,0.5);text-decoration:none;" @click.stop>More »</a>
                            </div>

                            <template x-for="(post, idx) in box4Posts" :key="'b4-'+post.id">
                                <a :href="'/article/'+post.slug"
                                   class="hero-slide"
                                   :class="{ active: idx === box4Idx }"
                                   style="display:block;height:100%;">
                                    <template x-if="post.image">
                                        <img :src="post.image" :alt="post.title" loading="lazy">
                                    </template>
                                    <template x-if="!post.image">
                                        <div class="hero-img-fallback"></div>
                                    </template>
                                    <div class="hero-overlay-med"></div>
                                    <div class="hero-caption-sm">
                                        <p class="meta" x-text="post.date"></p>
                                        <h3 x-text="post.title"></h3>
                                    </div>
                                </a>
                            </template>

                            <div class="hero-controls">
                                <div class="hero-dots">
                                    <template x-for="(p, i) in box4Posts" :key="'d4-'+i">
                                        <button class="hero-dot" :class="{ active: i === box4Idx }" @click="box4GoTo(i)"></button>
                                    </template>
                                </div>
                                <div style="display:flex;gap:3px;">
                                    <button class="hero-arrow hero-arrow-sm" @click.prevent="box4Prev()" x-show="box4Posts.length > 1">‹</button>
                                    <button class="hero-arrow hero-arrow-sm" @click.prevent="box4Next()" x-show="box4Posts.length > 1">›</button>
                                </div>
                            </div>
                            <div class="hero-progress" :style="'width:'+box4Progress+'%'"></div>
                        </div>
                    </template>

                    <template x-if="box4Posts.length === 0">
                        <div class="hero-empty"><p style="font-size:11px;">No economy articles</p></div>
                    </template>
                </div>

            </div>{{-- /hero-grid --}}
        </div>{{-- /x-data --}}

        {{-- ── Hero Slider Alpine.js Component ─────────────────── --}}
        @push('scripts')
        <script>
        function heroSlider(cfg) {
            const TICK = 100; // progress bar tick ms

            function makeBox(posts, speed) {
                return {
                    posts: posts || [],
                    idx: 0,
                    paused: false,
                    progress: 0,
                    elapsed: 0,
                    timer: null,
                    speed: speed,

                    get total() { return this.posts.length; },

                    start() {
                        if (this.total < 2) { this.progress = 100; return; }
                        this.timer = setInterval(() => {
                            if (this.paused) return;
                            this.elapsed += TICK;
                            this.progress = Math.min(100, (this.elapsed / this.speed) * 100);
                            if (this.elapsed >= this.speed) {
                                this.idx = (this.idx + 1) % this.total;
                                this.elapsed = 0;
                                this.progress = 0;
                            }
                        }, TICK);
                    },
                    stop()   { clearInterval(this.timer); },
                    pause()  { this.paused = true; },
                    resume() { this.paused = false; },

                    prev() {
                        this.idx = (this.idx - 1 + this.total) % this.total;
                        this.elapsed = 0; this.progress = 0;
                    },
                    next() {
                        this.idx = (this.idx + 1) % this.total;
                        this.elapsed = 0; this.progress = 0;
                    },
                    goTo(i) {
                        this.idx = i;
                        this.elapsed = 0; this.progress = 0;
                    },
                };
            }

            return {
                box1Posts: cfg.box1Posts || [],
                box2Posts: cfg.box2Posts || [],
                box3Posts: cfg.box3Posts || [],
                box4Posts: cfg.box4Posts || [],

                // Indexes & progress
                box1Idx: 0, box2Idx: 0, box3Idx: 0, box4Idx: 0,
                box1Progress: 0, box2Progress: 0, box3Progress: 0, box4Progress: 0,

                _b1: null, _b2: null, _b3: null, _b4: null,

                box1Prev()   { this._b1.prev(); this.box1Idx = this._b1.idx; this.box1Progress = this._b1.progress; },
                box1Next()   { this._b1.next(); this.box1Idx = this._b1.idx; this.box1Progress = this._b1.progress; },
                box1GoTo(i)  { this._b1.goTo(i); this.box1Idx = i; this.box1Progress = 0; },
                box1Pause()  { this._b1.pause(); },
                box1Resume() { this._b1.resume(); },

                box2Prev()   { this._b2.prev(); this.box2Idx = this._b2.idx; this.box2Progress = this._b2.progress; },
                box2Next()   { this._b2.next(); this.box2Idx = this._b2.idx; this.box2Progress = this._b2.progress; },
                box2GoTo(i)  { this._b2.goTo(i); this.box2Idx = i; this.box2Progress = 0; },
                box2Pause()  { this._b2.pause(); },
                box2Resume() { this._b2.resume(); },

                box3Prev()   { this._b3.prev(); this.box3Idx = this._b3.idx; this.box3Progress = this._b3.progress; },
                box3Next()   { this._b3.next(); this.box3Idx = this._b3.idx; this.box3Progress = this._b3.progress; },
                box3GoTo(i)  { this._b3.goTo(i); this.box3Idx = i; this.box3Progress = 0; },
                box3Pause()  { this._b3.pause(); },
                box3Resume() { this._b3.resume(); },

                box4Prev()   { this._b4.prev(); this.box4Idx = this._b4.idx; this.box4Progress = this._b4.progress; },
                box4Next()   { this._b4.next(); this.box4Idx = this._b4.idx; this.box4Progress = this._b4.progress; },
                box4GoTo(i)  { this._b4.goTo(i); this.box4Idx = i; this.box4Progress = 0; },
                box4Pause()  { this._b4.pause(); },
                box4Resume() { this._b4.resume(); },

                init() {
                    const self = this;

                    this._b1 = makeBox(this.box1Posts, cfg.box1Speed);
                    this._b2 = makeBox(this.box2Posts, cfg.box2Speed);
                    this._b3 = makeBox(this.box3Posts, cfg.box3Speed);
                    this._b4 = makeBox(this.box4Posts, cfg.box4Speed);

                    // Sync reactive idx & progress from internal state
                    setInterval(() => {
                        self.box1Idx = self._b1.idx; self.box1Progress = self._b1.progress;
                        self.box2Idx = self._b2.idx; self.box2Progress = self._b2.progress;
                        self.box3Idx = self._b3.idx; self.box3Progress = self._b3.progress;
                        self.box4Idx = self._b4.idx; self.box4Progress = self._b4.progress;
                    }, TICK);

                    this._b1.start();
                    this._b2.start();
                    this._b3.start();
                    this._b4.start();
                }
            };
        }
        </script>
        @endpush
        {{-- ── MAIN FLEX LAYOUT (below hero) ── --}}
        <div class="flex gap-5 home-main-layout" style="margin-top:14px;">

            {{-- ════ MAIN COLUMN ════ --}}
            <div class="home-main-column" style="flex:1;min-width:0;">
                @php $eIds = [];
                $hc = $headerCategories->values(); @endphp

                {{-- ── MACRO: fetch posts helper ── --}}
                @php
                    $cposts = function ($cat, $n = 10) use ($eIds) {
                        $catIds = array_merge([$cat->id], $cat->children->where('is_active', true)->pluck('id')->toArray());
                        return \App\Models\Post::whereIn('category_id', $catIds)
                            ->where('status', 'published')
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
                                                style="display:block;height:220px;margin-bottom:8px;">
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
                                            <a href="{{ route('frontend.article.show', $pf->slug) }}" class="post-title resp-main"
                                                style="display:block;font-size:15px;font-weight:600;margin-bottom:12px;line-height:1.35;overflow:hidden;">{{ $pf->translate()?->title }}</a>

                                            @if ($isBusiness)
                                                {{-- 2x2 Grid for Business --}}
                                                <div class="home-two-grid keep-2-col-mobile" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                                    @foreach ($others as $lp)
                                                        <div class="group">
                                                            <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                class="img-card"
                                                                style="display:block;height:100px;margin-bottom:5px;">
                                                                @if ($lp->hasMedia('featured_image'))
                                                                    <img src="{{ $lp->getFirstMediaUrl('featured_image') }}"
                                                                        style="width:100%;height:100%;object-fit:cover;">
                                                                @endif
                                                            </a>
                                                            <p class="post-meta" style="margin-bottom:2px;font-size:8px;">{{ $lp->published_at?->format('d M Y') }}</p>
                                                            <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                class="post-title resp-lst"
                                                                style="display:block;font-size:14px;font-weight:600;line-height:1.2;height:5px;overflow:hidden;">{{ Str::limit($lp->translate()?->title, 60) }}</a>
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
                                                                style="display:block;font-size:14px;line-height:1.3;">{{ Str::limit($lp->translate()?->title, 60) }}</a>
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
                                                    style="display:block;height:220px;margin-bottom:8px;">
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
                                                    style="display:block;font-size:14px;font-weight:600;margin-bottom:5px;line-height:1.35;">{{ $pf->translate()?->title }}</a>
                                                <p class="post-excerpt"
                                                    style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                                    {{ $pf->translate()?->excerpt }}</p>
                                            </div>
                                            <div class="home-two-grid keep-2-col-mobile" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                                @foreach ($others as $lp)
                                                    <div class="group">
                                                        <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                            class="img-card"
                                                            style="display:block;height:90px;margin-bottom:5px;">
                                                            @if ($lp->hasMedia('featured_image'))
                                                                <img src="{{ $lp->getFirstMediaUrl('featured_image') }}"
                                                                    style="width:100%;height:100%;object-fit:cover;">
                                                            @endif
                                                        </a>
                                                        <p class="post-meta" style="margin-bottom:2px;font-size:9px;">
                                                            {{ $lp->published_at?->format('d M Y') }}</p>
                                                        <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                            class="post-title resp-lst"
                                                            style="display:block;font-size:14px;font-weight:600;line-height:1.3;overflow:hidden;">{{ Str::limit($lp->translate()?->title, 50) }}</a>
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
                                    style="display:block;height:185px;margin-bottom:8px;">
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
                                    style="display:block;font-size:14px;font-weight:600;margin-bottom:8px;line-height:1.35;">{{ Str::limit($pf->translate()?->title, 50) }}</a>
                                                <div class="home-list-grid keep-2-col-mobile" style="display:grid;grid-template-columns:repeat(2, 1fr);gap:10px;margin-top:12px;border-top:1px solid #f0f0f0;padding-top:12px;">
                                                    @foreach ($others as $lp)
                                                        <div>
                                                            <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                class="img-card"
                                                                style="display:block;height:95px;margin-bottom:5px;">
                                                                @if ($lp->hasMedia('featured_image'))
                                                                    <img src="{{ $lp->getFirstMediaUrl('featured_image') }}"
                                                                        style="width:100%;height:100%;object-fit:cover;">
                                                                @endif
                                                            </a>
                                                            <p class="post-meta" style="margin-bottom:2px;font-size:9px;">
                                                                {{ $lp->published_at?->format('d M Y') }}</p>
                                                            <a href="{{ route('frontend.article.show', $lp->slug) }}"
                                                                class="post-title resp-lst"
                                                                style="display:block;font-size:14px;font-weight:600;line-height:1.25;overflow:hidden;">{{ Str::limit($lp->translate()?->title, 40) }}</a>
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
                                                        class="img-card" style="display:block;height:220px;margin-bottom:8px;">
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
                                                        style="display:block;font-size:14px;font-weight:600;margin-bottom:5px;line-height:1.35;">{{ $pf->translate()?->title }}</a>
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
                                                                    style="display:block;font-size:14px;line-height:1.3;">{{ $lp->translate()?->title }}</a>
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

                {{-- ── EXTRA CATEGORIES (cats 7+): Rendered in 2-col grid rows inside main column ── --}}
                @php $extraCats = $hc->slice(7)->values(); @endphp
                @foreach($extraCats->chunk(2) as $chunk)
                    @php
                        $chunkItems = [];
                        foreach ($chunk as $eCat) {
                            $ePs = $cposts($eCat, 10);
                            if ($ePs->isNotEmpty()) {
                                $chunkItems[] = ['cat' => $eCat, 'ps' => $ePs, 'groups' => $ePs->chunk(5)];
                            }
                        }
                    @endphp
                    @if(count($chunkItems) > 0)
                        <div style="display:grid;grid-template-columns:{{ count($chunkItems) === 1 ? '1fr' : '1fr 1fr' }};gap:10px;margin-bottom:14px;">
                            @foreach($chunkItems as $item)
                                @php $eCat = $item['cat']; $ePs = $item['ps']; $eGroups = $item['groups']; @endphp
                                <div class="content-box home-content-box" style="position:relative;"
                                    x-data="{ index: 0, loading: false, total: {{ $eGroups->count() }}, next() { if(this.loading) return; this.loading=true; setTimeout(()=>{this.index=(this.index+1)%this.total;this.loading=false;},700); }, prev() { if(this.loading) return; this.loading=true; setTimeout(()=>{this.index=(this.index-1+this.total)%this.total;this.loading=false;},700); } }">
                                    <div x-show="loading" style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.85);z-index:10000;">
                                        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);"><div class="loading-spinner"></div></div>
                                    </div>
                                    <div class="sec-head">
                                        <h3 class="sec-title">{{ $eCat->getTranslation('name', app()->getLocale()) }}</h3>
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <a href="{{ route('frontend.category.show', $eCat->slug) }}" class="more-link">More »</a>
                                            <div class="nav-arrows">
                                                <span @click="prev()">‹</span>
                                                <span @click="next()">›</span>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($eGroups as $egIndex => $eGroup)
                                        <div x-show="index === {{ $egIndex }}">
                                            @php $ePf = $eGroup->first(); $eOthers = $eGroup->slice(1); @endphp
                                            @if($ePf)
                                                <a href="{{ route('frontend.article.show', $ePf->slug) }}" class="img-card" style="display:block;height:185px;margin-bottom:8px;">
                                                    @if($ePf->hasMedia('featured_image'))<img src="{{ $ePf->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;">@else<div style="width:100%;height:100%;background:#ccc;"></div>@endif
                                                    <span class="img-cat">{{ $ePf->category?->getTranslation('name', 'en') }}</span>
                                                    <span class="img-flash"><svg width="8" height="8" fill="#fff" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
                                                </a>
                                                <p class="post-meta" style="margin-bottom:4px;">{{ $ePf->author?->name }} · {{ $ePf->published_at?->format('d M Y') }}</p>
                                                <a href="{{ route('frontend.article.show', $ePf->slug) }}" class="post-title" style="display:block;font-size:14px;font-weight:600;margin-bottom:8px;line-height:1.35;">{{ Str::limit($ePf->translate()?->title, 50) }}</a>
                                                <div style="display:grid;grid-template-columns:repeat(2, 1fr);gap:10px;margin-top:12px;border-top:1px solid #f0f0f0;padding-top:12px;">
                                                    @foreach($eOthers as $eLp)
                                                        <div>
                                                            <a href="{{ route('frontend.article.show', $eLp->slug) }}" class="img-card" style="display:block;height:95px;margin-bottom:5px;">
                                                                @if($eLp->hasMedia('featured_image'))<img src="{{ $eLp->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;">@endif
                                                            </a>
                                                            <p class="post-meta" style="margin-bottom:2px;font-size:9px;">{{ $eLp->published_at?->format('d M Y') }}</p>
                                                            <a href="{{ route('frontend.article.show', $eLp->slug) }}" class="post-title" style="display:block;font-size:14px;font-weight:600;line-height:1.25;height:34px;overflow:hidden;">{{ Str::limit($eLp->translate()?->title, 40) }}</a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

            </div>{{-- /main col --}}

            {{-- ════ SIDEBAR ════ --}}
            <div class="home-sidebar" style="width:300px;flex-shrink:0;">
                <div class="sidebar-sticky" style="position:sticky;top:60px;">

                    {{-- Dynamic Ad --}}
                    <div style="margin-bottom:12px;">
                        <x-ad-banner position="home_sidebar" />
                    </div>

                    {{-- Newsletter --}}
                    <div style="background:#000;padding:16px;margin-bottom:12px;">
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
                                        style="background:#000;color:#fff;font-size:8px;font-weight:900;text-transform:uppercase;padding:1px 5px;display:inline-block;margin-bottom:3px;">{{ $tp->category?->getTranslation('name', 'en') }}</span>
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
                                        <div style="width:64px;height:64px;background:rgba(17, 17, 17, 0.75);border:2px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:all 0.3s; cursor: pointer;" class="group-hover:scale-110 group-hover:bg-[#000] group-hover:border-[#000]">
                                            <svg width="24" height="24" fill="#fff" viewBox="0 0 24 24">
                                                <polygon points="5 3 19 12 5 21 5 3" />
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Info Bar --}}
                                    <div style="position:absolute;bottom:0;left:0;right:0;padding:30px;background:linear-gradient(to top, rgba(0,0,0,0.9) 0%, transparent 100%);">
                                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                                            <span style="background:#000;color:#fff;font-size:9px;font-weight:900;text-transform:uppercase;padding:2px 8px;border-radius:2px;letter-spacing:0.1em;">Featured</span>
                                            <span style="color:rgba(255,255,255,0.7);font-size:11px;font-weight:600;">{{ $vMain->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <h4 style="font-size:24px;font-weight:800;color:#fff;line-height:1.2;transition:color 0.3s;" class="group-hover:text-[#000]">{{ $vMain->title }}</h4>
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
                                                <div style="width:28px;height:28px;background:rgba(17, 17, 17, 0.75);border-radius:50%;display:flex;align-items:center;justify-content:center;transition:all 0.3s; cursor: pointer;" class="group-hover:bg-[#000]">
                                                    <svg width="10" height="10" fill="#fff" viewBox="0 0 24 24">
                                                        <polygon points="5 3 19 12 5 21 5 3" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <p style="font-size:10px;font-weight:700;color:#999;text-transform:uppercase;margin-bottom:4px;">{{ $v->created_at->format('d M Y') }}</p>
                                            <h5 style="font-size:13px;font-weight:800;line-height:1.3;transition:color 0.3s;" class="group-hover:text-[#000]">{{ $v->title }}</h5>
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
                                <span style="display:inline-block;background:#000;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;padding:4px 12px;letter-spacing:0.2em;border-radius:2px;margin-bottom:15px;box-shadow:0 5px 15px rgba(0,0,0,0.3);">Now Playing</span>
                                <h2 x-text="videoTitle" style="color:#fff;font-size:32px;font-weight:800;font-family:serif;letter-spacing:-0.02em;text-shadow:0 2px 10px rgba(0,0,0,0.5);"></h2>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        @endif

        {{-- Extra categories (7+) are now rendered inside the main column above --}}

    </div>{{-- /wrap --}}
</x-frontend-layout>