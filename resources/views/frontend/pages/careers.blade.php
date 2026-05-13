<x-frontend-layout>
    <x-seo title="Careers | Join the BizScoop Newsroom" />

    {{-- Cinematic Header --}}
    <div style="background:linear-gradient(135deg, #111 0%, #333 100%);padding:100px 0;position:relative;overflow:hidden;">
        <div style="position:absolute;bottom:0;right:0;width:600px;height:600px;background:#e60000;opacity:0.04;border-radius:50%;filter:blur(100px);transform:translate(30%, 30%);"></div>
        <div class="wrap text-center">
            <h1 style="font-family:'Merriweather',serif;font-size:64px;font-weight:900;color:#fff;margin:0;letter-spacing:-0.03em;">Join Our Team</h1>
            <p style="font-size:20px;color:#aaa;margin-top:24px;max-width:800px;margin-left:auto;margin-right:auto;line-height:1.6;font-weight:500;">
                Shape the future of business journalism. We are looking for bold storytellers and strategic thinkers.
            </p>
        </div>
    </div>

    <div class="wrap" style="padding:60px 0;">
        
        <div style="max-width:1000px;margin:0 auto;">
            
            <section style="text-align:center;margin-bottom:80px;">
                <h2 style="font-family:'Merriweather',serif;font-size:36px;font-weight:900;color:#111;margin-bottom:20px;">Why Work With Us?</h2>
                <p style="font-size:18px;color:#666;max-width:700px;margin:0 auto;line-height:1.7;">
                    At BizScoop, we foster a culture of innovation, integrity, and excellence. We provide a platform where your voice can drive global business impact.
                </p>
                <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:30px;margin-top:50px;">
                    @foreach([['Global Impact','Your stories reach half a million business leaders every month.'],['Creative Freedom','We encourage deep-dive investigative pieces and original analysis.'],['Growth Path','Continuous learning and rapid career advancement opportunities.']] as $ben)
                        <div style="text-align:left;padding:30px;background:#fff;border-radius:8px;box-shadow:0 10px 25px rgba(0,0,0,0.03);border:1px solid #f0f0f0;">
                            <h4 style="font-size:18px;font-weight:900;color:#e60000;margin-bottom:12px;">{{ $ben[0] }}</h4>
                            <p style="font-size:14px;color:#777;line-height:1.6;">{{ $ben[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section>
                <h2 style="font-family:'Merriweather',serif;font-size:32px;font-weight:900;color:#111;margin-bottom:40px;">Open Positions</h2>
                <div style="display:flex;flex-direction:column;gap:20px;">
                    @php
                        $jobs = [
                            ['Senior Business Journalist', 'Dubai / Remote', 'Full-time'],
                            ['Data Analyst - Market Trends', 'Riyadh / On-site', 'Full-time'],
                            ['Social Media Strategist', 'Remote', 'Contract'],
                            ['Digital Ad Sales Executive', 'Dubai / Hybrid', 'Full-time']
                        ];
                    @endphp
                    @foreach($jobs as $job)
                        <div style="background:#fff;padding:30px;border-radius:10px;border:1px solid #eee;display:flex;justify-content:space-between;align-items:center;transition:all 0.3s;"
                             onmouseover="this.style.borderColor='#e60000';this.style.transform='translateX(10px)'" onmouseout="this.style.borderColor='#eee';this.style.transform='translateX(0)'">
                            <div>
                                <h3 style="font-size:22px;font-weight:900;color:#111;margin-bottom:6px;">{{ $job[0] }}</h3>
                                <div style="display:flex;gap:15px;font-size:13px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:0.05em;">
                                    <span>{{ $job[1] }}</span>
                                    <span>&bull;</span>
                                    <span style="color:#e60000;">{{ $job[2] }}</span>
                                </div>
                            </div>
                            <a href="#" style="background:#111;color:#fff;padding:12px 25px;font-size:12px;font-weight:900;text-transform:uppercase;text-decoration:none;border-radius:4px;transition:all 0.3s;" onmouseover="this.style.background='#e60000'" onmouseout="this.style.background='#111'">Apply Now</a>
                        </div>
                    @endforeach
                </div>
            </section>

            <section style="margin-top:80px;background:#f9f9f9;padding:60px;border-radius:12px;text-align:center;border:1px dashed #ddd;">
                <h2 style="font-family:'Merriweather',serif;font-size:28px;font-weight:900;color:#111;margin-bottom:15px;">Don't see a fit?</h2>
                <p style="color:#666;margin-bottom:25px;">We're always looking for exceptional talent. Send us your CV and a cover letter for future consideration.</p>
                <a href="mailto:careers@bizscoop.com" style="color:#e60000;font-weight:900;text-decoration:none;text-transform:uppercase;font-size:14px;letter-spacing:0.1em;border-bottom:2px solid #e60000;padding-bottom:3px;">careers@bizscoop.com</a>
            </section>

        </div>
    </div>
</x-frontend-layout>
