<article class="list-post" style="border:1px solid #eee;padding:15px;background:#fff;display:flex;gap:20px;position:relative;margin-bottom:15px;">
    <a href="{{ route('frontend.article.show', $post->slug) }}" class="list-post-img" style="width:140px;height:95px;position:relative;flex-shrink:0;overflow:hidden;">
        @if($post->hasMedia('featured_image'))
            <img src="{{ $post->getFirstMediaUrl('featured_image') }}" style="width:100%;height:100%;object-fit:cover;">
        @endif
        <div style="position:absolute;top:5px;left:5px;background:rgba(0,0,0,0.9);color:#fff;font-size:7px;font-weight:900;text-transform:uppercase;padding:2px 5px;border-radius:2px;letter-spacing:0.1em;">
            {{ $post->category->getTranslation('name', 'en') }}
        </div>
    </a>
    <div style="flex:1;min-width:0;display:flex;flex-direction:column;justify-content:between;">
        <div>
            <p class="post-meta" style="font-size:10px;color:#aaa;margin-bottom:5px;text-transform:uppercase;letter-spacing:0.05em;">
                {{ $post->published_at->format('M d, Y') }} &bull; By {{ $post->author->name ?? 'Admin' }}
            </p>
            <a href="{{ route('frontend.article.show', $post->slug) }}" class="post-title" style="display:block;font-size:14px;font-weight:800;color:#111;text-decoration:none;margin-bottom:5px;line-height:1.3;">
                {{ $post->translate()?->title }}
            </a>
            <p style="font-size:11px;color:#666;line-height:1.4;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                {{ $post->translate()?->excerpt }}
            </p>
        </div>
        <div style="margin-top:10px;display:flex;gap:15px;align-items:center;">
            <form action="{{ route('frontend.article.interactions.' . $actionType, $post->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" style="background:none;border:none;padding:0;color:#b91c1c;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.05em;cursor:pointer;display:flex;align-items:center;gap:4px;"
                        onmouseover="this.style.textDecoration='underline'"
                        onmouseout="this.style.textDecoration='none'">
                    ✕ Remove from list
                </button>
            </form>
        </div>
    </div>
</article>
