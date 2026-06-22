@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl border-slate-300 py-2.5 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500']) }}>
