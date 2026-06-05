@if($poll)
    <div class="content-box" style="margin-bottom:12px; border-radius:8px; padding:20px; box-shadow:0 4px 15px rgba(0,0,0,0.03); border:1px solid #e0e0e0; background:#fff;" 
         x-data="{
             voted: {{ json_encode($voted) }},
             options: {{ json_encode($poll->options_with_percentages) }},
             totalVotes: {{ $poll->total_votes }},
             activeOption: null,
             loading: false,
             message: '',
             async submitVote() {
                 if (this.activeOption === null || this.loading) return;
                 this.loading = true;
                 this.message = '';
                 try {
                     const response = await fetch('{{ route('frontend.polls.vote', $poll) }}', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': '{{ csrf_token() }}',
                             'Accept': 'application/json'
                         },
                         body: JSON.stringify({ option_index: this.activeOption })
                     });
                     const data = await response.json();
                     if (response.ok && data.success) {
                         this.options = data.options;
                         this.totalVotes = data.total_votes;
                         this.voted = true;
                     } else {
                         this.message = data.message || 'Something went wrong.';
                     }
                 } catch (e) {
                     this.message = 'Network error. Please try again.';
                 } finally {
                     this.loading = false;
                 }
             }
         }">
        <div class="sec-head">
            <h3 class="sec-title">Reader Poll</h3>
        </div>
        
        <p style="font-size:12.5px;font-weight:700;color:#111;margin-bottom:14px;line-height:1.45;">
            {{ $poll->question }}
        </p>

        {{-- Voting Form --}}
        <template x-if="!voted">
            <div style="display:flex;flex-direction:column;gap:8px;">
                <template x-for="(opt, idx) in options" :key="idx">
                    <label style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f9f9f9;border:1px solid #eee;border-radius:4px;cursor:pointer;font-size:11.5px;font-weight:600;color:#333;transition:all 0.2s;"
                           :style="activeOption === idx ? 'border-color:#000;background:#f5f5f5;color:#000;' : ''"
                           @click="activeOption = idx">
                        <input type="radio" name="poll_option" :value="idx" x-model="activeOption" style="accent-color:#000;cursor:pointer;">
                        <span x-text="opt.text"></span>
                    </label>
                </template>

                <button type="button" 
                        :disabled="activeOption === null || loading"
                        @click="submitVote"
                        style="width:100%;background:#000;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;padding:10px;border:none;cursor:pointer;margin-top:10px;display:flex;align-items:center;justify-content:center;gap:6px;transition:all 0.2s;"
                        :style="activeOption === null ? 'opacity:0.5;cursor:not-allowed;' : ''"
                        onmouseover="if(this.style.opacity !== '0.5') this.style.background='#333'" 
                        onmouseout="if(this.style.opacity !== '0.5') this.style.background='#000'">
                    <svg x-show="loading" width="10" height="10" viewBox="0 0 24 24" style="animation: spin 1s linear infinite; display: inline-block; vertical-align: middle;"><path fill="currentColor" d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8Z"/></svg>
                    <span x-text="loading ? 'Submitting...' : 'Vote Now'"></span>
                </button>
                
                <template x-if="message">
                    <p style="font-size:10px;color:#000;font-weight:bold;margin-top:5px;" x-text="message"></p>
                </template>
            </div>
        </template>

        {{-- Results View --}}
        <template x-if="voted">
            <div style="display:flex;flex-direction:column;gap:12px;">
                <template x-for="(opt, idx) in options" :key="idx">
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:10.5px;font-weight:700;color:#333;margin-bottom:4px;">
                            <span x-text="opt.text"></span>
                            <span style="color:#000;" x-text="opt.percentage + '%'"></span>
                        </div>
                        <div style="background:#eee;height:6px;border-radius:3px;overflow:hidden;">
                            <div style="background:linear-gradient(to right, #000, #444);height:100%;border-radius:3px;transition:width 1s cubic-bezier(0.4, 0, 0.2, 1);"
                                 :style="'width: ' + opt.percentage + '%'"></div>
                        </div>
                    </div>
                </template>
                <div style="border-top:1px solid #f0f0f0;padding-top:10px;margin-top:4px;display:flex;justify-content:space-between;align-items:center;font-size:9.5px;font-weight:700;text-transform:uppercase;color:#888;letter-spacing:0.05em;">
                    <span>Thank you for voting!</span>
                    <span x-text="totalVotes + ' total votes'"></span>
                </div>
            </div>
        </template>
    </div>
@endif
