<x-public-layout title="Cek CV Gratis">
    {{-- intro --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -left-20 top-0 h-80 w-80 rounded-full bg-indigo-200/40 blur-3xl"></div>
            <div class="absolute right-0 top-10 h-80 w-80 rounded-full bg-violet-200/40 blur-3xl"></div>
        </div>
        <div class="mx-auto max-w-3xl px-4 pt-14 pb-6 text-center lg:px-8">
            <span class="reveal inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50/70 px-4 py-1.5 text-xs font-semibold text-indigo-700">
                <x-icon name="spark" class="h-3.5 w-3.5"/> Gratis · Tanpa daftar
            </span>
            <h1 class="reveal mt-5 text-4xl font-extrabold tracking-tight sm:text-5xl">Cek CV kamu sekarang</h1>
            <p class="reveal mt-4 text-lg text-slate-500">Upload CV, lihat cara HRD membacanya dalam hitungan detik. Lalu tanya langsung ke <span class="font-semibold text-indigo-600">Clara</span>, career coach AI kita.</p>
        </div>
    </section>

    @if (session('success'))
        <div class="mx-auto max-w-3xl px-4"><div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div></div>
    @endif
    @if ($errors->any())
        <div class="mx-auto max-w-3xl px-4"><div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $errors->first() }}</div></div>
    @endif

    <section class="mx-auto max-w-5xl px-4 pb-20 lg:px-8">
        @if (! $review && $canCheck)
            {{-- ===== UPLOAD FORM ===== --}}
            <form method="POST" action="{{ route('free.cv.analyze') }}" enctype="multipart/form-data"
                  x-data="{ name:'', loading:false }" @submit="loading = true"
                  class="reveal mx-auto mt-6 max-w-2xl rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">
                @csrf
                <div class="mb-5">
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Posisi yang kamu incar</label>
                    <div class="flex items-center gap-2 rounded-xl border border-slate-200 px-3">
                        <x-icon name="target" class="h-5 w-5 text-slate-400"/>
                        <input type="text" name="target_position" required value="{{ old('target_position', request('position')) }}" placeholder="cth: Backend Developer" class="w-full border-0 py-2.5 text-sm focus:ring-0">
                    </div>
                </div>

                <label class="block cursor-pointer rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50/50 p-8 text-center transition hover:border-indigo-400 hover:bg-indigo-50/30">
                    <input type="file" name="cv_file" accept="application/pdf" class="hidden" @change="name = $event.target.files[0]?.name ?? ''">
                    <div class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white">
                        <x-icon name="doc" class="h-7 w-7"/>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Klik untuk pilih file CV (PDF, maks 5MB)</p>
                    <p class="mt-1 text-xs text-emerald-600" x-text="name"></p>
                </label>

                <div class="my-5 flex items-center gap-3 text-xs text-slate-400">
                    <span class="h-px flex-1 bg-slate-200"></span> ATAU TEMPEL TEKS CV <span class="h-px flex-1 bg-slate-200"></span>
                </div>
                <textarea name="manual_text" rows="4" placeholder="Tempel isi CV kamu di sini..." class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('manual_text') }}</textarea>

                <button type="submit" :disabled="loading"
                        class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:scale-[1.01] disabled:opacity-60">
                    <span x-show="!loading" class="flex items-center gap-2"><x-icon name="spark" class="h-4 w-4"/> Analisis CV Gratis</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2"><svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/><path d="M12 2a10 10 0 0110 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg> HRD lagi baca CV kamu...</span>
                </button>
                <p class="mt-3 text-center text-xs text-slate-400"><x-icon name="shield" class="mr-1 inline h-3.5 w-3.5 text-emerald-500"/> Data CV kamu hanya dipakai untuk analisis ini. Gratis 1× cek.</p>
            </form>

        @elseif (! $review && ! $canCheck)
            {{-- ===== LOCKED (free quota used) ===== --}}
            <div class="reveal mx-auto mt-6 max-w-xl rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
                <div class="mx-auto mb-4 grid h-16 w-16 place-items-center rounded-2xl bg-amber-50 text-amber-600"><x-icon name="lock" class="h-8 w-8"/></div>
                <h2 class="text-xl font-bold text-slate-800">Jatah cek CV gratis sudah habis</h2>
                <p class="mt-2 text-sm text-slate-500">Kamu sudah pakai 1× analisis gratis. Daftar untuk lanjut review CV tanpa batas, latihan interview, dan buka 10 fitur AI di dashboard.</p>
                <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25">Daftar Gratis</a>
                    <a href="{{ route('pricing') }}" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Lihat Paket</a>
                </div>
            </div>
        @endif

        {{-- ===== RESULT + CHAT ===== --}}
        @if ($review)
            @php
                $overall = (int) ($review['overall_score'] ?? 0);
                $ats = (int) ($review['ats_score'] ?? 0);
                $callProb = $review['call_probability'] ?? 'medium';
                $callLabel = ['low'=>'Rendah','medium'=>'Sedang','high'=>'Tinggi'][$callProb] ?? 'Sedang';
                $callColor = ['low'=>'bg-rose-100 text-rose-700','medium'=>'bg-amber-100 text-amber-700','high'=>'bg-emerald-100 text-emerald-700'][$callProb] ?? 'bg-amber-100 text-amber-700';
            @endphp

            <div class="mt-6 grid gap-6 lg:grid-cols-5">
                {{-- left: result --}}
                <div class="space-y-6 lg:col-span-3">
                    <div class="reveal rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Hasil Analisis · {{ $position }}</p>
                                <h2 class="mt-1 text-lg font-bold text-slate-800">Yang HRD lihat pertama</h2>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $callColor }}">Peluang dipanggil: {{ $callLabel }}</span>
                        </div>
                        <p class="mt-3 rounded-xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-600">{{ $review['hrd_first_impression'] ?? '' }}</p>
                        <div class="mt-5 flex items-center justify-around">
                            <x-score-ring :score="$overall" label="Overall" :size="110" />
                            <x-score-ring :score="$ats" label="ATS Score" :size="110" />
                        </div>
                    </div>

                    <div class="reveal grid gap-4 sm:grid-cols-2" style="--reveal-delay:80ms">
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h3 class="mb-2 flex items-center gap-2 text-sm font-semibold text-emerald-700"><x-icon name="check-c" class="h-4 w-4"/> Kekuatan</h3>
                            <ul class="space-y-1.5 text-sm text-slate-600">
                                @foreach (($review['strengths'] ?? []) as $s)<li class="flex gap-2"><span class="text-emerald-500">•</span>{{ $s }}</li>@endforeach
                            </ul>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h3 class="mb-2 flex items-center gap-2 text-sm font-semibold text-amber-700"><x-icon name="bolt" class="h-4 w-4"/> Perlu diperbaiki</h3>
                            <ul class="space-y-1.5 text-sm text-slate-600">
                                @foreach (($review['weaknesses'] ?? []) as $w)<li class="flex gap-2"><span class="text-amber-500">•</span>{{ $w }}</li>@endforeach
                            </ul>
                        </div>
                    </div>

                    @if (!empty($review['red_flags']))
                        <div class="reveal rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" style="--reveal-delay:120ms">
                            <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-rose-700"><x-icon name="shield" class="h-4 w-4"/> Red Flag</h3>
                            <div class="space-y-2">
                                @foreach ($review['red_flags'] as $flag)
                                    @php $lvl=$flag['risk_level']??'low'; $b=['low'=>'bg-emerald-100 text-emerald-700','medium'=>'bg-amber-100 text-amber-700','high'=>'bg-rose-100 text-rose-700'][$lvl]??'bg-slate-100 text-slate-600'; @endphp
                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <div class="flex items-center justify-between"><p class="text-sm font-medium text-slate-800">{{ $flag['title'] ?? '-' }}</p><span class="rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase {{ $b }}">{{ $lvl }}</span></div>
                                        @if(!empty($flag['fix']))<p class="mt-1 text-xs text-emerald-700">💡 {{ $flag['fix'] }}</p>@endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (!empty($review['rewritten_summary']))
                        <div class="reveal rounded-2xl border border-indigo-200 bg-indigo-50/60 p-5 shadow-sm" style="--reveal-delay:160ms">
                            <h3 class="mb-2 flex items-center gap-2 text-sm font-semibold text-indigo-800"><x-icon name="spark" class="h-4 w-4"/> Contoh summary yang lebih kuat</h3>
                            <p class="text-sm leading-relaxed text-indigo-900">{{ $review['rewritten_summary'] }}</p>
                        </div>
                    @endif
                </div>

                {{-- right: CHATBOT --}}
                <div class="lg:col-span-2">
                    <div class="reveal sticky top-20 flex h-[600px] flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl"
                         style="--reveal-delay:100ms"
                         x-data="claraChat({
                            history: @js($chatHistory),
                            tokens: {{ $tokensLeft }},
                            max: {{ $maxTokens }},
                            endpoint: '{{ route('free.cv.chat') }}',
                            pricing: '{{ route('pricing') }}',
                            register: '{{ route('register') }}'
                         })">
                        {{-- header --}}
                        <div class="flex items-center justify-between border-b border-slate-100 bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-3 text-white">
                            <div class="flex items-center gap-2.5">
                                <div class="relative grid h-9 w-9 place-items-center rounded-full bg-white/20">
                                    <x-icon name="chat" class="h-5 w-5"/>
                                    <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-indigo-600 bg-emerald-400"></span>
                                </div>
                                <div><p class="text-sm font-bold leading-none">Clara</p><p class="text-[11px] text-white/70">Career Coach AI</p></div>
                            </div>
                            <span class="rounded-full bg-white/15 px-2.5 py-1 text-[11px] font-semibold" x-text="tokens + '/' + max + ' pesan'"></span>
                        </div>

                        {{-- messages --}}
                        <div class="flex-1 space-y-3 overflow-y-auto p-4" x-ref="scroll">
                            <template x-for="(m, i) in messages" :key="i">
                                <div :class="m.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                                    <div :class="m.role === 'user'
                                            ? 'max-w-[85%] rounded-2xl rounded-tr-sm bg-slate-900 px-3.5 py-2.5 text-sm text-white'
                                            : 'max-w-[85%] rounded-2xl rounded-tl-sm bg-slate-100 px-3.5 py-2.5 text-sm text-slate-700'"
                                         x-text="m.content"></div>
                                </div>
                            </template>
                            {{-- typing --}}
                            <div x-show="loading" x-cloak class="flex justify-start">
                                <div class="flex gap-1 rounded-2xl rounded-tl-sm bg-slate-100 px-4 py-3">
                                    <span class="h-2 w-2 animate-bounce rounded-full bg-slate-400" style="animation-delay:0ms"></span>
                                    <span class="h-2 w-2 animate-bounce rounded-full bg-slate-400" style="animation-delay:150ms"></span>
                                    <span class="h-2 w-2 animate-bounce rounded-full bg-slate-400" style="animation-delay:300ms"></span>
                                </div>
                            </div>
                        </div>

                        {{-- upgrade overlay --}}
                        <div x-show="tokens <= 0" x-cloak class="border-t border-slate-100 bg-amber-50 p-4 text-center">
                            <p class="text-sm font-medium text-amber-800">Token chat gratis habis 🙌</p>
                            <p class="mt-0.5 text-xs text-amber-700">Daftar untuk ngobrol tanpa batas + buka semua fitur.</p>
                            <a :href="register" class="mt-2 inline-block rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2 text-sm font-semibold text-white">Daftar Gratis</a>
                        </div>

                        {{-- input --}}
                        <div x-show="tokens > 0" class="border-t border-slate-100 p-3">
                            <form @submit.prevent="send()" class="flex items-center gap-2">
                                <input x-model="draft" :disabled="loading" type="text" placeholder="Tanya soal CV kamu..."
                                       class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="submit" :disabled="loading || !draft.trim()"
                                        class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white disabled:opacity-50">
                                    <x-icon name="arrow" class="h-4 w-4"/>
                                </button>
                            </form>
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                <template x-for="q in suggestions" :key="q">
                                    <button @click="draft = q; send()" :disabled="loading" class="rounded-full border border-slate-200 px-2.5 py-1 text-[11px] text-slate-500 transition hover:border-indigo-300 hover:text-indigo-600" x-text="q"></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- upgrade nudge under chat --}}
                    <a href="{{ route('register') }}" class="reveal mt-4 flex items-center justify-between rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-4 text-white" style="--reveal-delay:160ms">
                        <div>
                            <p class="text-sm font-semibold">Mau analisis lengkap?</p>
                            <p class="text-xs text-white/60">Interview sim, job match, career report PDF & lainnya.</p>
                        </div>
                        <span class="grid h-9 w-9 place-items-center rounded-full bg-white/15"><x-icon name="arrow" class="h-4 w-4"/></span>
                    </a>
                </div>
            </div>
        @endif
    </section>

    <script>
        function claraChat(cfg) {
            return {
                messages: cfg.history || [],
                tokens: cfg.tokens,
                max: cfg.max,
                draft: '',
                loading: false,
                suggestions: ['Perbaiki summary aku', 'Keyword apa yang kurang?', 'Tips interview posisi ini'],
                init() { this.$nextTick(() => this.scroll()); },
                scroll() { const el = this.$refs.scroll; if (el) el.scrollTop = el.scrollHeight; },
                async send() {
                    const text = this.draft.trim();
                    if (!text || this.loading || this.tokens <= 0) return;
                    this.messages.push({ role: 'user', content: text });
                    this.draft = '';
                    this.loading = true;
                    this.$nextTick(() => this.scroll());
                    try {
                        const res = await fetch(cfg.endpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            },
                            body: JSON.stringify({ message: text }),
                        });
                        const data = await res.json();
                        if (res.ok) {
                            this.messages.push({ role: 'assistant', content: data.reply });
                            this.tokens = data.tokensLeft;
                        } else if (res.status === 402) {
                            this.tokens = 0;
                            this.messages.push({ role: 'assistant', content: data.message });
                        } else {
                            this.messages.push({ role: 'assistant', content: data.message || 'Maaf, ada kendala. Coba lagi ya.' });
                        }
                    } catch (e) {
                        this.messages.push({ role: 'assistant', content: 'Koneksi bermasalah. Coba lagi sebentar ya.' });
                    } finally {
                        this.loading = false;
                        this.$nextTick(() => this.scroll());
                    }
                },
            };
        }
    </script>
</x-public-layout>
