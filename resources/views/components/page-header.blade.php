@props([
    'title',
    'subtitle' => null,
    'searchAction' => null,
    'searchPlaceholder' => 'Cari...',
    'searchValue' => request('search', ''),
    'searchName' => 'search',
    'createRoute' => null,
    'createLabel' => null,
])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    {{-- Left: Title & Subtitle / Badges --}}
    <div class="min-w-0">
        <h2 class="text-lg font-bold text-dark leading-tight truncate">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $subtitle }}</p>
        @endif
        {{ $titleExtra ?? '' }}
    </div>

    {{-- Right: Search Form & Action Buttons --}}
    <div class="flex items-center gap-2 w-full sm:w-auto">
        {{-- Search Input Form --}}
        @if($searchAction)
            <form method="GET" action="{{ $searchAction }}" class="relative flex-1 sm:w-60 md:w-72">
                {{-- Preserve existing query parameters except search, reset, and page --}}
                @foreach(request()->query() as $key => $val)
                    @if($key !== $searchName && $key !== 'page' && !is_array($val))
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach

                <input type="text" 
                       name="{{ $searchName }}" 
                       value="{{ $searchValue }}" 
                       placeholder="{{ $searchPlaceholder }}"
                       class="w-full pl-9 pr-8 py-2 text-xs bg-white/95 border border-gray-200/80 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition shadow-2xs text-gray-700 placeholder-gray-400">
                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                @if($searchValue)
                    <a href="{{ $searchAction }}" 
                       class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-red-500 transition-colors" 
                       title="Hapus pencarian">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </form>
        @endif

        {{-- Custom slot for extra action buttons (filters, dropdowns, etc.) --}}
        {{ $actions ?? '' }}

        {{-- Primary Add / Create Button --}}
        @if($createRoute && $createLabel)
            <a href="{{ $createRoute }}" 
               class="btn-primary text-xs py-2 px-3.5 rounded-lg flex items-center gap-1.5 shadow-sm font-semibold shrink-0 whitespace-nowrap active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>{{ $createLabel }}</span>
            </a>
        @endif
    </div>
</div>
