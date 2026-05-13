<x-frontend-layout>
    <x-seo title="Contact Us | Get in Touch with BizScoop" />

    {{-- Cinematic Header --}}
    <div style="background:linear-gradient(135deg, #111 0%, #333 100%);padding:80px 0;position:relative;overflow:hidden;">
        <div style="position:absolute;top:0;left:0;width:400px;height:400px;background:#e60000;opacity:0.04;border-radius:50%;filter:blur(100px);transform:translate(-50%, -50%);"></div>
        <div class="wrap text-center">
            <h1 style="font-family:'Merriweather',serif;font-size:56px;font-weight:900;color:#fff;margin:0;letter-spacing:-0.03em;">Contact Us</h1>
            <p style="font-size:18px;color:#aaa;margin-top:20px;max-width:800px;margin-left:auto;margin-right:auto;line-height:1.6;font-weight:500;">
                Have a tip, a question, or a business inquiry? Our team is ready to listen and respond.
            </p>
        </div>
    </div>

    <div class="wrap" style="padding:60px 0;">
        <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 1.5fr;gap:60px;">
            
            {{-- Contact Info --}}
            <div>
                <h2 style="font-family:'Merriweather',serif;font-size:32px;font-weight:900;color:#111;margin-bottom:40px;">Reach Out</h2>
                
                <div style="display:flex;flex-direction:column;gap:40px;">
                    <div>
                        <h4 style="font-size:11px;font-weight:900;text-transform:uppercase;color:#e60000;letter-spacing:0.15em;margin-bottom:12px;">Editorial Desk</h4>
                        <p style="font-size:20px;font-weight:900;color:#111;">tips@bizscoop.com</p>
                        <p style="font-size:14px;color:#777;margin-top:5px;">Secure channel for news tips and leaks.</p>
                    </div>

                    <div>
                        <h4 style="font-size:11px;font-weight:900;text-transform:uppercase;color:#e60000;letter-spacing:0.15em;margin-bottom:12px;">Business & Ads</h4>
                        <p style="font-size:20px;font-weight:900;color:#111;">partners@bizscoop.com</p>
                        <p style="font-size:14px;color:#777;margin-top:5px;">Inquiries about advertising and partnerships.</p>
                    </div>

                    <div>
                        <h4 style="font-size:11px;font-weight:900;text-transform:uppercase;color:#e60000;letter-spacing:0.15em;margin-bottom:12px;">Office Headquarters</h4>
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
                        <a href="#" style="width:40px;height:40px;background:#111;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:900;text-decoration:none;border-radius:4px;transition:all 0.3s;" onmouseover="this.style.background='#e60000'" onmouseout="this.style.background='#111'">{{ $s }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Contact Form --}}
            <div style="background:#fff;padding:50px;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.05);border:1px solid #f0f0f0;">
                <h3 style="font-family:'Merriweather',serif;font-size:28px;font-weight:900;color:#111;margin-bottom:30px;">Send a Message</h3>
                <form style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div style="grid-column: span 1;">
                        <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;">Full Name</label>
                        <input type="text" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;background:#fcfcfc;">
                    </div>
                    <div style="grid-column: span 1;">
                        <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;">Email Address</label>
                        <input type="email" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;background:#fcfcfc;">
                    </div>
                    <div style="grid-column: span 2;">
                        <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;">Subject</label>
                        <select style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;background:#fcfcfc;">
                            <option>General Inquiry</option>
                            <option>Editorial Correction</option>
                            <option>Advertising / Sales</option>
                            <option>Press Release</option>
                        </select>
                    </div>
                    <div style="grid-column: span 2;">
                        <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;">Message</label>
                        <textarea rows="6" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;outline:none;font-size:14px;background:#fcfcfc;resize:none;"></textarea>
                    </div>
                    <div style="grid-column: span 2;">
                        <button type="submit" style="width:100%;background:#e60000;color:#fff;padding:15px;font-weight:900;text-transform:uppercase;font-size:13px;border:none;border-radius:4px;cursor:pointer;transition:all 0.3s;" onmouseover="this.style.background='#c00'" onmouseout="this.style.background='#e60000'">Submit Inquiry</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-frontend-layout>
