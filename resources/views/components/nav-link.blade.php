@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'group flex items-center gap-3 rounded-xl bg-white px-3 py-2.5 text-sm font-bold text-slate-950 shadow-sm shadow-slate-950/20 transition focus:outline-none focus:ring-2 focus:ring-indigo-400'
            : 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-400 transition hover:bg-slate-900 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-400';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($icon))
        <i data-lucide="{{ $icon }}" class="h-4 w-4 {{ ($active ?? false) ? 'text-indigo-600' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
    @endif
    <span>{{ $slot }}</span>
</a>
