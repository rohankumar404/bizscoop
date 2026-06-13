<x-frontend-layout>
    <x-seo title="User Dashboard | BizScoop" />

    <div style="background:#f9f9f9;border-bottom:1px solid #eee;padding:50px 0;">
        <div class="wrap">
            <nav style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;color:#000;margin-bottom:15px;display:flex;align-items:center;gap:8px;">
                <a href="{{ route('frontend.home') }}" style="color:inherit;text-decoration:none;opacity:0.8;">Home</a>
                <span style="color:#ddd;">/</span>
                <span style="color:#111;">Dashboard</span>
            </nav>
            <h1 style="font-family:'Merriweather',serif;font-size:38px;font-weight:900;color:#111;margin:0;letter-spacing:-0.02em;">
                Welcome, {{ auth()->user()->name }}
            </h1>
            <p style="font-size:12px;color:#777;margin-top:8px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;display:flex;align-items:center;gap:12px;">
                <span>{{ auth()->user()->email }}</span>
                @if(auth()->user()->google_id)
                    <span style="background:#e0f2fe;color:#0369a1;padding:2px 8px;font-size:9px;border-radius:10px;font-weight:800;letter-spacing:0.02em;">Linked with Google</span>
                @endif
            </p>
        </div>
    </div>

    <div class="wrap" style="padding-top:40px;padding-bottom:80px;" x-data="{ activeTab: 'saved' }">
        <div class="flex gap-8 home-main-layout">
            
            {{-- Sidebar Tabs navigation --}}
            <div style="width:240px;flex-shrink:0;display:flex;flex-direction:column;gap:5px;">
                <button @click="activeTab = 'saved'" 
                        :class="activeTab === 'saved' ? 'background:#111;color:#fff;' : 'background:#f5f5f5;color:#333;'"
                        style="width:100%;text-align:left;border:none;padding:14px 20px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:between;">
                    <span>Saved (Read Later)</span>
                    <span style="font-size:9px;background:rgba(0,0,0,0.1);padding:2px 6px;border-radius:10px;" :style="activeTab === 'saved' ? 'background:rgba(255,255,255,0.2);color:#fff;' : ''">{{ $savedPosts->count() }}</span>
                </button>
                <button @click="activeTab = 'favorites'" 
                        :class="activeTab === 'favorites' ? 'background:#111;color:#fff;' : 'background:#f5f5f5;color:#333;'"
                        style="width:100%;text-align:left;border:none;padding:14px 20px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:between;">
                    <span>Favorites</span>
                    <span style="font-size:9px;background:rgba(0,0,0,0.1);padding:2px 6px;border-radius:10px;" :style="activeTab === 'favorites' ? 'background:rgba(255,255,255,0.2);color:#fff;' : ''">{{ $favoritePosts->count() }}</span>
                </button>
                <button @click="activeTab = 'likes'" 
                        :class="activeTab === 'likes' ? 'background:#111;color:#fff;' : 'background:#f5f5f5;color:#333;'"
                        style="width:100%;text-align:left;border:none;padding:14px 20px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:between;">
                    <span>Liked Articles</span>
                    <span style="font-size:9px;background:rgba(0,0,0,0.1);padding:2px 6px;border-radius:10px;" :style="activeTab === 'likes' ? 'background:rgba(255,255,255,0.2);color:#fff;' : ''">{{ $likedPosts->count() }}</span>
                </button>
                <button @click="activeTab = 'history'" 
                        :class="activeTab === 'history' ? 'background:#111;color:#fff;' : 'background:#f5f5f5;color:#333;'"
                        style="width:100%;text-align:left;border:none;padding:14px 20px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:between;">
                    <span>Reading History</span>
                    <span style="font-size:9px;background:rgba(0,0,0,0.1);padding:2px 6px;border-radius:10px;" :style="activeTab === 'history' ? 'background:rgba(255,255,255,0.2);color:#fff;' : ''">{{ $readHistory->count() }}</span>
                </button>
                <button @click="activeTab = 'settings'" 
                        :class="activeTab === 'settings' ? 'background:#111;color:#fff;' : 'background:#f5f5f5;color:#333;'"
                        style="width:100%;text-align:left;border:none;padding:14px 20px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;cursor:pointer;transition:all 0.2s;">
                    Account Settings
                </button>
            </div>

            {{-- Main Column Content Panels --}}
            <div class="home-main-column" style="flex:1;min-width:0;">
                
                {{-- Session Alerts --}}
                @if(session('status') === 'settings-updated')
                    <div style="background:#e6f4ea;color:#137333;padding:16px 20px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:30px;border-left:4px solid #137333;">
                        Profile changes saved successfully.
                    </div>
                @endif

                {{-- PANEL: Saved / Read Later --}}
                <div x-show="activeTab === 'saved'">
                    <h2 style="font-family:'Merriweather',serif;font-size:22px;font-weight:900;color:#000;margin-top:0;margin-bottom:24px;border-bottom:2px solid #000;padding-bottom:10px;">
                        Read Later List
                    </h2>
                    <div style="display:flex;flex-direction:column;gap:20px;">
                        @forelse($savedPosts as $post)
                            @include('frontend.profile.partials.dashboard-post-card', ['post' => $post, 'actionType' => 'bookmark'])
                        @empty
                            @include('frontend.profile.partials.dashboard-empty', ['title' => 'No saved articles', 'message' => 'Articles you save for reading later will show up here.'])
                        @endforelse
                    </div>
                </div>

                {{-- PANEL: Favorites --}}
                <div x-show="activeTab === 'favorites'">
                    <h2 style="font-family:'Merriweather',serif;font-size:22px;font-weight:900;color:#000;margin-top:0;margin-bottom:24px;border-bottom:2px solid #000;padding-bottom:10px;">
                        Favorite Articles
                    </h2>
                    <div style="display:flex;flex-direction:column;gap:20px;">
                        @forelse($favoritePosts as $post)
                            @include('frontend.profile.partials.dashboard-post-card', ['post' => $post, 'actionType' => 'favorite'])
                        @empty
                            @include('frontend.profile.partials.dashboard-empty', ['title' => 'No favorites selected', 'message' => 'Articles you mark as favorites will be listed here.'])
                        @endforelse
                    </div>
                </div>

                {{-- PANEL: Likes --}}
                <div x-show="activeTab === 'likes'">
                    <h2 style="font-family:'Merriweather',serif;font-size:22px;font-weight:900;color:#000;margin-top:0;margin-bottom:24px;border-bottom:2px solid #000;padding-bottom:10px;">
                        Liked Articles
                    </h2>
                    <div style="display:flex;flex-direction:column;gap:20px;">
                        @forelse($likedPosts as $post)
                            @include('frontend.profile.partials.dashboard-post-card', ['post' => $post, 'actionType' => 'like'])
                        @empty
                            @include('frontend.profile.partials.dashboard-empty', ['title' => 'No liked articles', 'message' => 'Articles you like will appear here.'])
                        @endforelse
                    </div>
                </div>

                {{-- PANEL: Reading History --}}
                <div x-show="activeTab === 'history'">
                    <h2 style="font-family:'Merriweather',serif;font-size:22px;font-weight:900;color:#000;margin-top:0;margin-bottom:24px;border-bottom:2px solid #000;padding-bottom:10px;">
                        Your Reading History
                    </h2>
                    <div style="display:flex;flex-direction:column;gap:20px;">
                        @forelse($readHistory as $post)
                            <article class="list-post" style="border:1px solid #eee;padding:15px;background:#fff;display:flex;gap:20px;position:relative;">
                                <a href="{{ route('frontend.article.show', $post->slug) }}" class="list-post-img" style="width:140px;height:95px;position:relative;flex-shrink:0;overflow:hidden;">
                                    @if($post->hasMedia('featured_image'))
                                        <img src="{{ $post->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;">
                                    @endif
                                    <div style="position:absolute;top:5px;left:5px;background:rgba(0,0,0,0.9);color:#fff;font-size:7px;font-weight:900;text-transform:uppercase;padding:2px 5px;border-radius:2px;letter-spacing:0.1em;">
                                        {{ $post->category->getTranslation('name', 'en') }}
                                    </div>
                                </a>
                                <div style="flex:1;min-width:0;display:flex;flex-direction:column;justify-content:space-between;">
                                    <div>
                                        <p class="post-meta" style="font-size:10px;color:#aaa;margin-bottom:5px;text-transform:uppercase;letter-spacing:0.05em;">
                                            {{ $post->published_at->format('M d, Y') }} &bull; Viewed on {{ \Carbon\Carbon::parse($post->pivot->last_read_at)->format('M d, Y \a\t g:i A') }}
                                        </p>
                                        <a href="{{ route('frontend.article.show', $post->slug) }}" class="post-title" style="display:block;font-size:14px;font-weight:800;color:#111;text-decoration:none;margin-bottom:5px;line-height:1.3;">
                                            {{ $post->translate()?->title }}
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            @include('frontend.profile.partials.dashboard-empty', ['title' => 'History empty', 'message' => 'Articles you read will automatically be recorded in your reading history.'])
                        @endforelse
                    </div>
                </div>

                {{-- PANEL: Settings --}}
                <div x-show="activeTab === 'settings'">
                    <h2 style="font-family:'Merriweather',serif;font-size:22px;font-weight:900;color:#000;margin-top:0;margin-bottom:24px;border-bottom:2px solid #000;padding-bottom:10px;">
                        Account Settings
                    </h2>

                    <form action="{{ route('frontend.profile.update') }}" method="POST" style="background:#fff;border:1px solid #eee;padding:30px;max-width:550px;">
                        @csrf
                        @method('PATCH')

                        <div style="margin-bottom:20px;">
                            <label style="display:block;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;color:#111;margin-bottom:8px;">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required 
                                   style="width:100%;border:1px solid #ccc;padding:12px 14px;font-size:13px;outline:none;background:#fcfcfc;font-weight:600;">
                            @error('name')
                                <span style="font-size:11px;color:#b91c1c;margin-top:4px;display:block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="margin-bottom:20px;">
                            <label style="display:block;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;color:#666;margin-bottom:8px;">Email Address (Cannot Change)</label>
                            <input type="email" value="{{ auth()->user()->email }}" disabled 
                                   style="width:100%;border:1px solid #ddd;padding:12px 14px;font-size:13px;outline:none;background:#f2f2f2;color:#777;cursor:not-allowed;">
                        </div>

                        @if(!auth()->user()->google_id)
                            <div style="border-top:1px solid #eee;margin-top:30px;padding-top:25px;margin-bottom:20px;">
                                <h3 style="font-family:'Merriweather',serif;font-size:15px;font-weight:900;margin:0 0 15px;">Update Password</h3>
                            </div>

                            <div style="margin-bottom:20px;">
                                <label style="display:block;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;color:#111;margin-bottom:8px;">Current Password</label>
                                <input type="password" name="current_password" 
                                       style="width:100%;border:1px solid #ccc;padding:12px 14px;font-size:13px;outline:none;background:#fcfcfc;">
                                @error('current_password')
                                    <span style="font-size:11px;color:#b91c1c;margin-top:4px;display:block;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div style="margin-bottom:20px;">
                                <label style="display:block;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;color:#111;margin-bottom:8px;">New Password</label>
                                <input type="password" name="password" 
                                       style="width:100%;border:1px solid #ccc;padding:12px 14px;font-size:13px;outline:none;background:#fcfcfc;">
                                @error('password')
                                    <span style="font-size:11px;color:#b91c1c;margin-top:4px;display:block;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div style="margin-bottom:25px;">
                                <label style="display:block;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;color:#111;margin-bottom:8px;">Confirm New Password</label>
                                <input type="password" name="password_confirmation" 
                                       style="width:100%;border:1px solid #ccc;padding:12px 14px;font-size:13px;outline:none;background:#fcfcfc;">
                            </div>
                        @endif

                        <button type="submit" 
                                style="background:#000;color:#fff;border:none;padding:14px 28px;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;cursor:pointer;transition:background 0.2s;"
                                onmouseover="this.style.background='#222'"
                                onmouseout="this.style.background='#000'">
                            Save Settings
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-frontend-layout>
