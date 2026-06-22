<x-dashboard-layout title="Social Audit">
    <x-page-head icon="users" gradient="from-blue-500 to-violet-600"
                 title="Social Media HR Check"
                 subtitle="Cek personal branding kamu sebelum apply. Tanpa scraping — kamu isi sendiri datanya." />

    @php $o = $old ?? []; @endphp
    <form method="POST" action="{{ route('social-audit.check') }}" x-data="{ loading: false }" @submit="loading = true"
          class="cl-rise space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:60ms">
        @csrf
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">Target Role</label>
                <input type="text" name="target_role" value="{{ $o['target_role'] ?? auth()->user()->target_position }}"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Username (opsional)</label>
                <input type="text" name="username" value="{{ $o['username'] ?? '' }}"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Bio LinkedIn</label>
            <textarea name="linkedin_bio" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $o['linkedin_bio'] ?? '' }}</textarea>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">Bio Instagram</label>
                <textarea name="instagram_bio" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $o['instagram_bio'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Bio TikTok</label>
                <textarea name="tiktok_bio" rows="2" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $o['tiktok_bio'] ?? '' }}</textarea>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Link Portfolio</label>
            <input type="text" name="portfolio_url" value="{{ $o['portfolio_url'] ?? '' }}" placeholder="https://..."
                   class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <button type="submit" :disabled="loading"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:scale-[1.01] disabled:opacity-60">
            <span x-show="!loading" class="flex items-center gap-2"><x-icon name="users" class="h-4 w-4"/> Audit Personal Branding</span>
            <span x-show="loading" x-cloak>Menganalisis profil...</span>
        </button>
    </form>

    @if ($result)
        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center justify-center">
                <x-score-ring :score="$result['personal_branding_score'] ?? 0" label="Branding Score" />
            </div>
            <div class="cl-rise lg:col-span-2 rounded-2xl bg-gradient-to-br from-blue-600 to-violet-600 p-6 text-white shadow-sm flex items-center" style="--reveal-delay:60ms">
                <p class="text-sm text-white/90">{{ $result['summary'] ?? '' }}</p>
            </div>
        </div>
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 flex items-center gap-2 font-semibold text-amber-700"><x-icon name="bolt" class="h-5 w-5"/> Yang Perlu Diperbaiki</h3>
                <ul class="space-y-2 text-sm text-slate-600">@foreach ($result['problems'] ?? [] as $p)<li class="flex gap-2"><span class="text-amber-500">•</span>{{ $p }}</li>@endforeach</ul>
            </div>
            <div class="cl-rise rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay:60ms">
                <h3 class="mb-3 flex items-center gap-2 font-semibold text-emerald-700"><x-icon name="check-c" class="h-5 w-5"/> Saran Perbaikan</h3>
                <ul class="space-y-2 text-sm text-slate-600">@foreach ($result['improvements'] ?? [] as $i)<li class="flex gap-2"><span class="text-emerald-500">→</span>{{ $i }}</li>@endforeach</ul>
            </div>
        </div>
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="cl-rise rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm">
                <h3 class="mb-2 font-semibold text-indigo-800">💼 Bio LinkedIn (saran)</h3>
                <p class="text-sm text-indigo-900">{{ $result['linkedin_bio_suggestion'] ?? '' }}</p>
            </div>
            <div class="cl-rise rounded-2xl border border-violet-200 bg-violet-50 p-6 shadow-sm" style="--reveal-delay:60ms">
                <h3 class="mb-2 font-semibold text-violet-800">📸 Bio Instagram (saran)</h3>
                <p class="text-sm text-violet-900">{{ $result['instagram_bio_suggestion'] ?? '' }}</p>
            </div>
        </div>
        <div class="cl-rise mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 flex items-center gap-2 font-semibold text-indigo-700"><x-icon name="check" class="h-5 w-5"/> Checklist Sebelum Apply</h3>
            <div class="grid gap-2 sm:grid-cols-2">
                @foreach ($result['before_apply_checklist'] ?? [] as $c)
                    <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" class="rounded text-indigo-500"> {{ $c }}</label>
                @endforeach
            </div>
        </div>
    @endif
</x-dashboard-layout>
