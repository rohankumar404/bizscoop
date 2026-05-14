<x-frontend-layout
    :title="$category->seoMeta?->meta_title ?? $category->getTranslation('name', app()->getLocale())"
    :description="$category->seoMeta?->meta_description ?? $category->getTranslation('description', app()->getLocale())"
>
    {{-- Category Cinematic Header --}}
    <div style="background:linear-gradient(135deg, #111 0%, #333 100%);padding:60px 0;position:relative;overflow:hidden;margin-bottom:40px;">
        <div style="position:absolute;top:0;right:0;width:400px;height:400px;background:#e60000;opacity:0.05;border-radius:50%;filter:blur(80px);transform:translate(50%, -50%);"></div>
        <div class="wrap">
            <nav style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;color:#e60000;margin-bottom:15px;display:flex;align-items:center;gap:8px;">
                <a href="{{ route('frontend.home') }}" style="color:inherit;text-decoration:none;opacity:0.8;">Home</a>
                <span style="color:#555;">/</span>
                <span style="color:#fff;">{{ $category->getTranslation('name', app()->getLocale()) }}</span>
            </nav>
            <h1 style="font-family:'Merriweather',serif;font-size:48px;font-weight:900;color:#fff;margin:0;letter-spacing:-0.02em;">{{ $category->getTranslation('name', app()->getLocale()) }}</h1>
            @if($category->getTranslation('description', app()->getLocale()))
                <p style="font-size:16px;color:#aaa;line-height:1.6;margin-top:15px;max-width:700px;font-weight:500;">
                    {{ $category->getTranslation('description', app()->getLocale()) }}
                </p>
            @endif
        </div>
    </div>

    <div class="wrap" style="padding-bottom:60px;">
        <div class="flex gap-8">

            {{-- ─────────────── MAIN CONTENT ─────────────── --}}
            <div style="flex:1;min-width:0;">

                {{-- Articles List --}}
                <div style="display:flex;flex-direction:column;gap:30px;">
                    @forelse($posts as $index => $post)
                        @if($index === 0)
                            {{-- Premium Featured Post Card --}}
                            <article style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 20px 40px rgba(0,0,0,0.06);border:1px solid #f0f0f0;transition:transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);"
                                     onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 30px 60px rgba(0,0,0,0.1)'"
                                     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 20px 40px rgba(0,0,0,0.06)'">
                                <a href="{{ route('frontend.article.show', $post->slug) }}" style="display:block;aspect-ratio:21/9;position:relative;background:#eee;overflow:hidden;">
                                    @if($post->hasMedia('featured_image'))
                                        <img src="{{ $post->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.6s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    @endif
                                    <div style="position:absolute;top:20px;left:20px;background:#e60000;color:#fff;font-size:11px;font-weight:900;text-transform:uppercase;padding:5px 12px;border-radius:4px;letter-spacing:0.1em;box-shadow:0 4px 10px rgba(230,0,0,0.3);">Featured</div>
                                </a>
                                <div style="padding:35px;">
                                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:15px;">
                                        <span style="font-size:11px;font-weight:900;color:#e60000;text-transform:uppercase;letter-spacing:0.05em;">{{ $post->category->getTranslation('name', 'en') }}</span>
                                        <span style="width:4px;height:4px;background:#ddd;border-radius:50%;"></span>
                                        <span style="font-size:11px;font-weight:700;color:#999;text-transform:uppercase;">{{ $post->published_at->format('M d, Y') }}</span>
                                    </div>
                                    <a href="{{ route('frontend.article.show', $post->slug) }}" 
                                       style="font-family:'Merriweather',serif;font-size:32px;font-weight:900;color:#111;line-height:1.2;text-decoration:none;display:block;margin-bottom:15px;transition:color 0.2s;"
                                       onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#111'">
                                        {{ $post->translate()->title }}
                                    </a>
                                    <p style="font-size:16px;color:#666;line-height:1.6;margin:0;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                        {{ $post->translate()->excerpt }}
                                    </p>
                                </div>
                            </article>
                        @else
                            {{-- Premium List Post Card --}}
                            <article style="background:#fff;border-radius:10px;overflow:hidden;display:flex;height:180px;box-shadow:0 10px 25px rgba(0,0,0,0.03);border:1px solid #f0f0f0;transition:all 0.3s;"
                                     onmouseover="this.style.borderColor='#e60000';this.style.transform='translateX(5px)'"
                                     onmouseout="this.style.borderColor='#f0f0f0';this.style.transform='translateX(0)'">
                                <a href="{{ route('frontend.article.show', $post->slug) }}" style="width:280px;flex-shrink:0;position:relative;background:#eee;overflow:hidden;">
                                    @if($post->hasMedia('featured_image'))
                                        <img src="{{ $post->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    @endif
                                </a>
                                <div style="flex:1;padding:25px;display:flex;flex-direction:column;justify-content:center;">
                                    <p style="font-size:10px;font-weight:900;color:#999;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;">{{ $post->published_at->format('M d, Y') }}</p>
                                    <a href="{{ route('frontend.article.show', $post->slug) }}" 
                                       style="font-size:20px;font-weight:900;color:#111;line-height:1.3;text-decoration:none;display:block;margin-bottom:10px;transition:color 0.2s;"
                                       onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#111'">
                                        {{ $post->translate()->title }}
                                    </a>
                                    <p style="font-size:14px;color:#777;line-height:1.5;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                        {{ $post->translate()->excerpt }}
                                    </p>
                                </div>
                            </article>
                        @endif
                    @empty
                        <div style="background:#fff;border-radius:12px;padding:80px;text-align:center;border:1px dashed #ddd;">
                            <div style="width:60px;height:60px;background:#f9f9f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;color:#ccc;">
                                <svg width="30" height="30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"/><path d="M14 3v5h5M16 13H8M16 17H8M10 9H8"/></svg>
                            </div>
                            <p style="color:#999;font-size:16px;font-weight:600;">No articles found in this section yet.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Premium Pagination --}}
                @if($posts->hasPages())
                    <div style="margin-top:40px;display:flex;justify-content:center;">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>

            {{-- ─────────────── SIDEBAR ─────────────── --}}
            <div style="width:320px;flex-shrink:0;">
                <div style="position:sticky;top:80px;display:flex;flex-direction:column;gap:20px;">
                    
                    {{-- Ad Slot --}}
                    <div style="background:#fff;padding:15px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,0.03);border:1px solid #f0f0f0;">
                        <p style="font-size:8px;font-weight:900;color:#ccc;text-align:center;text-transform:uppercase;margin-bottom:8px;">Advertisement</p>
                        <x-ad-banner position="category_sidebar" />
                    </div>

                    {{-- Trending --}}
                    <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,0.03);border:1px solid #f0f0f0;">
                        <div class="sec-head" style="margin-bottom:15px;"><h3 class="sec-title">Trending</h3></div>
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            @foreach($sidebarTrendingArticles->take(5) as $i => $tp)
                                <div style="display:flex;gap:12px;align-items:flex-start;padding-bottom:12px;border-bottom:1px solid #f5f5f5;">
                                    <div style="font-size:28px;font-weight:900;color:#f0f0f0;line-height:1;min-width:32px;">{{ $i + 1 }}</div>
                                    <div style="flex:1;">
                                        <a href="{{ route('frontend.article.show', $tp->slug) }}" 
                                           style="font-size:13px;font-weight:800;color:#111;line-height:1.35;text-decoration:none;display:block;transition:color 0.2s;"
                                           onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#111'">{{ $tp->translate()->title }}</a>
                                        <p style="font-size:10px;color:#aaa;margin-top:4px;text-transform:uppercase;font-weight:700;">{{ $tp->published_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Newsletter (Premium Dark) --}}
                    <div style="background:#111;padding:25px;border-radius:8px;color:#fff;box-shadow:0 10px 30px rgba(0,0,0,0.1);position:relative;overflow:hidden;">
                        <div style="position:absolute;top:-20px;right:-20px;width:100px;height:100px;background:#e60000;border-radius:50%;opacity:0.1;filter:blur(40px);"></div>
                        <div style="font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;border-top:2px solid #e60000;padding-top:8px;margin-bottom:12px;">
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
                                    <button type="submit" :disabled="loading" style="background:#e60000;color:#fff;font-size:11px;font-weight:900;text-transform:uppercase;padding:14px;border:none;cursor:pointer;border-radius:4px;transition:all 0.3s;display:flex;align-items:center;justify-content:center;gap:5px;" onmouseover="this.style.background='#c00'" onmouseout="this.style.background='#e60000'">
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

                </div>
            </div>{{-- /sidebar --}}
        </div>
    </div>
</x-frontend-layout>
