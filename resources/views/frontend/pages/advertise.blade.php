<x-frontend-layout>
    <x-seo title="Advertise With Us | Reach a Premium Business Audience" />

    {{-- Cinematic Header --}}
    <div class="about-hero" style="background:linear-gradient(135deg, #e60000 0%, #111 100%);padding:100px 0;position:relative;overflow:hidden;">
        <div style="position:absolute;inset:0;opacity:0.1;background-image:radial-gradient(#fff 1px, transparent 1px);background-size:20px 20px;"></div>
        <div class="wrap text-center">
            <h1 class="adv-title" style="font-family:'Merriweather',serif;font-size:64px;font-weight:900;color:#fff;margin:0;letter-spacing:-0.04em;">Advertise With Us</h1>
            <p style="font-size:20px;color:rgba(255,255,255,0.8);margin-top:24px;max-width:800px;margin-left:auto;margin-right:auto;line-height:1.6;font-weight:600;">
                Connect your brand with high-net-worth professionals and key decision-makers across the region.
            </p>
        </div>
    </div>

    <div class="wrap" style="padding:60px 0;">
        
        {{-- Audience Stats --}}
        <div class="adv-grid-4" style="display:grid;grid-template-columns:repeat(4, 1fr);gap:20px;margin-bottom:80px;text-align:center;">
            @php
                $stats = [
                    ['500K+', 'Monthly Uniques'],
                    ['65%', 'C-Suite Audience'],
                    ['1.2M+', 'Social Reach'],
                    ['15Min+', 'Avg. Time on Site']
                ];
            @endphp
            @foreach($stats as $s)
                <div style="background:#fff;padding:30px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.05);border:1px solid #f0f0f0;">
                    <div class="adv-stat-number" style="font-size:36px;font-weight:900;color:#e60000;margin-bottom:5px;">{{ $s[0] }}</div>
                    <div style="font-size:12px;font-weight:800;color:#888;text-transform:uppercase;letter-spacing:0.1em;">{{ $s[1] }}</div>
                </div>
            @endforeach
        </div>

        <div style="max-width:1000px;margin:0 auto;">
            <div class="adv-grid-2" style="display:grid;grid-template-columns:1.5fr 1fr;gap:60px;align-items:start;">
                
                <div>
                    <h2 style="font-family:'Merriweather',serif;font-size:36px;font-weight:900;color:#111;margin-bottom:30px;">Strategic Marketing Solutions</h2>
                    <p style="font-size:18px;line-height:1.8;color:#444;margin-bottom:30px;">
                        BizScoop offers a suite of premium advertising products designed to help you achieve your marketing goals, from brand awareness to high-quality lead generation.
                    </p>
                    
                    <div style="display:flex;flex-direction:column;gap:25px;">
                        @php
                            $products = [
                                ['Display Advertising', 'High-impact banners, skins, and sticky units across desktop and mobile.'],
                                ['Sponsored Content', 'Collaborate with our editors to create engaging stories that resonate with our audience.'],
                                ['Newsletter Sponsorship', 'Reach over 50,000 daily subscribers directly in their inbox.'],
                                ['Event Partnership', 'Align your brand with our exclusive business webinars and summits.']
                            ];
                        @endphp
                        @foreach($products as $p)
                            <div style="display:flex;gap:20px;">
                                <div style="width:24px;height:24px;background:#e60000;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:4px;">
                                    <svg width="12" height="12" fill="#fff" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                </div>
                                <div>
                                    <h4 style="font-size:20px;font-weight:900;color:#111;margin-bottom:6px;">{{ $p[0] }}</h4>
                                    <p style="font-size:15px;color:#666;line-height:1.6;">{{ $p[1] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="adv-form-box adv-sidebar" 
                    x-data="{ 
                        form: { name: '', email: '', phone: '', company: '' },
                        loading: false,
                        sent: false,
                        errors: {},
                        async submit() {
                            this.loading = true;
                            this.errors = {};
                            try {
                                const response = await fetch('{{ route("frontend.advertise.store") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify(this.form)
                                });
                                const result = await response.json();
                                if (response.ok) {
                                    this.sent = true;
                                    this.form = { name: '', email: '', phone: '', company: '' };
                                } else {
                                    this.errors = result.errors || {};
                                }
                            } catch (e) {
                                console.error(e);
                            } finally {
                                this.loading = false;
                            }
                        }
                    }" 
                    style="background:#f9f9f9;padding:40px;border-radius:12px;border:1px solid #eee;position:sticky;top:100px;">
                    
                    <template x-if="!sent">
                        <div>
                            <h3 style="font-size:24px;font-weight:900;color:#111;margin-bottom:20px;">Request Media Kit</h3>
                            <p style="font-size:14px;color:#777;line-height:1.6;margin-bottom:25px;">Download our 2026 Media Kit for detailed demographics, pricing, and specs.</p>
                            
                            <form @submit.prevent="submit" style="display:flex;flex-direction:column;gap:15px;">
                                <div>
                                    <input type="text" x-model="form.name" required placeholder="Full Name *" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;">
                                    <template x-if="errors.name"><span x-text="errors.name[0]" style="color:#e60000;font-size:10px;font-weight:bold;margin-top:5px;display:block;"></span></template>
                                </div>
                                
                                <div>
                                    <input type="email" x-model="form.email" required placeholder="Work Email *" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;">
                                    <template x-if="errors.email"><span x-text="errors.email[0]" style="color:#e60000;font-size:10px;font-weight:bold;margin-top:5px;display:block;"></span></template>
                                </div>
                                
                                <div>
                                    <input type="text" x-model="form.phone" placeholder="Phone Number (Optional)" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;">
                                    <template x-if="errors.phone"><span x-text="errors.phone[0]" style="color:#e60000;font-size:10px;font-weight:bold;margin-top:5px;display:block;"></span></template>
                                </div>
                                
                                <div>
                                    <input type="text" x-model="form.company" placeholder="Company Name (Optional)" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;">
                                    <template x-if="errors.company"><span x-text="errors.company[0]" style="color:#e60000;font-size:10px;font-weight:bold;margin-top:5px;display:block;"></span></template>
                                </div>
                                
                                <button type="submit" :disabled="loading" style="background:#e60000;color:#fff;padding:15px;font-weight:900;text-transform:uppercase;font-size:13px;border:none;border-radius:4px;cursor:pointer;transition:all 0.3s;display:flex;align-items:center;justify-content:center;gap:10px;" onmouseover="this.style.background='#c00'" onmouseout="this.style.background='#e60000'">
                                    <svg x-show="loading" width="16" height="16" viewBox="0 0 24 24" style="animation: spin 1s linear infinite;margin-right:8px;"><path fill="currentColor" d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8Z"/></svg>
                                    <span x-text="loading ? 'Sending...' : 'Send Request'"></span>
                                </button>
                            </form>
                            <p style="font-size:11px;color:#aaa;text-align:center;margin-top:15px;">By submitting, you agree to our privacy policy.</p>
                        </div>
                    </template>

                    <template x-if="sent">
                        <div style="text-align:center;padding:20px 0;">
                            <div style="width:60px;height:60px;background:#e6000015;color:#e60000;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                                <svg width="30" height="30" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h3 style="font-size:20px;font-weight:900;color:#111;margin-bottom:10px;">Request Received</h3>
                            <p style="color:#666;line-height:1.6;font-size:14px;">Thank you for your interest in BizScoop. We have received your request and will send the 2026 Media Kit to your inbox shortly.</p>
                        </div>
                    </template>
                </div>

            </div>
        </div>
    </div>
</x-frontend-layout>
