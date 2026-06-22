<x-frontend-layout>
    <x-seo title="Careers | Join the Bizscoop Newsroom" />

    {{-- Cinematic Hero Header --}}
    <div class="careers-hero" style="background: linear-gradient(135deg, #0f0f11 0%, #1c1c21 100%); padding: 120px 0; position: relative; overflow: hidden; border-bottom: 1px solid #222;">
        <div style="position: absolute; bottom: 0; right: 0; width: 600px; height: 600px; background: #000; opacity: 0.05; border-radius: 50%; filter: blur(120px); transform: translate(30%, 30%);"></div>
        <div style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: #555; opacity: 0.02; border-radius: 50%; filter: blur(80px);"></div>
        <div class="wrap text-center" style="position: relative; z-index: 10;">
            <span style="background: rgba(0, 0, 0, 0.1); color: #000; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; padding: 8px 16px; border-radius: 50px; display: inline-block; margin-bottom: 24px;">Careers at Bizscoop</span>
            <h1 class="careers-hero-title" style="font-family: 'Merriweather', serif; font-size: 56px; font-weight: 900; color: #fff; margin: 0; letter-spacing: -0.03em; line-height: 1.1;">Shape the Future of Business Media</h1>
            <p class="careers-hero-subtitle" style="font-size: 18px; color: #aaa; margin-top: 24px; max-width: 750px; margin-left: auto; margin-right: auto; line-height: 1.7; font-weight: 400;">
                Join an elite editorial team dedicated to objective business journalism, data-driven insights, and narrative depth. We seek bold storytellers and strategic thinkers.
            </p>
        </div>
    </div>

    <div class="wrap" style="padding: 80px 0;">
        <div style="max-width: 1100px; margin: 0 auto;">
            
            {{-- Perks/Benefits --}}
            <section style="text-align: center; margin-bottom: 100px;">
                <h2 style="font-family: 'Merriweather', serif; font-size: 36px; font-weight: 900; color: #111; margin-bottom: 16px;">Why Work With Us?</h2>
                <p style="font-size: 16px; color: #666; max-width: 650px; margin: 0 auto 50px; line-height: 1.7;">
                    At Bizscoop, we foster a culture of deep investigation, creative freedom, and structural support so that you can do your best professional work.
                </p>
                <div class="careers-grid-3" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
                    @foreach([
                        ['Global Impact', 'Your stories reach over half a million business executives, industry pioneers, and investors globally every month.', '🌍'],
                        ['Editorial Freedom', 'We champion deep-dive investigative journalism, proprietary research, and unrestrained original analyses.', '✍️'],
                        ['Elite Ecosystem', 'Collaborate with seasoned media directors, global analysts, and state-of-the-art tech platforms.', '🚀']
                    ] as $ben)
                        <div class="careers-card" style="text-align: left; padding: 40px 30px; background: #fff; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.03); border: 1px solid #eee; transition: all 0.3s;">
                            <div style="font-size: 32px; margin-bottom: 20px;">{{ $ben[2] }}</div>
                            <h4 style="font-size: 18px; font-weight: 900; color: #111; margin-bottom: 12px; font-family: 'Merriweather', serif;">{{ $ben[0] }}</h4>
                            <p style="font-size: 14px; color: #777; line-height: 1.6; margin: 0;">{{ $ben[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Cultural Core Values --}}
            <section style="background: #fdfdfd; border: 1px solid #f0f0f0; border-radius: 16px; padding: 60px 40px; margin-bottom: 100px;">
                <div class="careers-flex-container" style="display: flex; gap: 50px; align-items: center;">
                    <div style="flex: 1;">
                        <h2 style="font-family: 'Merriweather', serif; font-size: 32px; font-weight: 900; color: #111; margin-bottom: 20px;">Our Core Editorial Values</h2>
                        <p style="font-size: 15px; color: #666; line-height: 1.7; margin-bottom: 24px;">
                            We don't just report news; we trace the operational architectures behind corporate changes, technological disruptions, and economic megatrends.
                        </p>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            @foreach([
                                ['Unyielding Integrity', 'Accuracy and objectivity are non-negotiable standards of our reports.'],
                                ['Analytical Depth', 'We look past simple press releases to expose deeper structural insights.'],
                                ['Creative Courage', 'We support our authors to raise hard questions and publish challenging truths.']
                            ] as $val)
                                <div style="display: flex; gap: 12px; align-items: flex-start;">
                                    <div style="width: 6px; height: 6px; border-radius: 50%; background: #000; margin-top: 8px; flex-shrink: 0;"></div>
                                    <div>
                                        <h5 style="font-size: 14px; font-weight: 900; color: #111; margin: 0 0 2px 0;">{{ $val[0] }}</h5>
                                        <p style="font-size: 13px; color: #777; margin: 0; line-height: 1.5;">{{ $val[1] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="careers-hero-badge" style="flex: 1; background: #0f0f11; color: #fff; padding: 50px; border-radius: 12px; position: relative;">
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: #000;"></div>
                        <h4 style="font-family: 'Merriweather', serif; font-size: 24px; font-weight: 900; line-height: 1.4; margin-bottom: 20px;">“At Bizscoop, we believe that high-quality journalism is a vital catalyst for economic transparency and progress.”</h4>
                        <p style="font-size: 12px; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">Editorial Board, Bizscoop</p>
                    </div>
                </div>
            </section>

            {{-- Open Positions --}}
            <section x-data="{ activeJob: null }" style="margin-bottom: 100px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; border-bottom: 2px solid #eee; padding-bottom: 20px;">
                    <div>
                        <h2 style="font-family: 'Merriweather', serif; font-size: 32px; font-weight: 900; color: #111; margin: 0 0 8px 0;">Open Positions</h2>
                        <p style="font-size: 14px; color: #777; margin: 0;">Click on a posting to view full responsibilities and application criteria.</p>
                    </div>
                    <span style="font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; color: #000; background: #00010; padding: 6px 12px; border-radius: 4px;">
                        {{ $jobs->count() }} Roles Available
                    </span>
                </div>

                <div class="careers-job-list" style="display: flex; flex-direction: column; gap: 20px;">
                    @forelse($jobs as $index => $job)
                        <div class="careers-job-row" 
                             style="background: #fff; border-radius: 10px; border: 1px solid #eee; transition: all 0.3s; overflow: hidden; cursor: pointer;"
                             :style="activeJob === {{ $index }} ? 'border-color: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.05);' : ''"
                             @click="activeJob = (activeJob === {{ $index }} ? null : {{ $index }})">
                            
                            {{-- Visible Header Row --}}
                            <div style="padding: 30px; display: flex; justify-content: space-between; align-items: center; gap: 20px;">
                                <div>
                                    <h3 style="font-size: 20px; font-weight: 900; color: #111; margin-bottom: 8px;">{{ $job->title }}</h3>
                                    <div style="display: flex; gap: 15px; align-items: center; font-size: 12px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 0.05em;">
                                        <span style="display: flex; align-items: center; gap: 5px;">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                            {{ $job->location }}
                                        </span>
                                        <span>&bull;</span>
                                        <span style="color: #000;">{{ $job->type }}</span>
                                    </div>
                                </div>
                                <div style="display: flex; items-center: center; gap: 15px; flex-shrink: 0;">
                                    <span class="careers-job-arrow" :style="activeJob === {{ $index }} ? 'transform: rotate(180deg); color: #000;' : ''" style="transition: transform 0.3s; color: #888; display: inline-block;">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                    </span>
                                </div>
                            </div>

                            {{-- Collapsible Details Section --}}
                            <div x-show="activeJob === {{ $index }}" 
                                 x-transition:enter="transition ease-out duration-250"
                                 x-transition:enter-start="opacity-0 max-h-0"
                                 x-transition:enter-end="opacity-100 max-h-[1000px]"
                                 style="border-t: 1px solid #eee; background: #fafafa; padding: 40px 30px; cursor: default;"
                                 @click.stop>
                                <div style="font-size: 14px; color: #444; line-height: 1.8; white-space: pre-line; margin-bottom: 30px;">
                                    {{ $job->description }}
                                </div>

                                <div style="display: flex; justify-content: flex-start;">
                                    <a href="mailto:{{ $adminEmail }}?subject=Application for {{ rawurlencode($job->title) }} - BizScoop&body={{ rawurlencode("Hi Bizscoop Editorial Team,\n\nI am writing to express my interest in the " . $job->title . " position (" . $job->location . " / " . $job->type . ").\n\nPlease find my resume and application details attached.\n\nBest regards,\n[Your Name]") }}" 
                                       class="careers-apply-btn"
                                       style="background: #000; color: #fff; padding: 15px 35px; font-size: 13px; font-weight: 900; text-transform: uppercase; text-decoration: none; border-radius: 4px; transition: all 0.3s; display: inline-flex; align-items: center; gap: 10px;">
                                        <span>Apply For This Role</span>
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="background: #fff; padding: 60px; border-radius: 12px; border: 1px dashed #ccc; text-align: center;">
                            <p style="font-size: 18px; color: #888; font-style: italic; margin-bottom: 10px;">We don't have any specific openings listed right now.</p>
                            <p style="font-size: 14px; color: #666; margin: 0;">But we're always scouting for extraordinary editors, writers, and analysts. Submit a spontaneous application below!</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Spontaneous Application --}}
            <section style="background: #0f0f11; padding: 80px 40px; border-radius: 16px; text-align: center; border: 1px solid #222; position: relative; overflow: hidden;">
                <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: #000; opacity: 0.05; border-radius: 50%; filter: blur(50px);"></div>
                <h2 style="font-family: 'Merriweather', serif; font-size: 32px; font-weight: 900; color: #fff; margin-bottom: 15px; position: relative; z-index: 10;">Don't see a fit?</h2>
                <p style="color: #ccc; margin-bottom: 35px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.7; font-size: 15px; position: relative; z-index: 10;">
                    We are always looking for exceptional editorial, strategic, and tech talent. Send us your resume and cover letter for future consideration.
                </p>
                <a href="mailto:{{ $adminEmail }}?subject=Spontaneous Application - BizScoop&body={{ rawurlencode("Hi Bizscoop Team,\n\nI would love to be considered for future opportunities at Bizscoop. Please find my resume attached.\n\nBest regards,\n[Your Name]") }}" 
                   style="color: #fff; background: transparent; border: 2px solid #000; padding: 15px 40px; font-weight: 900; text-decoration: none; text-transform: uppercase; font-size: 13px; letter-spacing: 0.1em; border-radius: 4px; transition: all 0.3s; display: inline-block; position: relative; z-index: 10;"
                   onmouseover="this.style.backgroundColor='#000'"
                   onmouseout="this.style.backgroundColor='transparent'">
                    Submit Spontaneous Application
                </a>
            </section>

        </div>
    </div>
</x-frontend-layout>
