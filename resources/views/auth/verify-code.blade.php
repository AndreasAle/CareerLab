<x-guest-layout>
    <div class="mb-7 text-center">
        <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-indigo-50 text-indigo-600">
            <x-icon name="mail" class="h-7 w-7"/>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Cek email kamu 📩</h2>
        <p class="mt-2 text-sm text-slate-500">Kami kirim kode verifikasi 6 digit ke<br><span class="font-semibold text-slate-700">{{ $email }}</span></p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-center text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-center text-sm text-amber-800">{{ session('error') }}</div>
    @endif
    @error('code')
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('verification.code') }}"
          x-data="otpInput()" @submit="syncCode()" x-ref="form" class="space-y-6">
        @csrf
        <input type="hidden" name="code" x-ref="codeField">

        <div class="flex justify-center gap-2 sm:gap-3" @paste.prevent="onPaste($event)">
            <template x-for="(d, i) in digits" :key="i">
                <input :ref="'box'+i" x-model="digits[i]" @input="onInput(i, $event)" @keydown="onKey(i, $event)"
                       type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code"
                       class="h-14 w-12 rounded-xl border-slate-300 text-center text-2xl font-bold text-slate-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:h-16 sm:w-14">
            </template>
        </div>

        <x-primary-button class="w-full py-3" ::disabled="!complete()">
            Verifikasi & Lanjut
        </x-primary-button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500">
        Nggak nerima kode?
        @if ($remaining > 0)
            <form method="POST" action="{{ route('verification.send') }}" class="mt-2">
                @csrf
                <button type="submit" class="font-semibold text-indigo-600 hover:underline">Kirim ulang ({{ $remaining }} tersisa)</button>
            </form>
        @else
            <p class="mt-2 text-xs text-amber-600">Batas pengiriman {{ $maxSends }}× tercapai. Coba lagi nanti atau hubungi support.</p>
        @endif
    </div>

    <div class="mt-6 border-t border-slate-100 pt-4 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-xs text-slate-400 hover:text-slate-600">Keluar & pakai akun lain</button>
        </form>
    </div>

    <script>
        function otpInput() {
            return {
                digits: ['', '', '', '', '', ''],
                complete() { return this.digits.every(d => d !== ''); },
                syncCode() { this.$refs.codeField.value = this.digits.join(''); },
                focusBox(i) { const el = this.$refs['box' + i]; if (el) el.focus(); },
                onInput(i, e) {
                    const v = e.target.value.replace(/\D/g, '');
                    this.digits[i] = v.slice(-1) || '';
                    if (this.digits[i] && i < 5) this.focusBox(i + 1);
                    if (this.complete()) { this.syncCode(); this.$nextTick(() => this.$refs.form.requestSubmit()); }
                },
                onKey(i, e) {
                    if (e.key === 'Backspace' && !this.digits[i] && i > 0) this.focusBox(i - 1);
                },
                onPaste(e) {
                    const txt = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                    if (!txt) return;
                    for (let i = 0; i < 6; i++) this.digits[i] = txt[i] || '';
                    this.focusBox(Math.min(txt.length, 5));
                    if (this.complete()) { this.syncCode(); this.$nextTick(() => this.$refs.form.requestSubmit()); }
                },
            };
        }
    </script>
</x-guest-layout>
