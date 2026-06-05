<x-frontend-layout>
    <x-seo title="Contact Us | Get in Touch with BizScoop" />

    {{-- Cinematic Header --}}
    <div style="background:linear-gradient(135deg, #111 0%, #333 100%);padding:80px 0;position:relative;overflow:hidden;">
        <div style="position:absolute;top:0;left:0;width:400px;height:400px;background:#000;opacity:0.04;border-radius:50%;filter:blur(100px);transform:translate(-50%, -50%);"></div>
        <div class="wrap text-center">
            <h1 style="font-family:'Merriweather',serif;font-size:56px;font-weight:900;color:#fff;margin:0;letter-spacing:-0.03em;">Contact Us</h1>
            <p style="font-size:18px;color:#aaa;margin-top:20px;max-width:800px;margin-left:auto;margin-right:auto;line-height:1.6;font-weight:500;">
                Have a tip, a question, or a business inquiry? Our team is ready to listen and respond.
            </p>
        </div>
    </div>

    <div class="wrap" style="padding:60px 0;">
        <div class="contact-grid" style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 1.5fr;gap:60px;">
            
            {{-- Contact Info --}}
            <div>
                <h2 style="font-family:'Merriweather',serif;font-size:32px;font-weight:900;color:#111;margin-bottom:40px;">Reach Out</h2>
                
                <div style="display:flex;flex-direction:column;gap:40px;">
                    <div>
                        <h4 style="font-size:11px;font-weight:900;text-transform:uppercase;color:#000;letter-spacing:0.15em;margin-bottom:12px;">Editorial Desk</h4>
                        <p style="font-size:20px;font-weight:900;color:#111;">tips@bizscoop.com</p>
                        <p style="font-size:14px;color:#777;margin-top:5px;">Secure channel for news tips and leaks.</p>
                    </div>

                    <div>
                        <h4 style="font-size:11px;font-weight:900;text-transform:uppercase;color:#000;letter-spacing:0.15em;margin-bottom:12px;">Business & Ads</h4>
                        <p style="font-size:20px;font-weight:900;color:#111;">partners@bizscoop.com</p>
                        <p style="font-size:14px;color:#777;margin-top:5px;">Inquiries about advertising and partnerships.</p>
                    </div>

                    <div>
                        <h4 style="font-size:11px;font-weight:900;text-transform:uppercase;color:#000;letter-spacing:0.15em;margin-bottom:12px;">Office Headquarters</h4>
                        <p style="font-size:16px;color:#111;font-weight:700;line-height:1.6;">
                            Level 14, Media One Tower,<br>
                            Dubai Media City, Dubai,<br>
                            United Arab Emirates
                        </p>
                    </div>
                </div>

                {{-- Social --}}
                <div style="margin-top:60px;display:flex;gap:15px;">
                    @foreach(['f','t','in','ig'] as $s)
                        <a href="#" style="width:40px;height:40px;background:#111;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:900;text-decoration:none;border-radius:4px;transition:all 0.3s;" onmouseover="this.style.background='#000'" onmouseout="this.style.background='#111'">{{ $s }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="contact-form-card" x-data="{ 
                form: { name: '', email: '', phone: '', subject: 'General Inquiry', message: '' },
                loading: false,
                sent: false,
                errors: {},
                async submit() {
                    this.loading = true;
                    this.errors = {};
                    try {
                        const response = await fetch('{{ route("frontend.contact.store") }}', {
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
                            this.form = { name: '', email: '', phone: '', subject: 'General Inquiry', message: '' };
                        } else {
                            this.errors = result.errors || {};
                        }
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loading = false;
                    }
                }
            }" style="background:#fff;padding:50px;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.05);border:1px solid #f0f0f0;">
                
                <template x-if="!sent">
                    <div>
                        <h3 style="font-family:'Merriweather',serif;font-size:28px;font-weight:900;color:#111;margin-bottom:30px;">Send a Message</h3>
                        <form @submit.prevent="submit" class="contact-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                            <div style="grid-column: span 1;">
                                <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;">Full Name *</label>
                                <input type="text" x-model="form.name" required style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;background:#fcfcfc;">
                                <template x-if="errors.name"><span x-text="errors.name[0]" style="color:#000;font-size:10px;font-weight:bold;margin-top:5px;display:block;"></span></template>
                            </div>
                            <div style="grid-column: span 1;">
                                <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;">Email Address *</label>
                                <input type="email" x-model="form.email" required style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;background:#fcfcfc;">
                                <template x-if="errors.email"><span x-text="errors.email[0]" style="color:#000;font-size:10px;font-weight:bold;margin-top:5px;display:block;"></span></template>
                            </div>
                            <div style="grid-column: span 2;">
                                <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;">Phone Number (Optional)</label>
                                <input type="text" x-model="form.phone" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;background:#fcfcfc;">
                                <template x-if="errors.phone"><span x-text="errors.phone[0]" style="color:#000;font-size:10px;font-weight:bold;margin-top:5px;display:block;"></span></template>
                            </div>
                            <div style="grid-column: span 2;">
                                <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;">Subject</label>
                                <select x-model="form.subject" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;background:#fcfcfc;">
                                    <option>General Inquiry</option>
                                    <option>Editorial Correction</option>
                                    <option>Advertising / Sales</option>
                                    <option>Press Release</option>
                                </select>
                            </div>
                            <div style="grid-column: span 2;">
                                <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;">Message *</label>
                                <textarea x-model="form.message" required rows="6" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;background:#fcfcfc;resize:none;"></textarea>
                                <template x-if="errors.message"><span x-text="errors.message[0]" style="color:#000;font-size:10px;font-weight:bold;margin-top:5px;display:block;"></span></template>
                            </div>
                            <div style="grid-column: span 2;">
                                <button type="submit" :disabled="loading" style="width:100%;background:#000;color:#fff;padding:15px;font-weight:900;text-transform:uppercase;font-size:13px;border:none;border-radius:4px;cursor:pointer;transition:all 0.3s;display:flex;align-items:center;justify-content:center;gap:10px;" onmouseover="this.style.background='#333'" onmouseout="this.style.background='#000'">
                                    <svg x-show="loading" width="16" height="16" viewBox="0 0 24 24" style="animation: spin 1s linear infinite;margin-right:8px;"><path fill="currentColor" d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8Z"/></svg>
                                    <span x-text="loading ? 'Sending...' : 'Submit Inquiry'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </template>

                <template x-if="sent">
                    <div style="text-align:center;padding:40px 0;">
                        <div style="width:80px;height:80px;background:#00015;color:#000;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 25px;">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 style="font-family:'Merriweather',serif;font-size:32px;font-weight:900;color:#111;margin-bottom:15px;">Message Sent</h3>
                        <p style="color:#666;line-height:1.8;font-size:16px;">Thank you for reaching out. We have received your message and will get back to you shortly.</p>
                    </div>
                </template>
            </div>

        </div>
    </div>
</x-frontend-layout>
