<x-admin-layout :title="$plan->exists ? 'Edit Plan' : 'Plan Baru'" role="admin">
    <form method="POST" action="{{ $plan->exists ? route('admin.plans.update', $plan) : route('admin.plans.store') }}"
          class="max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        @csrf
        @if ($plan->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-slate-700">Nama Plan</label>
            <input type="text" name="name" required value="{{ old('name', $plan->name) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div><label class="block text-sm font-medium text-slate-700">Harga (Rp)</label>
                <input type="number" name="price" required value="{{ old('price', $plan->price) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm"></div>
            <div><label class="block text-sm font-medium text-slate-700">Durasi (hari)</label>
                <input type="number" name="duration_days" required value="{{ old('duration_days', $plan->duration_days) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm"></div>
        </div>
        <p class="text-xs text-slate-400">Limit: gunakan <strong>-1</strong> untuk unlimited, <strong>0</strong> untuk tidak tersedia.</p>
        <div class="grid gap-4 sm:grid-cols-4">
            @foreach (['cv_review_limit'=>'CV Review','interview_limit'=>'Interview','job_match_limit'=>'Job Match','report_limit'=>'Report'] as $f=>$lbl)
                <div><label class="block text-xs font-medium text-slate-700">{{ $lbl }}</label>
                    <input type="number" name="{{ $f }}" required value="{{ old($f, $plan->$f) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm"></div>
            @endforeach
        </div>
        <div class="flex flex-wrap gap-6">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="has_manual_review" value="1" @checked(old('has_manual_review', $plan->has_manual_review)) class="rounded"> Manual Review</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="has_consultation" value="1" @checked(old('has_consultation', $plan->has_consultation)) class="rounded"> Konsultasi</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true)) class="rounded"> Aktif</label>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Fitur (1 per baris)</label>
            <textarea name="features_text" rows="5" class="mt-1 w-full rounded-xl border-slate-300 text-sm">{{ old('features_text', implode("\n", $plan->features ?? [])) }}</textarea>
        </div>
        <div class="flex gap-2">
            <button class="rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-5 py-2.5 text-sm font-semibold text-white">Simpan</button>
            <a href="{{ route('admin.plans.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
