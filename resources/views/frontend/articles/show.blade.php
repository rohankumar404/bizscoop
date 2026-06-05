@php
    $translation = $post->translate();
    $seo         = $post->seoMeta;
@endphp

<x-frontend-layout
    :title="$seo?->meta_title ?? $translation->title"
    :description="$seo?->meta_description ?? $translation->excerpt"
    :ogImage="$post->getFirstMediaUrl('featured_image')"
>
    {{-- Reading Progress Bar --}}
    <div style="position:fixed;top:0;left:0;width:100%;height:3px;z-index:9999;pointer-events:none;">
        <div id="readingProgress" style="height:100%;background:#000;width:0%;transition:width 0.1s ease;box-shadow:0 0 10px rgba(0,0,0,0.5);"></div>
    </div>

    <div class="wrap" style="padding-top:28px;padding-bottom:50px;">
        <div class="flex gap-8 home-main-layout">

            {{-- ─────────────── MAIN ARTICLE ─────────────── --}}
            <div class="home-main-column" style="flex:1;min-width:0;">
                <article style="background:#fff;box-shadow:0 10px 30px rgba(0,0,0,0.05);border-radius:8px;overflow:hidden;">
                    
                    {{-- Article Header (Cinematic) --}}
                    <div style="padding:40px 40px 20px 40px;background:linear-gradient(to bottom, #fafafa, #fff);">
                        {{-- Breadcrumb --}}
                        <nav style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:#000;margin-bottom:20px;display:flex;align-items:center;gap:8px;">
                            <a href="{{ route('frontend.home') }}" style="color:inherit;text-decoration:none;opacity:0.7;transition:opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">Home</a>
                            <span style="color:#ddd;">/</span>
                            <a href="{{ route('frontend.category.show', $post->category->slug) }}" style="color:inherit;text-decoration:none;opacity:0.7;transition:opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
                                {{ $post->category->getTranslation('name', app()->getLocale()) }}
                            </a>
                        </nav>

                        <h1 style="font-family:'Merriweather',serif;font-size:42px;font-weight:900;color:#111;line-height:1.15;margin-bottom:24px;letter-spacing:-0.03em;">
                            {{ $translation->title }}
                        </h1>

                        {{-- Premium Meta Bar --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;padding-top:20px;border-top:1px solid #f0f0f0;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:44px;height:44px;border-radius:12px;background:#f0f0f0;overflow:hidden;flex-shrink:0;box-shadow:0 4px 10px rgba(0,0,0,0.05);">
                                    @if($post->author->hasMedia('avatar'))
                                        <img src="{{ $post->author->getFirstMediaUrl('avatar') }}" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:900;color:#ccc;">
                                            {{ strtoupper(substr($post->author->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p style="font-size:13px;font-weight:800;color:#111;margin:0;">{{ $post->author->name }}</p>
                                    <p style="font-size:11px;color:#888;margin:0;display:flex;align-items:center;gap:6px;">
                                        <span>{{ $post->published_at->format('M d, Y') }}</span>
                                        <span style="opacity:0.3;">|</span>
                                        <span style="display:flex;align-items:center;gap:3px;">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                            {{ $post->reading_time ?? '3' }} min read
                                        </span>
                                    </p>
                                </div>
                            </div>

                            {{-- Social Float --}}
                            <div style="display:flex;gap:8px;">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                                   style="width:32px;height:32px;background:#111;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;text-decoration:none;border-radius:50%;transition:all 0.3s;"
                                   onmouseover="this.style.background='#3b5998';this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#111';this.style.transform='translateY(0)'">f</a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}" target="_blank"
                                   style="width:32px;height:32px;background:#111;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;text-decoration:none;border-radius:50%;transition:all 0.3s;"
                                   onmouseover="this.style.background='#1da1f2';this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#111';this.style.transform='translateY(0)'">t</a>
                                <button onclick="navigator.clipboard.writeText(window.location.href)"
                                        style="width:32px;height:32px;background:#111;color:#fff;border:none;cursor:pointer;font-size:12px;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:all 0.3s;" 
                                        onmouseover="this.style.background='#000';this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#111';this.style.transform='translateY(0)'"
                                        title="Copy link">🔗</button>
                            </div>
                        </div>
                    </div>

                    {{-- Featured Image (Full Bleed) --}}
                    @if($post->hasMedia('featured_image'))
                        <figure style="margin:0;">
                            <div style="width:100%;height:540px;overflow:hidden;position:relative;">
                                <img src="{{ $post->getFirstMediaUrl('featured_image') }}"
                                     alt="{{ $translation->title }}"
                                     style="width:100%;height:100%;object-fit:cover;transition:transform 0.8s ease;"
                                     onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                <div style="position:absolute;inset:0;box-shadow:inset 0 -100px 100px -100px rgba(0,0,0,0.3);"></div>
                            </div>
                            @if($post->getFirstMedia('featured_image')->getCustomProperty('caption'))
                                <figcaption style="font-size:11px;color:#777;padding:12px 40px;background:#fcfcfc;border-bottom:1px solid #f0f0f0;font-style:italic;display:flex;align-items:center;gap:6px;">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                    {{ $post->getFirstMedia('featured_image')->getCustomProperty('caption') }}
                                </figcaption>
                            @endif
                        </figure>
                    @endif

                    {{-- Body Content --}}
                    <div class="article-body" style="padding:40px;font-size:18px;line-height:1.75;color:#222;max-width:800px;margin:0 auto;">
                        <style>
                            .article-body p { margin-bottom: 24px; }
                            .article-body h2 { font-family: 'Merriweather', serif; font-size: 28px; font-weight: 900; color: #111; margin: 40px 0 20px; line-height: 1.2; }
                            .article-body h3 { font-family: 'Merriweather', serif; font-size: 22px; font-weight: 900; color: #111; margin: 30px 0 15px; }
                            .article-body blockquote { border-left: 5px solid #000; padding: 5px 0 5px 25px; margin: 35px 0; font-style: italic; color: #444; font-size: 1.2em; line-height: 1.5; background: #fff8f8; }
                            .article-body img { width: 100%; height: auto; margin: 35px 0; border-radius: 4px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
                            .article-body ul { margin-bottom: 24px; padding-left: 20px; list-style: disc; }
                            .article-body li { margin-bottom: 8px; }
                        </style>
                        {!! $translation->content !!}
                    </div>

                    {{-- Tags & Share Bottom --}}
                    <div style="padding:0 40px 40px 40px;">
                        @if($post->tags->count() > 0)
                            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:30px;">
                                @foreach($post->tags as $tag)
                                    <a href="#" style="padding:6px 14px;background:#fff;border:1px solid #eee;color:#777;font-size:11px;font-weight:800;text-transform:uppercase;text-decoration:none;border-radius:4px;transition:all 0.2s;" 
                                       onmouseover="this.style.borderColor='#000';this.style.color='#000';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'"
                                       onmouseout="this.style.borderColor='#eee';this.style.color='#777';this.style.boxShadow='none'">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Author Bio Card --}}
                        <div style="background:#f9f9f9;border-radius:12px;padding:25px;display:flex;gap:20px;align-items:center;border:1px solid #f0f0f0;">
                            <div style="width:70px;height:70px;border-radius:50%;background:#ddd;overflow:hidden;flex-shrink:0;">
                                @if($post->author->hasMedia('avatar'))
                                    <img src="{{ $post->author->getFirstMediaUrl('avatar') }}" style="width:100%;height:100%;object-fit:cover;">
                                @endif
                            </div>
                            <div style="flex:1;">
                                <p style="font-size:10px;font-weight:900;text-transform:uppercase;color:#000;letter-spacing:0.1em;margin-bottom:4px;">Journalist</p>
                                <h4 style="font-size:18px;font-weight:900;color:#111;margin-bottom:6px;">{{ $post->author->name }}</h4>
                                <p style="font-size:13px;color:#666;line-height:1.5;margin:0;">
                                    Expert contributor at BizScoop specializing in global market analysis and high-integrity business journalism.
                                </p>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- Bottom Ad Slot --}}
                <div style="margin-top:30px; margin-bottom:20px; overflow:hidden; border-radius:8px;">
                    <x-ad-banner position="article_bottom" />
                </div>

                {{-- Related Stories (Attractive Grid) --}}
                @if($relatedPosts->count() > 0)
                    <div style="margin-top:50px;">
                        <div class="sec-head" style="margin-bottom:20px;"><h3 class="sec-title" style="font-size:20px;">You Might Also Like</h3></div>
                        <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:20px;">
                            @foreach($relatedPosts->take(3) as $related)
                                <div style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,0.03);transition:transform 0.3s ease;"
                                     onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <a href="{{ route('frontend.article.show', $related->slug) }}" style="display:block;aspect-ratio:16/10;position:relative;background:#eee;text-decoration:none;">
                                        @if($related->hasMedia('featured_image'))
                                            <img src="{{ $related->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;">
                                        @endif
                                        <div style="position:absolute;inset:0;background:linear-gradient(to top, rgba(0,0,0,0.4), transparent);"></div>
                                        <div style="position:absolute;bottom:12px;left:12px;right:12px;">
                                            <span style="display:inline-block;background:#000;color:#fff;font-size:8px;font-weight:900;text-transform:uppercase;padding:2px 6px;border-radius:2px;margin-bottom:6px;">
                                                {{ $related->category->getTranslation('name', 'en') }}
                                            </span>
                                            <h4 style="font-size:13px;font-weight:800;color:#fff;line-height:1.3;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                                {{ $related->translate()->title }}
                                            </h4>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ─────────────── SIDEBAR ─────────────── --}}
            <div class="home-sidebar" style="width:320px;flex-shrink:0;">
                <div class="sidebar-sticky" style="position:sticky;top:80px;display:flex;flex-direction:column;gap:20px;">

                    {{-- Ad Slot (Premium Card) --}}
                    <div style="background:#fff;padding:15px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,0.03);border:1px solid #f0f0f0;">
                        <p style="font-size:8px;font-weight:900;color:#ccc;text-align:center;text-transform:uppercase;margin-bottom:8px;">Advertisement</p>
                        <x-ad-banner position="article_sidebar" />
                    </div>

                    {{-- Trending (Numbering Style) --}}
                    <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,0.03);border:1px solid #f0f0f0;">
                        <div class="sec-head" style="margin-bottom:15px;"><h3 class="sec-title">Trending</h3></div>
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            @foreach($sidebarTrendingArticles->take(5) as $i => $tp)
                                <div style="display:flex;gap:12px;align-items:flex-start;padding-bottom:12px;border-bottom:1px solid #f5f5f5;">
                                    <div style="font-size:28px;font-weight:900;color:#f0f0f0;line-height:1;min-width:32px;">{{ $i + 1 }}</div>
                                    <div style="flex:1;">
                                        <a href="{{ route('frontend.article.show', $tp->slug) }}" 
                                           style="font-size:13px;font-weight:800;color:#111;line-height:1.35;text-decoration:none;display:block;transition:color 0.2s;"
                                           onmouseover="this.style.color='#000'" onmouseout="this.style.color='#111'">{{ $tp->translate()->title }}</a>
                                        <p style="font-size:10px;color:#aaa;margin-top:4px;text-transform:uppercase;font-weight:700;">{{ $tp->published_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Newsletter (Premium Dark) --}}
                    <div style="background:#111;padding:25px;border-radius:8px;color:#fff;box-shadow:0 10px 30px rgba(0,0,0,0.1);position:relative;overflow:hidden;">
                        <div style="position:absolute;top:-20px;right:-20px;width:100px;height:100px;background:#000;border-radius:50%;opacity:0.1;filter:blur(40px);"></div>
                        <div style="font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;border-top:2px solid #000;padding-top:8px;margin-bottom:12px;">
                            The Briefing
                        </div>
                        <h4 style="font-size:18px;font-weight:900;margin-bottom:8px;">Business in your inbox.</h4>
                        <p style="font-size:11px;color:#aaa;margin-bottom:20px;line-height:1.6;">Essential insights and top stories, delivered every morning.</p>
                        <form 
                            x-data="{
                                email: '',
                                loading: false,
                                sent: false,
                                message: '',
                                async submit() {
                                    if(!this.email) return;
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
                                            body: JSON.stringify({ email: this.email })
                                        });
                                        const result = await response.json();
                                        this.message = result.message;
                                        if (response.ok) {
                                            this.sent = true;
                                            this.email = '';
                                        }
                                    } catch (e) {
                                        this.message = 'Something went wrong.';
                                    } finally {
                                        this.loading = false;
                                    }
                                }
                            }"
                            @submit.prevent="submit"
                            style="display:flex;flex-direction:column;gap:10px;"
                        >
                            <template x-if="!sent">
                                <div style="display:flex;flex-direction:column;gap:10px;">
                                    <input type="email" x-model="email" required placeholder="email@address.com" style="background:#222;border:1px solid #333;padding:12px;font-size:12px;color:#fff;outline:none;border-radius:4px;">
                                    <button type="submit" :disabled="loading" style="background:#000;color:#fff;font-size:11px;font-weight:900;text-transform:uppercase;padding:14px;border:none;cursor:pointer;border-radius:4px;transition:all 0.3s;display:flex;align-items:center;justify-content:center;gap:5px;" onmouseover="this.style.background='#333'" onmouseout="this.style.background='#000'">
                                        <svg x-show="loading" width="12" height="12" viewBox="0 0 24 24" style="animation: spin 1s linear infinite;"><path fill="currentColor" d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8Z"/></svg>
                                        <span x-text="loading ? '...' : 'Join 50k+ Readers'"></span>
                                    </button>
                                    <template x-if="message">
                                        <span x-text="message" style="font-size:10px;font-weight:bold;color:#ff6b6b;margin-top:-5px;"></span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="sent">
                                <div style="text-align:center;padding:15px;background:rgba(255,255,255,0.05);border-radius:4px;border:1px solid rgba(255,255,255,0.1);">
                                    <div style="font-size:24px;color:#4ade80;margin-bottom:5px;">✓</div>
                                    <p style="font-size:11px;font-weight:bold;color:#fff;margin:0;" x-text="message"></p>
                                </div>
                            </template>
                        </form>
                    </div>

                    {{-- Dynamic Poll --}}
                    <x-reader-poll />

                </div>
            </div>{{-- /sidebar --}}
        </div>
    </div>

    <script>
        window.onscroll = function() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("readingProgress").style.width = scrolled + "%";
        };
    </script>
</x-frontend-layout>
