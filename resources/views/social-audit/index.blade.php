<x-dashboard-layout title="Social Audit">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Social Media HR Check</h2>
        <p class="text-sm text-slate-500">Cek personal branding kamu sebelum apply. Tanpa scraping — kamu isi sendiri datanya.</p>
    </div>

    @php $o = $old ?? []; @endphp
    <form method="POST" action="{{ route('social-audit.check') }}" x-data="{ loading: false }" @submit="loading = true"
          class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        @csrf
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">Target Role</label>
                <input type="text" name="target_role" value="{{ $o['target_role'] ?? auth()->user()->target_position }}"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Username (opsional)</label>
                <input type="text" name="username" value="{{ $o['username'] ?? '' }}"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Bio LinkedIn</label>
            <textarea name="linkedin_bio" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ $o['linkedin_bio'] ?? '' }}</textarea>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">Bio Instagram</label>
                <textarea name="instagram_bio" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ $o['instagram_bio'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Bio TikTok</label>
                <textarea name="tiktok_bio" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ $o['tiktok_bio'] ?? '' }}</textarea>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Link Portfolio</label>
            <input type="text" name="portfolio_url" value="{{ $o['portfolio_url'] ?? '' }}" placeholder="https://..."
                   class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <button type="submit" :disabled="loading"
                class="w-full rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 px-5 py-3 text-sm font-semibold text-white disabled:opacity-60">
            <span x-show="!loading">Audit Personal Branding</span>
            <span x-show="loading" x-cloak>Menganalisis profil...</span>
        </button>
    </form>

    @if ($result)
        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center justify-center">
                <x-score-ring :score="$result['personal_branding_score'] ?? 0" label="Branding Score" />
            </div>
            <div class="lg:col-span-2 rounded-2xl bg-gradient-to-br from-blue-600 to-purple-600 p-6 text-white shadow-sm flex items-center">
                <p class="text-sm text-white/90">{{ $result['summary'] ?? '' }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 font-semibold text-amber-700">⚠️ Yang Perlu Diperbaiki</h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    @foreach ($result['problems'] ?? [] as $p)<li class="flex gap-2"><span class="text-amber-500">•</span>{{ $p }}</li>@endforeach
                </ul>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 font-semibold text-emerald-700">✓ Saran Perbaikan</h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    @foreach ($result['improvements'] ?? [] as $i)<li class="flex gap-2"><span class="text-emerald-500">→</span>{{ $i }}</li>@endforeach
                </ul>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                <h3 class="mb-2 font-semibold text-emerald-800">💼 Bio LinkedIn (saran)</h3>
                <p class="text-sm text-emerald-900">{{ $result['linkedin_bio_suggestion'] ?? '' }}</p>
            </div>
            <div class="rounded-2xl border border-purple-200 bg-purple-50 p-6 shadow-sm">
                <h3 class="mb-2 font-semibold text-purple-800">📸 Bio Instagram (saran)</h3>
                <p class="text-sm text-purple-900">{{ $result['instagram_bio_suggestion'] ?? '' }}</p>
            </div>
        </div>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 font-semibold text-blue-700">✅ Checklist Sebelum Apply</h3>
            <div class="grid gap-2 sm:grid-cols-2">
                @foreach ($result['before_apply_checklist'] ?? [] as $c)
                    <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" class="rounded text-emerald-500"> {{ $c }}</label>
                @endforeach
            </div>
        </div>
    @endif
</x-dashboard-layout>
