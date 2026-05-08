@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-semibold leading-5 text-gray-900 focus:outline-none border-indigo-500 transition duration-150 ease-in-out gap-2'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out gap-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($icon))
        <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ ($active ?? false) ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    @endif
    {{ $slot }}
</a>
