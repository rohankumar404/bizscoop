<x-frontend-layout>
    <x-seo title="Editorial Standards | Bizscoop Commitment to Integrity" />

    {{-- Cinematic Header --}}
    <div class="about-hero" style="background:linear-gradient(135deg, #111 0%, #222 100%);padding:80px 0;position:relative;overflow:hidden;">
        <div style="position:absolute;top:0;left:0;width:400px;height:400px;background:#000;opacity:0.03;border-radius:50%;filter:blur(100px);transform:translate(-50%, -50%);"></div>
        <div class="wrap text-center">
            <h1 class="about-title" style="font-family:'Merriweather',serif;font-size:56px;font-weight:900;color:#fff;margin:0;letter-spacing:-0.03em;">Editorial Standards</h1>
            <p style="font-size:18px;color:#aaa;margin-top:20px;max-width:800px;margin-left:auto;margin-right:auto;line-height:1.6;font-weight:500;">
                The foundation of our journalism is built on accuracy, independence, and unwavering transparency.
            </p>
        </div>
    </div>

    <div class="wrap" style="padding:60px 0;">
        <div class="about-box" style="max-width:900px;margin:0 auto;background:#fff;padding:60px;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.05);border:1px solid #f0f0f0;">
            
            <section style="margin-bottom:50px;">
                <h2 style="font-family:'Merriweather',serif;font-size:32px;font-weight:900;color:#111;margin-bottom:24px;">Our Commitment</h2>
                <p style="font-size:17px;line-height:1.8;color:#444;margin-bottom:20px;">
                    At Bizscoop, we maintain the highest standards of journalistic integrity. Every story we publish undergoes a rigorous verification process to ensure that our readers receive only the most accurate and unbiased information.
                </p>
            </section>

            <div style="display:flex;flex-direction:column;gap:40px;">
                @php
                    $standards = [
                        ['Accuracy', 'We verify every fact. Our journalists use primary sources whenever possible and corroborate information through multiple independent channels.'],
                        ['Independence', 'Our editorial decisions are made independently of commercial or political interests. We maintain a strict "Chinese Wall" between our newsroom and our advertising department.'],
                        ['Fairness', 'We strive to represent all sides of a story. When reporting on controversial issues, we provide a platform for all relevant viewpoints to ensure a balanced perspective.'],
                        ['Transparency', 'If we make a mistake, we correct it promptly and transparently. We clearly distinguish between factual reporting, analysis, and opinion pieces.'],
                        ['Conflict of Interest', 'Our staff must disclose any potential conflicts of interest. We do not accept gifts or favors that could influence our reporting.']
                    ];
                @endphp

                @foreach($standards as $std)
                    <div style="border-left:5px solid #000;padding-left:30px;margin-bottom:10px;">
                        <h3 style="font-size:24px;font-weight:900;color:#111;margin-bottom:10px;">{{ $std[0] }}</h3>
                        <p style="font-size:16px;color:#555;line-height:1.7;">{{ $std[1] }}</p>
                    </div>
                @endforeach
            </div>

            <section class="about-footer" style="margin-top:60px;padding:40px;background:#fcfcfc;border-radius:8px;border:1px solid #eee;">
                <h2 style="font-family:'Merriweather',serif;font-size:24px;font-weight:900;color:#111;margin-bottom:15px;">Reporting a Concern</h2>
                <p style="font-size:15px;color:#666;line-height:1.6;margin-bottom:20px;">
                    We take our standards seriously. If you believe we have fallen short of our commitments, or if you wish to report a factual error, please contact our editorial team immediately.
                </p>
                <a href="{{ route('frontend.pages.contact') }}" style="color:#000;font-weight:800;text-decoration:none;text-transform:uppercase;font-size:12px;letter-spacing:0.1em;display:flex;align-items:center;gap:8px;">
                    Contact Editorial Board <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </a>
            </section>

        </div>
    </div>
</x-frontend-layout>
