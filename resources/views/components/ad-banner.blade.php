@if($ad->type === 'code')
    <div class="ad-container ad-{{ $position }}" style="margin-bottom: 20px;">
        {!! $ad->content !!}
    </div>
@else
    @php
        $href = $ad->link ?? '#';
    @endphp
    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" class="ad-container ad-{{ $position }}" 
       style="display:block; position:relative; overflow:hidden; border-radius:4px; text-decoration:none; width:100%; transition: transform 0.3s ease-in-out;"
       onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
        
        @if($ad->image)
            <img src="{{ Storage::url($ad->image) }}" alt="{{ $ad->title }}" style="width:100%; height:auto; display:block; border-radius:4px;">
        @else
            <div style="width:100%; height:150px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; border-radius:4px;">
                <span style="color:#999; font-size:12px; font-weight:bold; text-transform:uppercase;">{{ $ad->title }}</span>
            </div>
        @endif
        
        {{-- Text Overlay (Matching Category Badge Style) --}}
        @if($ad->content)
            <div style="position:absolute; top:8px; left:8px;">
                <span style="background:#000; color:#fff; font-size:8px; font-weight:900; text-transform:uppercase; letter-spacing:0.1em; padding:3px 8px; box-shadow:0 2px 4px rgba(0,0,0,0.2);">
                    {{ $ad->content }}
                </span>
            </div>
        @endif
        
        <div style="position:absolute; top:8px; right:8px; background:rgba(255,255,255,0.9); color:#000; font-size:7px; padding:2px 5px; border-radius:2px; font-weight:900; text-transform:uppercase; letter-spacing:0.05em; z-index:10; opacity:0.7;">
            Ad
        </div>
    </a>
@endif