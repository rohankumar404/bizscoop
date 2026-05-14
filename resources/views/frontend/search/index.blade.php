<x-frontend-layout>
    <x-seo :title="'Search Results for: ' . $query" />

    {{-- Search Cinematic Header --}}
    <div style="background:#f9f9f9;border-bottom:1px solid #eee;padding:50px 0;margin-bottom:40px;">
        <div class="wrap">
            <nav style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;color:#e60000;margin-bottom:15px;display:flex;align-items:center;gap:8px;">
                <a href="{{ route('frontend.home') }}" style="color:inherit;text-decoration:none;opacity:0.8;">Home</a>
                <span style="color:#ddd;">/</span>
                <span style="color:#111;">Search Discovery</span>
            </nav>
            <h1 style="font-family:'Merriweather',serif;font-size:42px;font-weight:900;color:#111;margin:0;letter-spacing:-0.02em;">
                <span style="color:#999;font-weight:400;">Results for</span> "{{ $query }}"
            </h1>
            <p style="font-size:14px;color:#777;margin-top:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">
                Found {{ $results->total() ?? 0 }} matching records in our archives
            </p>
        </div>
    </div>

    <div class="wrap" style="padding-bottom:60px;">
        <div class="flex gap-8">

            {{-- ─────────────── RESULTS COLUMN ─────────────── --}}
            <div style="flex:1;min-width:0;">
                
                <div style="display:flex;flex-direction:column;gap:20px;">
                    @forelse($results as $post)
                        @php 
                            $translation = $post->translate();
                            // Sophisticated highlighting logic
                            $highlightedTitle = str_ireplace($query, "<mark style='background:rgba(230,0,0,0.08);color:#e60000;padding:0 4px;border-radius:2px;font-weight:900;'>$query</mark>", $translation->title);
                            $highlightedExcerpt = str_ireplace($query, "<mark style='background:rgba(230,0,0,0.08);color:#e60000;padding:0 2px;border-radius:2px;'>$query</mark>", $translation->excerpt);
                        @endphp
                        
                        <article style="background:#fff;border-radius:10px;overflow:hidden;display:flex;height:160px;box-shadow:0 10px 25px rgba(0,0,0,0.03);border:1px solid #f0f0f0;transition:all 0.3s;"
                                 onmouseover="this.style.borderColor='#e60000';this.style.boxShadow='0 15px 35px rgba(0,0,0,0.06)'"
                                 onmouseout="this.style.borderColor='#f0f0f0';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.03)'">
                            <a href="{{ route('frontend.article.show', $post->slug) }}" style="width:240px;flex-shrink:0;position:relative;background:#eee;overflow:hidden;">
                                @if($post->hasMedia('featured_image'))
                                    <img src="{{ $post->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                @endif
                                <div style="position:absolute;top:10px;left:10px;background:rgba(230,0,0,0.9);color:#fff;font-size:8px;font-weight:900;text-transform:uppercase;padding:3px 8px;border-radius:2px;letter-spacing:0.1em;backdrop-filter:blur(4px);">
                                    {{ $post->category->getTranslation('name', 'en') }}
                                </div>
                            </a>
                            <div style="flex:1;padding:20px 25px;display:flex;flex-direction:column;justify-content:center;">
                                <p style="font-size:10px;font-weight:800;color:#aaa;text-transform:uppercase;margin-bottom:8px;">{{ $post->published_at->format('M d, Y') }}</p>
                                <a href="{{ route('frontend.article.show', $post->slug) }}" 
                                   style="font-size:18px;font-weight:900;color:#111;line-height:1.3;text-decoration:none;display:block;margin-bottom:10px;transition:color 0.2s;"
                                   onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#111'">
                                    {!! $highlightedTitle !!}
                                </a>
                                <p style="font-size:13px;color:#777;line-height:1.5;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    {!! $highlightedExcerpt !!}
                                </p>
                            </div>
                        </article>
                    @empty
                        <div style="background:#fff;border-radius:12px;padding:80px;text-align:center;border:1px dashed #ddd;">
                            <div style="width:70px;height:70px;background:#f9f9f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 25px;color:#ccc;">
                                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <h3 style="font-family:'Merriweather',serif;font-size:24px;font-weight:900;color:#111;margin-bottom:10px;">No matches found</h3>
                            <p style="color:#999;font-size:14px;max-width:400px;margin:0 auto 25px;line-height:1.6;">We couldn't find any articles matching "{{ $query }}". Please check your spelling or try more general keywords.</p>
                            <a href="{{ route('frontend.home') }}" style="display:inline-block;background:#111;color:#fff;font-size:11px;font-weight:900;text-transform:uppercase;text-decoration:none;padding:12px 25px;border-radius:4px;transition:all 0.3s;" onmouseover="this.style.background='#e60000'" onmouseout="this.style.background='#111'">Return Home</a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($results->hasPages())
                    <div style="margin-top:40px;display:flex;justify-content:center;">
                        {{ $results->appends(['q' => $query])->links() }}
                    </div>
                @endif
            </div>

            {{-- ─────────────── SIDEBAR ─────────────── --}}
            <div style="width:320px;flex-shrink:0;">
                <div style="position:sticky;top:80px;display:flex;flex-direction:column;gap:20px;">

                    {{-- Search Refinement --}}
                    <div style="background:#fff;padding:25px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,0.03);border:1px solid #f0f0f0;">
                        <div class="sec-head" style="margin-bottom:15px;"><h3 class="sec-title">Refine Search</h3></div>
                        <p style="font-size:13px;color:#666;line-height:1.6;margin-bottom:20px;">
                            Try selecting a specific category from the menu or using more specific business terminology.
                        </p>
                        <a href="{{ route('frontend.home') }}" style="font-size:11px;font-weight:900;color:#e60000;text-transform:uppercase;text-decoration:none;display:flex;align-items:center;gap:6px;">
                            Browse Categories <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    {{-- Ad Slot --}}
                    <div style="background:#fff;padding:15px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,0.03);border:1px solid #f0f0f0;">
                        <p style="font-size:8px;font-weight:900;color:#ccc;text-align:center;text-transform:uppercase;margin-bottom:8px;">Advertisement</p>
                        <x-ad-banner position="search_sidebar" />
                    </div>

                    {{-- Newsletter --}}
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
