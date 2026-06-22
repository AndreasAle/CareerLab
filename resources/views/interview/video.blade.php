<x-dashboard-layout title="Interview Video AI">
    @php
        $seed = $messages->map(fn ($m) => ['sender' => $m->sender, 'text' => $m->message])->values();
        $lastAi = $messages->where('sender', 'ai_hrd')->last();
    @endphp

    <div x-data="videoInterview({
            messages: @js($seed),
            opening: @js($lastAi->message ?? 'Halo, selamat datang. Yuk kita mulai.'),
            mode: @js($modes[$session->hrd_mode] ?? $session->hrd_mode),
            position: @js($session->target_position),
            endpoint: '{{ route('interview.video.message', $session) }}',
            completed: {{ $session->status === 'completed' ? 'true' : 'false' }}
         })" x-init="init()" class="mx-auto max-w-5xl">

        {{-- ===== Video stage ===== --}}
        <div class="cl-rise relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 shadow-xl"
             :class="{ 'cl-avatar speaking': speaking }">
            <div class="absolute inset-0 bg-[radial-gradient(circle,_rgba(255,255,255,0.06)_1px,_transparent_1px)] [background-size:22px_22px]"></div>
            <div class="absolute -left-16 top-10 h-56 w-56 rounded-full bg-violet-600/30 blur-3xl"></div>
            <div class="absolute -right-16 bottom-0 h-56 w-56 rounded-full bg-indigo-600/30 blur-3xl"></div>

            {{-- top bar --}}
            <div class="relative flex items-center justify-between p-4">
                <div class="flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs text-white backdrop-blur">
                    <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-2 w-2 animate-ping rounded-full bg-red-500"></span><span class="relative h-2 w-2 rounded-full bg-red-500"></span></span>
                    REC · <span class="font-semibold" x-text="timer"></span>
                </div>
                <div class="rounded-full bg-white/10 px-3 py-1.5 text-xs font-medium text-white backdrop-blur" x-text="mode + ' · ' + position"></div>
            </div>

            {{-- ===== AI interviewer video tile (realistic human) ===== --}}
            @php
                // Realistic human portrait (free CDN). Swap to your own asset or a D-ID/HeyGen
                // talking-head stream URL for true lip-sync (see AVATAR_VIDEO_URL note).
                $avatarPhoto = config('services.ai_avatar.photo') ?: 'https://randomuser.me/api/portraits/women/79.jpg';
                $avatarVideo = config('services.ai_avatar.video'); // optional looping mp4 of a real person
            @endphp
            <div class="relative flex flex-col items-center px-4 pb-5 pt-1">
                <div class="relative w-full max-w-md">
                    <span class="cl-pulse absolute -inset-1 -z-10 rounded-3xl bg-emerald-400/30"></span>
                    <div class="relative aspect-[4/3] w-full overflow-hidden rounded-2xl shadow-2xl ring-1 ring-white/10 transition"
                         :class="speaking ? 'ring-2 ring-emerald-400/80' : 'ring-white/10'">
                        @if ($avatarVideo)
                            {{-- looping real-person video (idle), most lifelike --}}
                            <video src="{{ $avatarVideo }}" autoplay loop muted playsinline class="h-full w-full object-cover" :class="speaking ? 'cl-alive' : ''"></video>
                        @else
                            <img src="{{ $avatarPhoto }}" alt="Clara - AI HRD" referrerpolicy="no-referrer"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 class="h-full w-full object-cover" :class="speaking ? 'cl-alive' : ''">
                            <div style="display:none" class="absolute inset-0 items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-600 text-7xl font-bold text-white">C</div>
                        @endif

                        {{-- legibility gradient --}}
                        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/70 to-transparent"></div>

                        {{-- status pill (top-left) --}}
                        <div class="absolute left-3 top-3 rounded-full bg-black/55 px-3 py-1 text-[11px] font-medium text-white backdrop-blur">
                            <span x-show="speaking">🔊 Sedang bicara</span>
                            <span x-show="!speaking && loading" x-cloak>💭 Mendengarkan jawabanmu...</span>
                            <span x-show="!speaking && !loading" x-cloak>🎙️ Giliran kamu</span>
                        </div>

                        {{-- speaking equalizer (top-right) --}}
                        <div x-show="speaking" x-cloak class="absolute right-3 top-3 flex h-5 items-end gap-0.5">
                            @for ($i = 0; $i < 5; $i++)
                                <span class="cl-soundbar w-1 rounded-full bg-emerald-400" style="animation-delay: {{ $i * 0.12 }}s"></span>
                            @endfor
                        </div>

                        {{-- name tag (bottom-left, Zoom style) --}}
                        <div class="absolute bottom-3 left-3 flex items-center gap-2 rounded-lg bg-black/55 px-3 py-1.5 backdrop-blur">
                            <span class="flex h-3.5 items-end gap-0.5">
                                <span class="w-0.5 rounded-full bg-emerald-400" style="height:40%"></span>
                                <span class="w-0.5 rounded-full bg-emerald-400" style="height:70%"></span>
                                <span class="w-0.5 rounded-full bg-emerald-400" style="height:100%"></span>
                            </span>
                            <span class="text-xs font-semibold text-white">Clara · AI HRD</span>
                        </div>
                    </div>
                </div>

                {{-- live caption (current question) --}}
                <div class="mt-4 w-full max-w-xl rounded-2xl bg-black/30 px-5 py-3 text-center text-sm leading-relaxed text-white/90 backdrop-blur">
                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-widest text-emerald-300/80">Clara berkata</span>
                    <span x-text="aiText"></span>
                </div>
            </div>

            {{-- self-view webcam PiP --}}
            <div class="absolute bottom-4 right-4 h-28 w-40 overflow-hidden rounded-xl border border-white/20 bg-slate-800 shadow-lg">
                <video x-ref="selfcam" autoplay muted playsinline class="h-full w-full object-cover" x-show="cameraOn"></video>
                <div x-show="!cameraOn" class="flex h-full flex-col items-center justify-center text-white/50">
                    <x-icon name="users" class="h-6 w-6"/>
                    <span class="mt-1 text-[10px]">Kamera mati</span>
                </div>
                <span class="absolute bottom-1 left-2 text-[10px] font-medium text-white/80">Kamu</span>
            </div>
        </div>

        {{-- ===== Controls ===== --}}
        <div class="cl-rise mt-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm" style="--reveal-delay:80ms">
            {{-- last feedback --}}
            <div x-show="lastScore !== null" x-cloak class="mb-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm text-amber-800">
                <span class="font-semibold">Skor jawaban: <span x-text="lastScore"></span>/100.</span> <span x-text="lastFeedback"></span>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                {{-- answer input (filled by voice or typed) --}}
                <div class="flex-1">
                    <label class="mb-1 block text-xs font-medium text-slate-500">Jawaban kamu (ngomong pakai mik, atau ketik)</label>
                    <textarea x-model="draft" rows="2" :disabled="loading" placeholder="Tekan tombol mik lalu mulai bicara, atau ketik di sini..."
                              class="w-full resize-none rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    {{-- mic --}}
                    <button @click="toggleMic()" :disabled="loading || speaking" type="button"
                            :class="listening ? 'bg-emerald-500 text-white animate-pulse' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="grid h-12 w-12 shrink-0 place-items-center rounded-xl transition disabled:opacity-50" title="Mik">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z"/></svg>
                    </button>
                    {{-- camera toggle --}}
                    <button @click="toggleCamera()" type="button" :class="cameraOn ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                            class="grid h-12 w-12 shrink-0 place-items-center rounded-xl transition" title="Kamera">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                    </button>
                    {{-- send --}}
                    <button @click="send()" :disabled="loading || speaking || !draft.trim()" type="button"
                            class="flex h-12 items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:scale-[1.02] disabled:opacity-50">
                        <span x-show="!loading">Kirim</span><span x-show="loading" x-cloak>...</span>
                        <x-icon name="arrow" class="h-4 w-4"/>
                    </button>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-3">
                <button @click="replay()" type="button" :disabled="speaking" class="flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-indigo-600 disabled:opacity-50">
                    <x-icon name="play" class="h-3.5 w-3.5"/> Ulangi pertanyaan
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400" x-show="readyToFinish" x-cloak>Sudah cukup pertanyaan — boleh diselesaikan ✨</span>
                    <form method="POST" action="{{ route('interview.finish', $session) }}" onsubmit="return confirm('Selesaikan interview & lihat laporan akhir?')">
                        @csrf
                        <button class="flex items-center gap-1.5 rounded-xl bg-rose-500 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-600">
                            <x-icon name="logout" class="h-4 w-4"/> Selesai & Lihat Laporan
                        </button>
                    </form>
                </div>
            </div>
            <p x-show="!supportsSTT" x-cloak class="mt-2 text-center text-[11px] text-amber-600">Browser kamu belum mendukung input suara — silakan ketik jawabanmu. (Pakai Chrome untuk pengalaman penuh.)</p>
        </div>

        <a href="{{ route('interview.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-indigo-600">
            <x-icon name="chevron" class="h-4 w-4 rotate-180"/> Kembali ke daftar interview
        </a>
    </div>

    <script>
        function videoInterview(cfg) {
            return {
                messages: cfg.messages || [],
                aiText: cfg.opening,
                mode: cfg.mode, position: cfg.position,
                speaking: false, listening: false, loading: false,
                cameraOn: false, draft: '', lastScore: null, lastFeedback: '',
                readyToFinish: false, supportsSTT: false,
                recog: null, voices: [], stream: null,
                timer: '00:00', _t0: Date.now(),

                init() {
                    this.loadVoices();
                    if (window.speechSynthesis) window.speechSynthesis.onvoiceschanged = () => this.loadVoices();
                    this.setupRecognition();
                    this.startTimer();
                    // greet
                    setTimeout(() => this.speak(this.aiText), 600);
                },
                startTimer() {
                    setInterval(() => {
                        const s = Math.floor((Date.now() - this._t0) / 1000);
                        this.timer = String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0');
                    }, 1000);
                },
                loadVoices() { try { this.voices = window.speechSynthesis ? window.speechSynthesis.getVoices() : []; } catch(e){} },
                pickVoice() {
                    return this.voices.find(v => /id[-_]ID/i.test(v.lang)) || this.voices.find(v => /^id/i.test(v.lang)) || this.voices.find(v => /female|wanita/i.test(v.name)) || this.voices[0] || null;
                },
                speak(text) {
                    if (!window.speechSynthesis || !text) return;
                    window.speechSynthesis.cancel();
                    const u = new SpeechSynthesisUtterance(text.replace(/[*_#]/g,''));
                    const v = this.pickVoice(); if (v) u.voice = v;
                    u.lang = 'id-ID'; u.rate = 1; u.pitch = 1.05;
                    u.onstart = () => this.speaking = true;
                    u.onend = () => this.speaking = false;
                    u.onerror = () => this.speaking = false;
                    window.speechSynthesis.speak(u);
                },
                replay() { this.speak(this.aiText); },

                setupRecognition() {
                    const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (!SR) { this.supportsSTT = false; return; }
                    this.supportsSTT = true;
                    const r = new SR();
                    r.lang = 'id-ID'; r.continuous = true; r.interimResults = true;
                    let base = '';
                    r.onresult = (e) => {
                        let interim = '';
                        for (let i = e.resultIndex; i < e.results.length; i++) {
                            const t = e.results[i][0].transcript;
                            if (e.results[i].isFinal) base += t + ' '; else interim += t;
                        }
                        this.draft = (base + interim).trim();
                    };
                    r.onend = () => { if (this.listening) { try { r.start(); } catch(e){} } };
                    this.recog = r;
                },
                toggleMic() {
                    if (!this.supportsSTT) return;
                    if (this.listening) { this.listening = false; try { this.recog.stop(); } catch(e){} }
                    else {
                        if (window.speechSynthesis) window.speechSynthesis.cancel();
                        this.listening = true; try { this.recog.start(); } catch(e){}
                    }
                },
                async toggleCamera() {
                    if (this.cameraOn) {
                        if (this.stream) this.stream.getTracks().forEach(t => t.stop());
                        this.cameraOn = false; return;
                    }
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                        this.$refs.selfcam.srcObject = this.stream; this.cameraOn = true;
                    } catch (e) { alert('Tidak bisa mengakses kamera. Cek izin browser.'); }
                },
                async send() {
                    const text = this.draft.trim();
                    if (!text || this.loading) return;
                    if (this.listening) this.toggleMic();
                    this.messages.push({ sender: 'user', text });
                    this.loading = true; this.draft = '';
                    try {
                        const res = await fetch(cfg.endpoint, {
                            method: 'POST',
                            headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                            body: JSON.stringify({ message: text }),
                        });
                        const data = await res.json();
                        if (res.ok) {
                            this.lastScore = data.answer_score; this.lastFeedback = data.feedback || '';
                            this.readyToFinish = !!data.is_ready_to_finish;
                            this.aiText = data.ai_message;
                            this.messages.push({ sender: 'ai_hrd', text: data.ai_message });
                            this.speak(data.ai_message);
                        } else {
                            this.aiText = data.message || 'Maaf, ada kendala. Coba lagi ya.';
                        }
                    } catch (e) { this.aiText = 'Koneksi bermasalah. Coba lagi sebentar ya.'; }
                    finally { this.loading = false; }
                },
            };
        }
    </script>
</x-dashboard-layout>
