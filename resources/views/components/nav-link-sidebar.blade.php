@props(['active' => false, 'href' => '#'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 bg-[#F2F2EB] text-clean-dark rounded-2xl font-bold transition duration-200 shadow-sm'
            : 'flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-clean-dark rounded-2xl font-medium transition duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'href' => $href]) }}>
    <span class="flex-1">{{ $slot }}</span>
    @if($active)
        <div class="w-1.5 h-1.5 bg-clean-dark rounded-full"></div>
    @endif
</a>