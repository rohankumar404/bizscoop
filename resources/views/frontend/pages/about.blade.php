<x-frontend-layout>
    <x-seo title="About BizScoop | High-Integrity Business Journalism" />

    {{-- Cinematic Header --}}
    <div style="background:linear-gradient(135deg, #111 0%, #333 100%);padding:80px 0;position:relative;overflow:hidden;">
        <div style="position:absolute;top:0;right:0;width:500px;height:500px;background:#e60000;opacity:0.05;border-radius:50%;filter:blur(100px);transform:translate(50%, -50%);"></div>
        <div class="wrap text-center">
            <h1 style="font-family:'Merriweather',serif;font-size:56px;font-weight:900;color:#fff;margin:0;letter-spacing:-0.03em;">About BizScoop</h1>
            <p style="font-size:18px;color:#aaa;margin-top:20px;max-width:800px;margin-left:auto;margin-right:auto;line-height:1.6;font-weight:500;">
                Delivering high-integrity business journalism and strategic market insights for professionals across the GCC and beyond.
            </p>
        </div>
    </div>

    <div class="wrap" style="padding:60px 0;">
        <div style="max-width:900px;margin:0 auto;background:#fff;padding:60px;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.05);border:1px solid #f0f0f0;">
            
            <section style="margin-bottom:50px;">
                <h2 style="font-family:'Merriweather',serif;font-size:32px;font-weight:900;color:#111;margin-bottom:24px;border-bottom:3px solid #e60000;display:inline-block;padding-bottom:8px;">Our Mission</h2>
                <p style="font-size:18px;line-height:1.8;color:#444;margin-bottom:20px;">
                    At BizScoop, we believe that informed decision-making is the cornerstone of successful enterprise. Our mission is to provide the most accurate, timely, and impactful business news to our readers, ensuring they stay ahead in a rapidly evolving global economy.
                </p>
                <p style="font-size:18px;line-height:1.8;color:#444;">
                    Founded in the heart of the MENA region, we have grown into a trusted voice for entrepreneurs, C-suite executives, and investors who demand more than just headlines—they demand insight.
                </p>
            </section>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-bottom:60px;">
                <div style="background:#f9f9f9;padding:30px;border-radius:8px;border-left:4px solid #e60000;">
                    <h3 style="font-size:20px;font-weight:900;color:#111;margin-bottom:12px;">Integrity First</h3>
                    <p style="font-size:15px;color:#666;line-height:1.6;">Our reporting is unbiased, verified, and strictly adheres to the highest editorial standards in the industry.</p>
                </div>
                <div style="background:#f9f9f9;padding:30px;border-radius:8px;border-left:4px solid #111;">
                    <h3 style="font-size:20px;font-weight:900;color:#111;margin-bottom:12px;">Market Intelligence</h3>
                    <p style="font-size:15px;color:#666;line-height:1.6;">We don't just report the news; we analyze the trends that matter most to your bottom line.</p>
                </div>
            </div>

            <section style="margin-bottom:50px;">
                <h2 style="font-family:'Merriweather',serif;font-size:32px;font-weight:900;color:#111;margin-bottom:24px;border-bottom:3px solid #e60000;display:inline-block;padding-bottom:8px;">What We Cover</h2>
                <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:20px;">
                    @foreach(['Global Markets','GCC Economy','FinTech','Real Estate','Energy Sector','Tech Innovation'] as $topic)
                        <div style="padding:15px;border:1px solid #eee;text-align:center;font-weight:800;font-size:13px;text-transform:uppercase;color:#555;border-radius:4px;">
                            {{ $topic }}
                        </div>
                    @endforeach
                </div>
            </section>

            <section style="text-align:center;background:#111;color:#fff;padding:50px;border-radius:12px;margin-top:40px;">
                <h2 style="font-family:'Merriweather',serif;font-size:28px;font-weight:900;margin-bottom:15px;">Become a Part of Our Story</h2>
                <p style="color:#aaa;margin-bottom:30px;">Subscribe to our newsletter for daily updates and exclusive market analysis.</p>
                <a href="#" style="display:inline-block;background:#e60000;color:#fff;padding:14px 35px;font-size:14px;font-weight:900;text-transform:uppercase;text-decoration:none;border-radius:4px;transition:all 0.3s;" onmouseover="this.style.background='#c00'" onmouseout="this.style.background='#e60000'">Join BizScoop Today</a>
            </section>

        </div>
    </div>
</x-frontend-layout>
